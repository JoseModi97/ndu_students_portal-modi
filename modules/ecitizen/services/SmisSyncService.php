<?php

namespace app\modules\ecitizen\services;

use app\modules\ecitizen\models\smis\SmisAcademicProgress;
use app\modules\ecitizen\models\smis\SmisAcademicSession;
use app\modules\ecitizen\models\smis\SmisBankAccount;
use app\modules\ecitizen\models\smis\SmisBankingSlip;
use app\modules\ecitizen\models\smis\SmisEcitizen;
use app\modules\ecitizen\models\smis\SmisFeePayment;
use app\modules\ecitizen\models\smis\SmisFeeTransaction;
use app\modules\ecitizen\models\smis\SmisPaymentType;
use app\modules\ecitizen\models\smis\SmisStudent;
use app\modules\ecitizen\models\smis\SmisStudentProgCurriculum;
use app\modules\ecitizen\models\smis\SmisStudentSemesterSessionProgress;
use Yii;
use yii\db\Connection;
use yii\db\IntegrityException;

/**
 * Best-effort mirror of the eCitizen payment records into the standalone
 * `smis` database, in addition to the `smisportal` writes PaymentService
 * already performs.
 *
 * `smisportal` remains the source of truth for the student-facing payment
 * flow: every method here is fire-and-forget from the caller's point of
 * view. Failures (missing student in smis, missing bank mapping, a schema
 * difference, smis being briefly unreachable, ...) are logged and
 * swallowed so they never affect the primary smisportal transaction that
 * already committed by the time these run.
 *
 * Because smis and smisportal are separate physical databases, every
 * foreign key (academic progress, programme, collection point/bank) is
 * re-resolved against smis's own tables via natural keys (registration
 * number, bank code + account number) rather than reusing smisportal's
 * numeric ids, which are meaningless in smis.
 */
final class SmisSyncService
{
    private const LOG_CATEGORY = 'ecitizen.smis_sync';

    private ?Connection $db = null;

    private function connection(): Connection
    {
        if ($this->db === null) {
            $this->db = Yii::$app->get('smisDb');
        }

        return $this->db;
    }

    /**
     * Mirrors the current state of a smisportal.ecitizen row into
     * smis.ecitizen, matched (and inserted, if missing) by payment_id.
     * Only columns that actually exist on the smis table are written,
     * since smis.ecitizen predates some of the sync/reconciliation
     * columns that were later added to smisportal.ecitizen only.
     */
    public function mirrorEcitizenState(array $portalRow): void
    {
        $paymentId = $portalRow['payment_id'] ?? null;
        $billRefNumber = (string) ($portalRow['billRefNumber'] ?? '');
        if (empty($paymentId)) {
            return;
        }

        $this->safely('mirror eCitizen state for ' . $billRefNumber, function () use ($portalRow, $paymentId): void {
            $db = $this->connection();
            $table = 'smis.ecitizen';
            $schema = $db->schema->getTableSchema($table, true);
            if ($schema === null) {
                return;
            }

            $attributes = [];
            foreach ($portalRow as $column => $value) {
                if ($column !== 'payment_id' && isset($schema->columns[$column])) {
                    $attributes[$column] = $value;
                }
            }

            $transaction = $db->beginTransaction();
            try {
                $db->createCommand("SET LOCAL smisportal.ecitizen_app_write = '1'")->execute();

                $existing = SmisEcitizen::find()->where(['payment_id' => $paymentId])->one();
                if ($existing !== null) {
                    $existing->setAttributes($attributes, false);
                    if ($existing->save(false) === false) {
                        throw new \RuntimeException('Unable to update mirrored eCitizen row: ' . json_encode($existing->getErrors()));
                    }
                } else {
                    $row = new SmisEcitizen();
                    $row->setAttributes(array_merge(['payment_id' => $paymentId], $attributes), false);
                    if (!$row->save(false)) {
                        throw new \RuntimeException('Unable to insert mirrored eCitizen row: ' . json_encode($row->getErrors()));
                    }
                }

                $transaction->commit();
            } catch (\Throwable $exception) {
                $transaction->rollBack();
                throw $exception;
            }
        });
    }

    /**
     * Mirrors the fee statement credit (fss_fee_transactions CR row and
     * fss_fee_payments row) that PaymentService::creditPortalFeeStatement
     * created in smisportal, using the same shared $sharedTransId so the
     * two databases stay joinable on trans_id for this payment.
     */
    public function mirrorFeeCredit(
        array $request,
        float $amount,
        string $paymentDate,
        string $gatewayReference,
        array $metadata,
        int $sharedTransId
    ): void {
        $registrationNumber = (string) ($request['registration_number'] ?? '');
        $this->safely('mirror fee credit for ' . ($request['billRefNumber'] ?? $registrationNumber), function () use (
            $request,
            $amount,
            $paymentDate,
            $gatewayReference,
            $metadata,
            $sharedTransId,
            $registrationNumber
        ): void {
            if ($registrationNumber === '') {
                return;
            }

            $context = $this->resolveContext($registrationNumber);
            if ($context === null) {
                Yii::warning(
                    "SMIS mirror skipped fee credit: student {$registrationNumber} could not be resolved in smis.",
                    self::LOG_CATEGORY
                );
                return;
            }

            $db = $this->connection();
            $transaction = $db->beginTransaction();
            try {
                $existingCredit = SmisFeeTransaction::find()
                    ->where(['trans_id' => (string) $sharedTransId, 'trans_type' => 'CR'])
                    ->exists();

                if (!$existingCredit) {
                    $description = substr((string) ($request['billDesc'] ?? 'eCitizen student fee payment'), 0, 150);
                    $userId = (string) ($metadata['user_id'] ?? $registrationNumber);
                    $progressCode = $this->progressCode($context['academicProgress'], $registrationNumber);

                    $feeTransaction = new SmisFeeTransaction();
                    $feeTransaction->setAttributes([
                        'trans_id' => (string) $sharedTransId,
                        'academic_progress_id' => $context['academicProgress']['academic_progress_id'],
                        'trans_date' => $paymentDate,
                        'trans_type' => 'CR',
                        'trans_amount' => $amount,
                        'trans_desc' => $description,
                        'user_id' => $userId,
                        'receipt_status' => '',
                        'exchange_rate' => 1,
                        'progress_code' => $progressCode,
                        'sync_status' => false,
                        'student_semester_session_id' => $this->studentSemesterSessionId(
                            (int) $context['academicProgress']['academic_progress_id']
                        ),
                    ], false);
                    if (!$feeTransaction->save(false)) {
                        throw new \RuntimeException('Unable to mirror fee transaction: ' . json_encode($feeTransaction->getErrors()));
                    }
                }

                $existingPayment = SmisFeePayment::find()->where(['trans_id' => $sharedTransId])->exists();
                $bankId = $this->resolveBankId($metadata['account_no'] ?? null, $metadata['bank_number'] ?? null);

                if (!$existingPayment && $bankId !== null) {
                    $userId = (string) ($metadata['user_id'] ?? $registrationNumber);
                    $paymentAttributes = [
                        'receipt_no' => $this->receiptNumber($gatewayReference, (string) ($request['billRefNumber'] ?? '')),
                        'trans_date' => $paymentDate,
                        'trans_amount' => $amount,
                        'pay_mode' => PaymentService::PAYMENT_MODE_ID,
                        'collection_point_id' => $bankId,
                        'user_id' => $userId,
                        'entry_date' => date('Y-m-d'),
                        'trans_id' => $sharedTransId,
                        'academic_session' => '',
                        'authorized_by' => $userId,
                        'authorized_date' => date('Y-m-d'),
                        'receipt_status' => '',
                        'exchange_rate' => 1,
                        'student_prog_curriculum_id' => $context['programme']['student_prog_curriculum_id'],
                    ];
                    if (!$this->columnHasDatabaseGeneratedValue('smis.fss_fee_payments', 'fee_paymt_id')) {
                        $paymentAttributes['fee_paymt_id'] = $sharedTransId;
                    }

                    $feePayment = new SmisFeePayment();
                    $feePayment->setAttributes($paymentAttributes, false);
                    if (!$feePayment->save(false)) {
                        throw new \RuntimeException('Unable to mirror fee payment: ' . json_encode($feePayment->getErrors()));
                    }
                } elseif (!$existingPayment) {
                    Yii::warning(
                        "SMIS mirror skipped fee payment for trans_id {$sharedTransId}: no matching bank account in smis.",
                        self::LOG_CATEGORY
                    );
                }

                $transaction->commit();
            } catch (\Throwable $exception) {
                $transaction->rollBack();
                throw $exception;
            }
        });
    }

    /**
     * Mirrors the NDU service-code catalog seeding into smis.fss_payment_types.
     *
     * @param array<int, string> $catalog service code => description
     */
    public function mirrorPaymentTypes(array $catalog): void
    {
        if ($catalog === []) {
            return;
        }

        $this->safely('mirror payment types catalog', function () use ($catalog): void {
            $db = $this->connection();
            $table = 'smis.fss_payment_types';
            $schema = $db->schema->getTableSchema($table, true);
            if ($schema === null) {
                return;
            }

            $serviceCodes = array_keys($catalog);
            $existingRows = SmisPaymentType::find()
                ->select(['payment_type_id', 'payment_desc'])
                ->where(['payment_type_id' => $serviceCodes])
                ->asArray()
                ->all();
            $existingRowsById = [];
            foreach ($existingRows as $row) {
                $existingRowsById[(int) $row['payment_type_id']] = $row;
            }

            $hasOrderPriority = isset($schema->columns['order_priority']);
            $hasEntryType = isset($schema->columns['entry_type']);
            $hasPaymentFrequency = isset($schema->columns['payment_frequency']);

            $priority = 1;
            foreach ($catalog as $paymentTypeId => $description) {
                $paymentTypeId = (int) $paymentTypeId;
                $description = substr($description, 0, 150);
                if (isset($existingRowsById[$paymentTypeId])) {
                    $updates = [];
                    if ((string) ($existingRowsById[$paymentTypeId]['payment_desc'] ?? '') !== $description) {
                        $updates['payment_desc'] = $description;
                    }
                    if ($hasOrderPriority) {
                        $updates['order_priority'] = $priority;
                    }
                    if ($updates !== []) {
                        $db->createCommand()->update($table, $updates, ['payment_type_id' => $paymentTypeId])->execute();
                    }
                    $priority++;
                    continue;
                }

                $row = [
                    'payment_type_id' => $paymentTypeId,
                    'payment_desc' => $description,
                ];
                if ($hasOrderPriority) {
                    $row['order_priority'] = $priority;
                }
                if ($hasEntryType) {
                    $row['entry_type'] = 'CR';
                }
                if ($hasPaymentFrequency) {
                    $row['payment_frequency'] = 'ONCE';
                }

                try {
                    $db->createCommand()->insert($table, $row)->execute();
                } catch (IntegrityException) {
                    // Another request seeded the same service code first.
                }
                $priority++;
            }
        });
    }

    /**
     * Mirrors the console-only postPaidBankingSlip flow: finds or creates
     * the smis banking slip by source/trans reference, marks it POSTED,
     * and ensures a matching fee transaction + fee payment in smis. Uses
     * smis's own banking-slip trans_id as the shared key between those
     * two rows (this flow never touches the eCitizen gateway table).
     */
    public function mirrorBankingSlipPosted(
        array $slip,
        string $paymentDate,
        string $gatewayReference,
        string $paymentDescription
    ): void {
        $reference = (string) ($slip['source_reference'] ?: $slip['trans_reference'] ?? '');
        if ($reference === '') {
            return;
        }

        $this->safely('mirror banking slip posted for ' . $reference, function () use (
            $slip,
            $paymentDate,
            $gatewayReference,
            $paymentDescription,
            $reference
        ): void {
            $registrationNumber = (string) ($slip['reg_number'] ?? $slip['registration_number'] ?? '');
            $context = $registrationNumber !== '' ? $this->resolveContext($registrationNumber) : null;

            $db = $this->connection();
            $transaction = $db->beginTransaction();
            try {
                $smisSlip = SmisBankingSlip::find()
                    ->where(['or', ['source_reference' => $reference], ['trans_reference' => $reference]])
                    ->orderBy(['trans_id' => SORT_DESC])
                    ->one();

                if ($smisSlip === null) {
                    $bankId = $this->resolveBankId($slip['account_no'] ?? null, $slip['bank_number'] ?? null);
                    $smisSlip = new SmisBankingSlip();
                    $smisSlip->setAttributes([
                        'deposit_date' => $paymentDate,
                        'deposit_type' => $slip['deposit_type'] ?? $slip['payment_type_id'] ?? null,
                        'payment_type_id' => $slip['payment_type_id'] ?? $slip['deposit_type'] ?? null,
                        'deposit_amount' => $slip['deposit_amount'],
                        'reg_number' => $slip['reg_number'] ?? $registrationNumber,
                        'registration_number' => $slip['registration_number'] ?? $registrationNumber,
                        'other_names' => $slip['other_names'] ?? '',
                        'post_status' => 'POSTED',
                        'post_comment' => substr($paymentDescription, 0, 20),
                        'account_no' => $slip['account_no'] ?? null,
                        'process_date' => date('Y-m-d'),
                        'pay_mode' => PaymentService::PAYMENT_MODE_ID,
                        'trans_reference' => substr($gatewayReference ?: $reference, 0, 50),
                        'branch_code' => $slip['branch_code'] ?? null,
                        'last_update' => date('Y-m-d H:i:s'),
                        'user_id' => $slip['user_id'] ?? null,
                        'drawer_name' => 'eCitizen',
                        'source_reference' => $reference,
                        'value_date' => date('Y-m-d H:i:s'),
                        'bank_id' => $bankId,
                        'bank_number' => $slip['bank_number'] ?? null,
                    ], false);
                    if (!$smisSlip->save(false)) {
                        throw new \RuntimeException('Unable to mirror banking slip: ' . json_encode($smisSlip->getErrors()));
                    }
                } else {
                    $smisSlip->setAttributes([
                        'deposit_date' => $paymentDate,
                        'process_date' => date('Y-m-d'),
                        'post_status' => 'POSTED',
                        'post_comment' => substr($paymentDescription, 0, 20),
                        'trans_reference' => substr($gatewayReference ?: $reference, 0, 50),
                        'last_update' => date('Y-m-d H:i:s'),
                    ], false);
                    if (!$smisSlip->save(false)) {
                        throw new \RuntimeException('Unable to update mirrored banking slip: ' . json_encode($smisSlip->getErrors()));
                    }
                }

                if ($context !== null) {
                    $this->ensureBankingSlipFeeTransaction($smisSlip->trans_id, (float) $slip['deposit_amount'], $paymentDate, $paymentDescription, $context);
                    $this->ensureBankingSlipFeePayment($smisSlip, (float) $slip['deposit_amount'], $paymentDate, $context);
                }

                $transaction->commit();
            } catch (\Throwable $exception) {
                $transaction->rollBack();
                throw $exception;
            }
        });
    }

    private function ensureBankingSlipFeeTransaction(
        int $transId,
        float $amount,
        string $paymentDate,
        string $paymentDescription,
        array $context
    ): void {
        $existing = SmisFeeTransaction::find()->where(['trans_id' => $transId])->exists();
        if ($existing) {
            SmisFeeTransaction::updateAll([
                'trans_desc' => substr($paymentDescription, 0, 150),
            ], ['trans_id' => $transId]);
            return;
        }

        $registrationNumber = (string) $context['programme']['registration_number'];
        $progressCode = $this->progressCode($context['academicProgress'], $registrationNumber);

        $feeTransaction = new SmisFeeTransaction();
        $feeTransaction->setAttributes([
            'trans_id' => $transId,
            'academic_progress_id' => $context['academicProgress']['academic_progress_id'],
            'trans_date' => $paymentDate,
            'trans_type' => 'CR',
            'trans_amount' => $amount,
            'trans_desc' => substr($paymentDescription, 0, 150),
            'user_id' => $registrationNumber,
            'receipt_status' => '',
            'exchange_rate' => 1,
            'progress_code' => $progressCode,
            'student_semester_session_id' => $this->studentSemesterSessionId(
                (int) $context['academicProgress']['academic_progress_id']
            ),
        ], false);
        if (!$feeTransaction->save(false)) {
            throw new \RuntimeException('Unable to mirror banking-slip fee transaction: ' . json_encode($feeTransaction->getErrors()));
        }
    }

    private function ensureBankingSlipFeePayment(SmisBankingSlip $smisSlip, float $amount, string $paymentDate, array $context): void
    {
        $existingPayment = SmisFeePayment::find()->where(['trans_id' => $smisSlip->trans_id])->exists();
        if ($existingPayment || empty($smisSlip->bank_id)) {
            return;
        }

        $userId = (string) ($smisSlip->user_id ?: $context['programme']['registration_number']);
        $paymentAttributes = [
            'receipt_no' => (string) $smisSlip->trans_id,
            'trans_date' => $paymentDate,
            'trans_amount' => $amount,
            'pay_mode' => PaymentService::PAYMENT_MODE_ID,
            'collection_point_id' => (int) $smisSlip->bank_id,
            'user_id' => $userId,
            'entry_date' => date('Y-m-d'),
            'trans_id' => $smisSlip->trans_id,
            'academic_session' => '',
            'authorized_by' => $userId,
            'authorized_date' => date('Y-m-d'),
            'receipt_status' => '',
            'exchange_rate' => 1,
            'student_prog_curriculum_id' => $context['programme']['student_prog_curriculum_id'],
        ];
        if (!$this->columnHasDatabaseGeneratedValue('smis.fss_fee_payments', 'fee_paymt_id')) {
            $paymentAttributes['fee_paymt_id'] = $smisSlip->trans_id;
        }

        $feePayment = new SmisFeePayment();
        $feePayment->setAttributes($paymentAttributes, false);
        if (!$feePayment->save(false)) {
            throw new \RuntimeException('Unable to mirror banking-slip fee payment: ' . json_encode($feePayment->getErrors()));
        }
    }

    /**
     * Resolves a student's programme/academic-progress context from smis's
     * own tables by registration number. Returns null (never throws) if
     * the student isn't found in smis, so callers can skip the mirror.
     */
    private function resolveContext(string $registrationNumber): ?array
    {
        $student = SmisStudent::find()
            ->where(['student_number' => $registrationNumber])
            ->asArray()
            ->one();
        if (!$student) {
            return null;
        }

        $programme = SmisStudentProgCurriculum::find()
            ->where(['registration_number' => $registrationNumber])
            ->orderBy(['student_prog_curriculum_id' => SORT_DESC])
            ->asArray()
            ->one();
        if (!$programme) {
            return null;
        }

        $academicProgress = SmisAcademicProgress::find()
            ->where(['student_prog_curriculum_id' => $programme['student_prog_curriculum_id']])
            ->orderBy(['academic_progress_id' => SORT_DESC])
            ->asArray()
            ->one();
        if (!$academicProgress) {
            return null;
        }

        return [
            'student' => $student,
            'programme' => $programme,
            'academicProgress' => $academicProgress,
        ];
    }

    /**
     * Resolves smis's own bank surrogate id (fss_banks.brank_id) from the
     * natural keys stored on the smisportal metadata (account number and
     * bank code), since the smisportal-side numeric ids are meaningless
     * in the smis database.
     */
    private function resolveBankId(?string $accountNo, ?string $bankCode): ?int
    {
        $accountNo = trim((string) $accountNo);
        if ($accountNo === '') {
            return null;
        }

        $query = SmisBankAccount::find()
            ->alias('ba')
            ->select(['bank.brank_id'])
            ->joinWith(['branch branch' => static fn ($q) => $q->joinWith('bank bank')], false)
            ->where(['ba.account_no' => $accountNo]);

        $bankCode = trim((string) $bankCode);
        if ($bankCode !== '') {
            $query->andWhere(['branch.bank_code' => $bankCode]);
        }

        $bankId = $query->scalar();

        return $bankId !== false && $bankId !== null ? (int) $bankId : null;
    }

    private function progressCode(array $academicProgress, string $registrationNumber): string
    {
        $sessionName = SmisAcademicSession::find()
            ->select('acad_session_name')
            ->where(['acad_session_id' => $academicProgress['acad_session_id']])
            ->scalar();

        return $registrationNumber . '-' . ($sessionName ?: $academicProgress['acad_session_id']);
    }

    private function studentSemesterSessionId(int $academicProgressId): ?int
    {
        try {
            $id = SmisStudentSemesterSessionProgress::find()
                ->select('student_semester_session_id')
                ->where(['academic_progress_id' => $academicProgressId])
                ->orderBy(['student_semester_session_id' => SORT_DESC])
                ->scalar();
        } catch (\Throwable) {
            return null;
        }

        return $id === false || $id === null ? null : (int) $id;
    }

    private function columnHasDatabaseGeneratedValue(string $table, string $column): bool
    {
        $schema = $this->connection()->schema->getTableSchema($table, true);
        $tableColumn = $schema?->columns[$column] ?? null;

        return $tableColumn !== null && ($tableColumn->autoIncrement || $tableColumn->defaultValue !== null);
    }

    private function receiptNumber(string $gatewayReference, string $fallback): string
    {
        $receiptNo = trim($gatewayReference) !== '' ? $gatewayReference : $fallback;
        return substr($receiptNo, 0, 30);
    }

    private function safely(string $description, callable $fn): void
    {
        try {
            $fn();
        } catch (\Throwable $exception) {
            Yii::warning(
                'SMIS mirror failed (' . $description . '): ' . $exception->getMessage(),
                self::LOG_CATEGORY
            );
        }
    }
}
