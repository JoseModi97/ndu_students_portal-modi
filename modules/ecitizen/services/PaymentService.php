<?php

namespace app\modules\ecitizen\services;

use app\models\AcademicProgress;
use app\models\Ecitizen;
use app\models\FeePayment;
use app\models\FeeTransaction;
use app\models\AcademicSession;
use app\models\SmisportalSmAdmittedStudent;
use app\models\Student;
use app\models\StudentProgCurriculum;
use app\models\StudentSemesterSessionProgress;
use app\modules\ecitizen\Module;
use app\modules\ecitizen\models\BankAccount;
use app\modules\ecitizen\models\BankingSlip;
use app\modules\ecitizen\models\PaymentMode;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\db\IntegrityException;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class PaymentService
{
    public const PAYMENT_MODE_ID = 12;
    public const SYNC_PENDING = 0;
    public const SYNC_DONE = 1;
    public const SYNC_FAILED = 2;
    public const RECONCILIATION_CONFIRMED = 'confirmed';
    public const RECONCILIATION_REVIEW_REQUIRED = 'review_required';
    public const RECONCILIATION_UNKNOWN = 'unknown';
    private const REMOTE_SETTLED_STATUSES = ['PAID', 'SUCCESS', 'COMPLETED', 'SETTLED'];
    private const REMOTE_REVERSAL_STATUSES = ['REVERSED', 'REFUNDED', 'REVERSE', 'REFUND'];
    private const PORTAL_ECITIZEN_TRANS_ID_OFFSET = 900000000000;

    private Connection $db;
    private SmisSyncService $smisSync;

    public function __construct()
    {
        $this->db = $this->portalDb();
        $this->smisSync = new SmisSyncService();
    }

    private function module(): Module
    {
        return Yii::$app->getModule('ecitizen');
    }

    private function portalDb(): Connection
    {
        return $this->module()->connection($this->module()->portalDb);
    }

    private function params(): array
    {
        return $this->module()->ecitizenParams();
    }

    public function invoiceToken(int $transId, string $registrationNumber): string
    {
        if ($transId < 1 || trim($registrationNumber) === '') {
            throw new InvalidConfigException('A valid invoice and student are required to create an invoice token.');
        }

        $payload = json_encode([
            'v' => 1,
            'trans_id' => $transId,
            'issued_at' => time(),
        ], JSON_THROW_ON_ERROR);
        $encrypted = Yii::$app->security->encryptByKey(
            $payload,
            $this->invoiceTokenKey(),
            $this->invoiceTokenContext($registrationNumber)
        );

        return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
    }

    public function transIdFromInvoiceToken(string $token, string $registrationNumber): int
    {
        try {
            if ($token === '' || strlen($token) > 1024 || preg_match('/^[A-Za-z0-9_-]+$/D', $token) !== 1) {
                throw new \UnexpectedValueException('Malformed token.');
            }

            $padding = (4 - strlen($token) % 4) % 4;
            $encrypted = base64_decode(
                strtr($token, '-_', '+/') . str_repeat('=', $padding),
                true
            );
            if ($encrypted === false) {
                throw new \UnexpectedValueException('Malformed token.');
            }

            $decrypted = Yii::$app->security->decryptByKey(
                $encrypted,
                $this->invoiceTokenKey(),
                $this->invoiceTokenContext($registrationNumber)
            );
            if ($decrypted === false) {
                throw new \UnexpectedValueException('Invalid token.');
            }

            $payload = json_decode($decrypted, true, 2, JSON_THROW_ON_ERROR);
            $transId = filter_var($payload['trans_id'] ?? null, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1],
            ]);
            $issuedAt = filter_var($payload['issued_at'] ?? null, FILTER_VALIDATE_INT);
            $now = time();
            $ttl = max(60, (int) ($this->params()['invoiceTokenTtl'] ?? 900));
            if (($payload['v'] ?? null) !== 1
                || $transId === false
                || $issuedAt === false
                || $issuedAt > $now + 60
                || $issuedAt < $now - $ttl
            ) {
                throw new \UnexpectedValueException('Invalid token payload.');
            }

            return $transId;
        } catch (\Throwable $exception) {
            Yii::warning('Rejected invalid eCitizen invoice token: ' . $exception->getMessage(), 'ecitizen.payment');
            throw new BadRequestHttpException('The invoice link is invalid. Please open it from your invoice list.');
        }
    }

    private function invoiceTokenKey(): string
    {
        $params = $this->params();
        $keyMaterial = (string) ($params['invoiceTokenKey'] ?? '');
        if (strlen($keyMaterial) < 32) {
            throw new InvalidConfigException('A dedicated eCitizen invoice token key of at least 32 characters is required.');
        }

        return hash('sha256', $keyMaterial, true);
    }

    private function invoiceTokenContext(string $registrationNumber): string
    {
        return 'ecitizen-invoice-v1:' . strtoupper(trim($registrationNumber));
    }

    public function resolveLoggedInStudent(): array
    {
        $identity = Yii::$app->user->identity;
        $studentProgramme = StudentProgCurriculum::find()
            ->select('registration_number')
            ->where(['adm_refno' => $identity->adm_refno])
            ->asArray()
            ->one();

        if (empty($studentProgramme['registration_number'])) {
            throw new NotFoundHttpException('Registration number not found for the logged in student.');
        }

        $registrationNumber = $studentProgramme['registration_number'];
        $student = Student::find()
            ->where(['student_number' => $registrationNumber])
            ->asArray()
            ->one();

        if (!$student) {
            throw new NotFoundHttpException('The logged in student was not found in the portal database.');
        }

        $programme = StudentProgCurriculum::find()
            ->where(['registration_number' => $registrationNumber])
            ->orderBy(['student_prog_curriculum_id' => SORT_DESC])
            ->asArray()
            ->one();

        if (!$programme) {
            throw new NotFoundHttpException('The logged in student programme curriculum was not found in the portal database.');
        }

        $academicProgress = AcademicProgress::find()
            ->where(['student_prog_curriculum_id' => $programme['student_prog_curriculum_id']])
            ->orderBy(['academic_progress_id' => SORT_DESC])
            ->asArray()
            ->one();

        if (!$academicProgress) {
            throw new NotFoundHttpException('The logged in student academic progress was not found in the portal database.');
        }

        return [
            'registrationNumber' => $registrationNumber,
            'student' => $student,
            'programme' => $programme,
            'academicProgress' => $academicProgress,
        ];
    }

    public function paymentModeExists(): bool
    {
        // Payment mode 12 is carried on the portal payment rows. The legacy
        // reference-row requirement applied to the removed direct posting step.
        return true;
    }

    public function outstandingFeeBalance(string $registrationNumber): float
    {
        $progressPrefix = $registrationNumber . '-';
        $transactions = FeeTransaction::find()
            ->select(['trans_type', 'trans_amount'])
            ->where(['like', 'progress_code', $progressPrefix, false])
            ->asArray()
            ->all($this->db);
        $balance = 0.0;
        foreach ($transactions as $transaction) {
            if ($transaction['trans_type'] === 'DR') {
                $balance += (float) $transaction['trans_amount'];
            } elseif ($transaction['trans_type'] === 'CR') {
                $balance -= (float) $transaction['trans_amount'];
            }
        }

        return round((float) $balance, 2);
    }

    public function paymentTypes(): array
    {
        $catalog = $this->serviceCatalog();
        $this->ensurePaymentTypesExist($catalog);

        $serviceCodes = array_keys($catalog);
        $paymentTypes = (new Query())
            ->select(['payment_type_id', 'payment_desc'])
            ->from('smisportal.fss_payment_types')
            ->where(['payment_type_id' => $serviceCodes])
            ->all($this->db);
        $order = array_flip(array_map('intval', $serviceCodes));
        usort($paymentTypes, static fn (array $a, array $b): int => $order[(int) $a['payment_type_id']] <=> $order[(int) $b['payment_type_id']]);

        return $paymentTypes;
    }

    /**
     * Seeds missing eCitizen service-code rows into smisportal.fss_payment_types.
     *
     * This is called by paymentTypes(), so the dropdown self-heals as soon as
     * /ecitizen/payment/index loads. Existing IDs are checked first and duplicate
     * insert races are ignored safely.
     *
     * @param array<int, string> $catalog service code => description
     */
    private function ensurePaymentTypesExist(array $catalog): void
    {
        if ($catalog === []) {
            return;
        }

        $serviceCodes = array_keys($catalog);
        $table = 'smisportal.fss_payment_types';
        $schema = $this->db->schema->getTableSchema($table, true);
        if ($schema === null) {
            throw new InvalidConfigException('The portal payment types table was not found.');
        }

        $existingRows = (new Query())
            ->select(['payment_type_id', 'payment_desc'])
            ->from($table)
            ->where(['payment_type_id' => $serviceCodes])
            ->all($this->db);
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
                    $this->db->createCommand()
                        ->update($table, $updates, ['payment_type_id' => $paymentTypeId])
                        ->execute();
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
                $this->db->createCommand()
                    ->insert($table, $row)
                    ->execute();
            } catch (IntegrityException) {
                // Another request seeded the same service code first.
            }
            $priority++;
        }

        $this->smisSync->mirrorPaymentTypes($catalog);
    }

    public function serviceIdForPaymentType(int $paymentTypeId): string
    {
        $catalog = $this->serviceCatalog();
        if (!isset($catalog[$paymentTypeId])) {
            throw new InvalidConfigException('This transaction may be managed under a separate eCitizen account.');
        }

        return (string) $paymentTypeId;
    }

    /**
     * @return array<int, string> service code => service description
     */
    public function serviceCatalog(): array
    {
        static $catalog;
        if ($catalog !== null) {
            return $catalog;
        }

        $path = dirname(__DIR__) . '/NDU SERVICE CODES.xlsx';
        if (!is_file($path)) {
            throw new InvalidConfigException('NDU service codes workbook was not found in the eCitizen module.');
        }

        $archive = new \ZipArchive();
        if ($archive->open($path) !== true) {
            throw new InvalidConfigException('NDU service codes workbook could not be opened.');
        }

        try {
            $sharedStringsXml = $archive->getFromName('xl/sharedStrings.xml');
            $sheetXml = $archive->getFromName('xl/worksheets/sheet1.xml');
            if ($sharedStringsXml === false || $sheetXml === false) {
                throw new InvalidConfigException('NDU service codes workbook has an invalid worksheet structure.');
            }

            $sharedDocument = new \DOMDocument();
            $sheetDocument = new \DOMDocument();
            if (!@$sharedDocument->loadXML($sharedStringsXml) || !@$sheetDocument->loadXML($sheetXml)) {
                throw new InvalidConfigException('NDU service codes workbook contains invalid XML.');
            }

            $sharedXPath = new \DOMXPath($sharedDocument);
            $sharedXPath->registerNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $sharedStrings = [];
            foreach ($sharedXPath->query('//x:si') as $item) {
                $value = '';
                foreach ($sharedXPath->query('.//x:t', $item) as $textNode) {
                    $value .= $textNode->nodeValue;
                }
                $sharedStrings[] = trim(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            }

            $sheetXPath = new \DOMXPath($sheetDocument);
            $sheetXPath->registerNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $catalog = [];
            foreach ($sheetXPath->query('//x:sheetData/x:row[position() > 1]') as $row) {
                $service = null;
                $serviceCode = null;
                foreach ($sheetXPath->query('./x:c', $row) as $cell) {
                    $column = substr($cell->getAttribute('r'), 0, 1);
                    $valueNode = $sheetXPath->query('./x:v', $cell)->item(0);
                    if ($valueNode === null) {
                        continue;
                    }

                    $value = $valueNode->nodeValue;
                    if ($cell->getAttribute('t') === 's') {
                        $value = $sharedStrings[(int) $value] ?? '';
                    }

                    if ($column === 'A') {
                        $service = trim((string) $value);
                    } elseif ($column === 'B') {
                        $serviceCode = filter_var($value, FILTER_VALIDATE_INT);
                    }
                }

                if ($service !== null && $service !== '' && $serviceCode !== false && $serviceCode !== null) {
                    $catalog[$serviceCode] ??= $service;
                }
            }
        } finally {
            $archive->close();
        }

        if ($catalog === []) {
            throw new InvalidConfigException('NDU service codes workbook contains no services.');
        }

        return $catalog;
    }

    public function bankAccounts(): array
    {
        return BankAccount::find()
            ->alias('ba')
            ->select([
                'ba.brank_account_id',
                'ba.account_no',
                'ba.account_details',
                'ba.branch_code',
                'branch.branch_name',
                'branch.bank_code',
                'bank.brank_id',
                'bank.bank_name',
            ])
            ->joinWith(['branch branch' => static fn ($query) => $query->joinWith('bank bank')], false)
            ->distinct()
            ->orderBy(['bank.bank_name' => SORT_ASC, 'ba.account_no' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public function findBankAccount(int $bankAccountId): ?array
    {
        $account = BankAccount::find()
            ->alias('ba')
            ->select([
                'ba.brank_account_id',
                'ba.account_no',
                'ba.account_details',
                'ba.branch_code',
                'branch.branch_name',
                'branch.bank_code',
                'bank.brank_id',
                'bank.bank_name',
            ])
            ->joinWith(['branch branch' => static fn ($query) => $query->joinWith('bank bank')], false)
            ->where(['ba.brank_account_id' => $bankAccountId])
            ->distinct()
            ->asArray()
            ->one();

        return $account ?: null;
    }

    public function defaultCoopBankAccount(): ?array
    {
        foreach ($this->bankAccounts() as $account) {
            $label = implode(' ', array_filter([
                $account['bank_name'] ?? null,
                $account['account_details'] ?? null,
                $account['account_no'] ?? null,
            ]));

            if ($this->isCoopBankAccountLabel($label)) {
                return $account;
            }
        }

        return null;
    }

    private function isCoopBankAccountLabel(string $label): bool
    {
        return preg_match('/\bco[-\s]?op(?:erative)?\b|\bco[-\s]?operative\b/i', $label) === 1;
    }

    public function recentRequests(string $registrationNumber): array
    {
        return BankingSlip::find()
            ->select(['trans_id', 'deposit_date', 'deposit_amount', 'post_status', 'post_comment', 'trans_reference', 'source_reference'])
            ->where([
                'reg_number' => $registrationNumber,
                'pay_mode' => self::PAYMENT_MODE_ID,
            ])
            ->orderBy(['trans_id' => SORT_DESC])
            ->limit(10)
            ->asArray()
            ->all();
    }

    public function invoiceRequests(string $registrationNumber): array
    {
        $bankingSlipInvoices = BankingSlip::find()
            ->select([
                'trans_id',
                'deposit_date',
                'deposit_amount',
                'reg_number',
                'registration_number',
                'post_status',
                'post_comment',
                'receipt_no',
                'pay_mode',
                'trans_reference',
                'source_reference',
                'last_update',
            ])
            ->where([
                'reg_number' => $registrationNumber,
                'pay_mode' => self::PAYMENT_MODE_ID,
            ])
            ->orderBy(['trans_id' => SORT_DESC])
            ->asArray()
            ->all();
        $this->attachFeePaymentFlags($bankingSlipInvoices);

        return array_merge($bankingSlipInvoices, $this->pendingInvoiceRequests($registrationNumber));
    }

    public function findInvoiceRequest(int $transId, string $registrationNumber): ?array
    {
        $request = BankingSlip::find()
            ->where([
                'trans_id' => $transId,
                'reg_number' => $registrationNumber,
                'pay_mode' => self::PAYMENT_MODE_ID,
            ])
            ->asArray()
            ->one();

        if ($request) {
            $this->attachFeePaymentFlags($request);
            return $request;
        }

        $pending = Ecitizen::find()
            ->where([
                'payment_id' => $transId,
                'registration_number' => $registrationNumber,
            ])
            ->andWhere([
                'or',
                ['status' => ['Pending', 'Paid', 'Credited', 'Settled']],
                ['status' => null],
            ])
            ->asArray()
            ->one();
        $pending = $pending ? $this->ecitizenInvoiceRow($pending) : null;

        return $pending ?: null;
    }

    private function pendingInvoiceRequests(string $registrationNumber): array
    {
        $requests = array_map(
            fn (array $row): array => $this->ecitizenInvoiceRow($row),
            Ecitizen::find()
                ->where(['registration_number' => $registrationNumber])
                ->andWhere([
                    'or',
                    ['status' => ['Pending', 'Paid', 'Credited', 'Settled']],
                    ['status' => null],
                ])
                ->orderBy(['payment_id' => SORT_DESC])
                ->asArray()
                ->all()
        );

        if (empty($requests)) {
            return [];
        }

        $references = array_values(array_filter(array_map(
            static fn (array $request): string => (string) ($request['source_reference'] ?? ''),
            $requests
        )));

        if (empty($references)) {
            return $requests;
        }

        $existingReferences = BankingSlip::find()
            ->select('source_reference')
            ->where(['source_reference' => $references])
            ->column($this->db);
        $existingReferences = array_flip(array_map('strval', $existingReferences));

        return array_values(array_filter($requests, static function (array $request) use ($existingReferences): bool {
            return !isset($existingReferences[(string) ($request['source_reference'] ?? '')]);
        }));
    }

    private function ecitizenInvoiceRow(array $row): array
    {
        $status = (string) ($row['status'] ?? 'Pending');

        return [
            'trans_id' => $row['payment_id'],
            'deposit_date' => isset($row['trans_date']) ? substr((string) $row['trans_date'], 0, 10) : null,
            'deposit_amount' => $row['amountExpected'] ?? null,
            'reg_number' => $row['registration_number'] ?? null,
            'registration_number' => $row['registration_number'] ?? null,
            'post_status' => $status === 'Paid' ? 'Credited' : $status,
            'post_comment' => $row['billDesc'] ?? null,
            'receipt_no' => null,
            'pay_mode' => self::PAYMENT_MODE_ID,
            'trans_reference' => $row['billRefNumber'] ?? null,
            'source_reference' => $row['billRefNumber'] ?? null,
            'response' => $row['response'] ?? null,
            'last_update' => $row['trans_date'] ?? null,
            'has_fee_payment' => in_array($status ?: 'Pending', ['Credited', 'Settled'], true) ? 1 : 0,
        ];
    }

    private function attachFeePaymentFlags(array &$rows): void
    {
        $singleRow = isset($rows['trans_id']);
        $list = $singleRow ? [$rows] : $rows;
        $transIds = array_values(array_filter(array_map(
            static fn (array $row): int => (int) ($row['trans_id'] ?? 0),
            $list
        )));
        $paymentTransIds = $transIds === []
            ? []
            : array_flip(array_map('intval', FeePayment::find()
                ->select('trans_id')
                ->where(['trans_id' => $transIds])
                ->column($this->db)));

        foreach ($list as &$row) {
            $row['has_fee_payment'] = isset($paymentTransIds[(int) ($row['trans_id'] ?? 0)]) ? 1 : 0;
        }
        unset($row);

        $rows = $singleRow ? ($list[0] ?? $rows) : $list;
    }

    public function workflowReport(): array
    {
        $identity = Yii::$app->user->identity;
        $admRefNo = $identity->adm_refno;

        $portalAdmittedStudent = SmisportalSmAdmittedStudent::find()
            ->select(['adm_refno', 'surname', 'other_names', 'primary_email', 'primary_phone_no', 'national_id', 'passport_no', 'admission_status'])
            ->where(['adm_refno' => $admRefNo])
            ->asArray()
            ->one() ?: [];

        $portalProgramme = StudentProgCurriculum::find()
            ->select(['student_prog_curriculum_id', 'student_id', 'registration_number', 'adm_refno', 'status_id'])
            ->where(['adm_refno' => $admRefNo])
            ->orderBy(['student_prog_curriculum_id' => SORT_DESC])
            ->asArray()
            ->one() ?: [];

        $registrationNumber = (string) ($portalProgramme['registration_number'] ?? '');
        $portalStudent = [];
        if ($registrationNumber !== '') {
            $portalStudent = Student::find()
                ->select(['student_id', 'student_number', 'surname', 'other_names', 'primary_email', 'primary_phone_no'])
                ->where(['student_number' => $registrationNumber])
                ->asArray()
                ->one() ?: [];
        }

        $smisStudent = [];
        $smisProgramme = [];
        $academicProgress = [];
        $workflowRows = [];
        if ($registrationNumber !== '') {
            $smisStudent = Student::find()
                ->select(['student_id', 'student_number', 'surname', 'other_names', 'primary_email', 'primary_phone_no'])
                ->where(['student_number' => $registrationNumber])
                ->asArray()
                ->one() ?: [];

            $smisProgramme = StudentProgCurriculum::find()
                ->select(['student_prog_curriculum_id', 'student_id', 'registration_number', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'])
                ->where(['registration_number' => $registrationNumber])
                ->orderBy(['student_prog_curriculum_id' => SORT_DESC])
                ->asArray()
                ->one() ?: [];

            if (!empty($smisProgramme['student_prog_curriculum_id'])) {
                $academicProgress = AcademicProgress::find()
                    ->select(['academic_progress_id', 'acad_session_id', 'academic_level_id', 'student_prog_curriculum_id', 'progress_status_id', 'current_status'])
                    ->where(['student_prog_curriculum_id' => $smisProgramme['student_prog_curriculum_id']])
                    ->orderBy(['academic_progress_id' => SORT_DESC])
                    ->asArray()
                    ->one() ?: [];
            }

            $workflowRows = BankingSlip::find()
                ->alias('bs')
                ->select([
                    'bs.trans_id',
                    'bs.reg_number',
                    'bs.deposit_date',
                    'bs.deposit_amount',
                    'bs.post_status',
                    'bs.post_comment',
                    'bs.receipt_no',
                    'bs.source_reference',
                    'bs.trans_reference',
                    'bs.last_update',
                    'fp.fee_paymt_id',
                    'fee_payment_date' => 'fp.trans_date',
                    'fee_payment_amount' => 'fp.trans_amount',
                    'fp.student_prog_curriculum_id',
                    'ft.academic_progress_id',
                    'ft.trans_type',
                    'fee_transaction_amount' => 'ft.trans_amount',
                    'ft.trans_desc',
                    'sync_status' => null,
                ])
                ->leftJoin(FeePayment::tableName() . ' fp', 'fp.trans_id = bs.trans_id')
                ->leftJoin(FeeTransaction::tableName() . ' ft', 'ft.trans_id = bs.trans_id')
                ->where([
                    'bs.pay_mode' => self::PAYMENT_MODE_ID,
                    'bs.reg_number' => $registrationNumber,
                ])
                ->orderBy(['bs.trans_id' => SORT_DESC])
                ->asArray()
                ->all();
        }

        $paymentMode = PaymentMode::find()
            ->select(['payment_mode_id', 'mode_code', 'description', 'mode_flag'])
            ->where(['payment_mode_id' => self::PAYMENT_MODE_ID])
            ->asArray()
            ->one() ?: [];

        $bankAccounts = $this->bankAccounts();
        $counts = [
            'bankingSlips' => count($workflowRows),
            'postedSlips' => count(array_filter($workflowRows, static fn ($row) => strtoupper((string) $row['post_status']) === 'POSTED')),
            'feePayments' => count(array_filter($workflowRows, static fn ($row) => !empty($row['fee_paymt_id']))),
            'feeTransactions' => count(array_filter($workflowRows, static fn ($row) => !empty($row['academic_progress_id']) && $row['trans_type'] === 'CR')),
        ];

        return [
            'admRefNo' => $admRefNo,
            'registrationNumber' => $registrationNumber,
            'portal' => [
                'admittedStudent' => $portalAdmittedStudent,
                'programme' => $portalProgramme,
                'student' => $portalStudent,
            ],
            'portalFinance' => [
                'paymentMode' => $paymentMode,
                'student' => $smisStudent,
                'programme' => $smisProgramme,
                'academicProgress' => $academicProgress,
                'bankAccounts' => $bankAccounts,
                'workflowRows' => $workflowRows,
                'counts' => $counts,
            ],
            'sql' => $this->workflowSql($admRefNo, $registrationNumber),
        ];
    }

    public function workflowSql(int|string|null $admRefNo = null, ?string $registrationNumber = null): array
    {
        $admRefValue = $admRefNo === null || $admRefNo === ''
            ? ':adm_refno'
            : $this->db->quoteValue((string) $admRefNo);
        $registrationValue = $registrationNumber === null || $registrationNumber === ''
            ? ':registration_number'
            : $this->db->quoteValue($registrationNumber);

        return [
            'portalDb' => [
                'Admitted student' => "SELECT adm_refno, surname, other_names, primary_email, primary_phone_no, national_id, passport_no, admission_status\nFROM smisportal.sm_admitted_student\nWHERE adm_refno = {$admRefValue};",
                'Portal programme curriculum' => "SELECT student_prog_curriculum_id, student_id, registration_number, adm_refno, status_id\nFROM smisportal.sm_student_programme_curriculum\nWHERE adm_refno = {$admRefValue};",
                'Portal student' => "SELECT student_id, student_number, surname, other_names, primary_email, primary_phone_no\nFROM smisportal.sm_student\nWHERE student_number = {$registrationValue};",
            ],
            'portalFinance' => [
                'eCitizen payment mode' => "SELECT payment_mode_id, mode_code, description, mode_flag\nFROM smisportal.fss_payment_modes\nWHERE payment_mode_id = 12;",
                'Portal student' => "SELECT student_id, student_number, surname, other_names, primary_email, primary_phone_no\nFROM smisportal.sm_student\nWHERE student_number = {$registrationValue};",
                'Portal programme curriculum' => "SELECT student_prog_curriculum_id, student_id, registration_number, prog_curriculum_id, student_category_id, adm_refno, status_id\nFROM smisportal.sm_student_programme_curriculum\nWHERE registration_number = {$registrationValue}\nORDER BY student_prog_curriculum_id DESC;",
                'Academic progress' => "SELECT sap.academic_progress_id, sap.acad_session_id, sap.academic_level_id, sap.student_prog_curriculum_id, sap.progress_status_id, sap.current_status\nFROM smisportal.sm_academic_progress sap\nINNER JOIN smisportal.sm_student_programme_curriculum spc\n    ON spc.student_prog_curriculum_id = sap.student_prog_curriculum_id\nWHERE spc.registration_number = {$registrationValue}\nORDER BY sap.current_status DESC, sap.academic_progress_id DESC;",
                'Settlement bank accounts' => "SELECT ba.brank_account_id, ba.account_no, ba.account_details, ba.branch_code, bb.branch_name, bb.bank_code, b.brank_id, b.bank_name\nFROM smisportal.fss_bank_accounts ba\nLEFT JOIN smisportal.fss_bank_branches bb ON bb.branch_code = ba.branch_code\nLEFT JOIN smisportal.fss_banks b ON b.bank_code = bb.bank_code\nORDER BY b.bank_name, ba.account_no;",
                'Full eCitizen workflow trace' => "SELECT bs.trans_id, bs.reg_number, bs.deposit_date, bs.deposit_amount, bs.post_status, bs.post_comment, bs.receipt_no, bs.source_reference, bs.trans_reference, fp.fee_paymt_id, fp.trans_date AS fee_payment_date, fp.trans_amount AS fee_payment_amount, fp.student_prog_curriculum_id, ft.academic_progress_id, ft.trans_type, ft.trans_amount AS fee_transaction_amount, ft.trans_desc, NULL AS sync_status\nFROM smisportal.fss_banking_slips bs\nLEFT JOIN smisportal.fss_fee_payments fp ON fp.trans_id = bs.trans_id\nLEFT JOIN smisportal.fss_fee_transactions ft ON ft.trans_id = bs.trans_id\nWHERE bs.pay_mode = 12\n  AND bs.reg_number = {$registrationValue}\nORDER BY bs.trans_id DESC;",
            ],
        ];
    }

    public function createPendingBankingSlip(
        array $studentContext,
        array $bankAccount,
        float $amount,
        int $paymentTypeId,
        string $serviceId,
        string $narration
    ): array {
        $reference = $this->buildReference($studentContext['registrationNumber']);
        $student = $studentContext['student'];
        $identity = Yii::$app->user->identity;
        $config = $this->gatewayConfig();
        $clientName = trim(($student['surname'] ?? '') . ' ' . ($student['other_names'] ?? ''));
        $metadata = [
            'payment_type_id' => $paymentTypeId,
            'service_id' => $serviceId,
            'bank_account_id' => $bankAccount['brank_account_id'] ?? null,
            'account_no' => $bankAccount['account_no'] ?? null,
            'branch_code' => $bankAccount['branch_code'] ?? null,
            'bank_id' => $bankAccount['brank_id'] ?? null,
            'bank_number' => $bankAccount['bank_code'] ?? null,
            'other_names' => $clientName,
            'user_id' => $identity->adm_refno,
        ];

        $portalDb = $this->portalDb();
        $transaction = $portalDb->beginTransaction();
        try {
            $existing = Ecitizen::find()
                ->select('payment_id')
                ->where(['billRefNumber' => $reference])
                ->scalar($portalDb);

            if ($existing) {
                throw new ServerErrorHttpException('A payment request with the same reference already exists.');
            }

            $this->allowEcitizenWrite($portalDb);
            $payment = new Ecitizen();
            $payment->apiClientID = $config['apiClientID'];
            $payment->billDesc = substr($narration, 0, 100);
            $payment->billRefNumber = $reference;
            $payment->currency = $config['currency'];
            $payment->serviceID = $serviceId;
            $payment->clientMSISDN = $student['primary_phone_no'] ?: ($identity->primary_phone_no ?? null);
            $payment->clientName = $clientName;
            $payment->clientIDNumber = $student['id_no'] ?: $student['passport_no'] ?: ($identity->national_id ?? null) ?: ($identity->passport_no ?? null) ?: $studentContext['registrationNumber'];
            $payment->clientEmail = $student['primary_email'] ?: ($identity->primary_email ?? null);
            $payment->callBackURLOnSuccess = $this->callbackUrl('/ecitizen/payment/invoices');
            $payment->pictureURL = '';
            $payment->notificationURL = $this->callbackUrl('/ecitizen/payment/notify');
            $payment->amountExpected = $amount;
            $payment->registration_number = $studentContext['registrationNumber'];
            $payment->response = json_encode($metadata);
            $payment->status = 'Pending';
            if (!$payment->save(false)) {
                throw new ServerErrorHttpException('Unable to create the eCitizen payment request.');
            }

            $transaction->commit();
            $this->smisSync->mirrorEcitizenState($payment->toArray());
            return ['trans_id' => (int) $payment->payment_id, 'reference' => $reference];
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function queuePaidRequestForSync(
        string $reference,
        float $amount,
        string $paymentDate,
        string $gatewayReference,
        array $payload = []
    ): array {
        $portalDb = $this->portalDb();
        $transaction = $portalDb->beginTransaction();
        try {
            $request = Ecitizen::find()
                ->where(['billRefNumber' => $reference])
                ->orderBy(['payment_id' => SORT_DESC])
                ->asArray()
                ->one($portalDb);

            if (!$request) {
                throw new NotFoundHttpException('Payment request not found.');
            }

            $expectedAmount = round((float) $request['amountExpected'], 2);
            if (abs($expectedAmount - round($amount, 2)) > 0.01) {
                throw new ServerErrorHttpException('The paid amount does not match the requested amount.');
            }

            $metadata = json_decode((string) ($request['response'] ?? ''), true);
            $metadata = is_array($metadata) ? $metadata : [];
            $metadata['gateway_reference'] = $gatewayReference;
            $metadata['paid_amount'] = $amount;
            $metadata['payment_date'] = $paymentDate;
            if ($payload !== []) {
                $metadata['notification_payload'] = $payload;
            }

            $this->lockPosting($portalDb, $reference);

            if ((int) ($request['sync_status'] ?? self::SYNC_PENDING) === self::SYNC_DONE) {
                $this->creditPortalFeeStatement($portalDb, $request, $amount, $paymentDate, $gatewayReference, $metadata);
                $this->allowEcitizenWrite($portalDb);
                Ecitizen::updateAll([
                    'response' => json_encode($metadata),
                ], ['payment_id' => $request['payment_id']]);
                $transaction->commit();

                $this->mirrorCreditedRequestToSmis($request, $amount, $paymentDate, $gatewayReference, $metadata);

                return [
                    'payment_id' => (int) $request['payment_id'],
                    'reference' => $reference,
                ];
            }

            $this->allowEcitizenWrite($portalDb);
            Ecitizen::updateAll([
                'status' => 'Credited',
                'paid_amount' => $amount,
                'payment_date' => $paymentDate,
                'gateway_reference' => substr($gatewayReference ?: $reference, 0, 100),
                'response' => json_encode($metadata),
                'sync_status' => self::SYNC_PENDING,
                'sync_error' => null,
                'last_synced_at' => null,
            ], ['payment_id' => $request['payment_id']]);

            $this->creditPortalFeeStatement($portalDb, $request, $amount, $paymentDate, $gatewayReference, $metadata);
            Ecitizen::updateAll([
                'response' => json_encode($metadata),
            ], ['payment_id' => $request['payment_id']]);

            $transaction->commit();

            $this->mirrorCreditedRequestToSmis($request, $amount, $paymentDate, $gatewayReference, $metadata);

            return [
                'payment_id' => (int) $request['payment_id'],
                'reference' => $reference,
            ];
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    private function mirrorCreditedRequestToSmis(
        array $request,
        float $amount,
        string $paymentDate,
        string $gatewayReference,
        array $metadata
    ): void {
        $freshRow = Ecitizen::find()
            ->where(['payment_id' => $request['payment_id']])
            ->asArray()
            ->one($this->portalDb());
        if ($freshRow) {
            $this->smisSync->mirrorEcitizenState($freshRow);
        }

        $sharedTransId = (int) ($metadata['portal_fee_trans_id'] ?? 0);
        if ($sharedTransId <= 0) {
            return;
        }

        $registrationNumber = (string) ($request['registration_number'] ?? '');
        if ($registrationNumber === '') {
            return;
        }

        // smis and smisportal share the same numeric ids for academic progress,
        // programme curriculum and collection points (see feesManagement's
        // BankingSlips::postFeeTransactions in the smis app, which posts the
        // exact same ids into both databases). So we resolve these once
        // against smisportal here and hand them to smis as-is, rather than
        // re-resolving them independently against smis's own tables.
        try {
            $context = $this->portalStudentContextByRegistrationNumber($registrationNumber);
            $academicProgress = $context['academicProgress'];
            $progressCode = $this->progressCodeFor($this->portalDb(), $registrationNumber, (int) $academicProgress['acad_session_id']);
            $studentSemesterSessionId = $this->studentSemesterSessionId($this->portalDb(), (int) $academicProgress['academic_progress_id']);
        } catch (\Throwable $exception) {
            Yii::warning(
                'SMIS mirror skipped fee credit for ' . $registrationNumber . ': could not resolve smisportal context: ' . $exception->getMessage(),
                'ecitizen.smis_sync'
            );
            return;
        }

        $this->smisSync->mirrorFeeCredit(
            $request,
            $amount,
            $paymentDate,
            $gatewayReference,
            $metadata,
            $sharedTransId,
            (int) $academicProgress['academic_progress_id'],
            (int) $context['programme']['student_prog_curriculum_id'],
            $studentSemesterSessionId,
            $progressCode
        );
    }

    public function pendingPaidRequestsForSync(int $limit = 50): array
    {
        return Ecitizen::find()
            ->where(['status' => ['Paid', 'Credited']])
            ->andWhere(['sync_status' => self::SYNC_PENDING])
            ->orderBy(['payment_id' => SORT_ASC])
            ->limit(max(1, $limit))
            ->asArray()
            ->all($this->portalDb());
    }

    public function settledRequestsForReconciliation(int $limit = 100, int $minimumIntervalMinutes = 60): array
    {
        $limit = max(1, min($limit, 1000));
        $minimumIntervalMinutes = max(1, min($minimumIntervalMinutes, 10080));

        $cutoff = (new \DateTimeImmutable())->modify('-' . $minimumIntervalMinutes . ' minutes')->format('Y-m-d H:i:s');

        return Ecitizen::find()
            ->where(['status' => 'Settled'])
            ->andWhere([
                'or',
                ['last_status_checked_at' => null],
                ['<=', 'last_status_checked_at', $cutoff],
            ])
            ->orderBy(['last_status_checked_at' => SORT_ASC, 'payment_id' => SORT_ASC])
            ->limit($limit)
            ->asArray()
            ->all($this->portalDb());
    }

    public function reconcileSettledRequest(array $request): array
    {
        $reference = trim((string) ($request['billRefNumber'] ?? ''));
        if ($reference === '') {
            throw new \InvalidArgumentException('The settled eCitizen row has no bill reference.');
        }

        $payload = $this->queryPaymentStatus($reference);
        $classification = $this->classifyReconciliationPayload($request, $payload);
        $remoteStatus = $classification['remote_status'];
        $reconciliationStatus = $classification['reconciliation_status'];

        $portalDb = $this->portalDb();
        $transaction = $portalDb->beginTransaction();
        try {
            $this->lockPosting($portalDb, $reference);
            $currentReconciliationStatus = (string) Ecitizen::find()
                ->select('reconciliation_status')
                ->where([
                    'payment_id' => (int) $request['payment_id'],
                    'status' => 'Settled',
                ])
                ->forUpdate()
                ->scalar($portalDb);
            if ($currentReconciliationStatus === self::RECONCILIATION_REVIEW_REQUIRED) {
                // A confirmed reversal is a sticky audit condition and must be cleared manually.
                $reconciliationStatus = self::RECONCILIATION_REVIEW_REQUIRED;
            }

            $this->allowEcitizenWrite($portalDb);

            $attributes = [
                'remote_status' => $remoteStatus !== '' ? substr($remoteStatus, 0, 32) : null,
                'reconciliation_status' => $reconciliationStatus,
                'last_status_checked_at' => date('Y-m-d H:i:s'),
                'status_check_error' => null,
                'last_status_response' => json_encode($payload, JSON_UNESCAPED_SLASHES),
            ];
            if ($reconciliationStatus === self::RECONCILIATION_REVIEW_REQUIRED) {
                $attributes['reversal_detected_at'] = date('Y-m-d H:i:s');
            }

            Ecitizen::updateAll($attributes, [
                'payment_id' => (int) $request['payment_id'],
                'status' => 'Settled',
            ]);
            $transaction->commit();
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        $this->mirrorEcitizenRowByPaymentId((int) $request['payment_id']);

        return [
            'reference' => $reference,
            'remote_status' => $remoteStatus,
            'reconciliation_status' => $reconciliationStatus,
        ];
    }

    private function mirrorEcitizenRowByPaymentId(int $paymentId): void
    {
        $freshRow = Ecitizen::find()
            ->where(['payment_id' => $paymentId])
            ->asArray()
            ->one($this->portalDb());
        if ($freshRow) {
            $this->smisSync->mirrorEcitizenState($freshRow);
        }
    }

    public function classifyReconciliationPayload(array $request, array $payload): array
    {
        $remoteStatus = strtoupper(trim((string) ($payload['status'] ?? $payload['payment_status'] ?? '')));
        $reconciliationStatus = self::RECONCILIATION_UNKNOWN;

        if (in_array($remoteStatus, self::REMOTE_SETTLED_STATUSES, true)
            && $this->statusPayloadMatchesReconciliationRequest($request, $payload, true)
        ) {
            $reconciliationStatus = self::RECONCILIATION_CONFIRMED;
        } elseif (in_array($remoteStatus, self::REMOTE_REVERSAL_STATUSES, true)
            && $this->statusPayloadMatchesReconciliationRequest($request, $payload, true)
        ) {
            $reconciliationStatus = self::RECONCILIATION_REVIEW_REQUIRED;
        }

        return [
            'remote_status' => $remoteStatus,
            'reconciliation_status' => $reconciliationStatus,
        ];
    }

    public function markReconciliationFailed(int $paymentId, string $message): void
    {
        $portalDb = $this->portalDb();
        $transaction = $portalDb->beginTransaction();
        try {
            $this->allowEcitizenWrite($portalDb);
            Ecitizen::updateAll([
                'last_status_checked_at' => date('Y-m-d H:i:s'),
                'status_check_error' => substr($message, 0, 1000),
            ], [
                'payment_id' => $paymentId,
                'status' => 'Settled',
            ]);
            $transaction->commit();
            $this->mirrorEcitizenRowByPaymentId($paymentId);
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            Yii::warning(
                'Unable to record eCitizen reconciliation failure for payment ' . $paymentId . ': '
                . $exception->getMessage(),
                'ecitizen.payment'
            );
        }
    }

    private function statusPayloadMatchesReconciliationRequest(
        array $request,
        array $payload,
        bool $requireAmount
    ): bool {
        $reference = trim((string) (
            $payload['client_invoice_ref']
            ?? $payload['ref_no']
            ?? $payload['billRefNumber']
            ?? ''
        ));
        if ($reference === '' || !hash_equals((string) $request['billRefNumber'], $reference)) {
            return false;
        }

        if (!$requireAmount) {
            return true;
        }

        $amount = $this->paidAmount($payload);
        if ($amount === null) {
            foreach (['reversed_amount', 'refund_amount', 'amount_reversed', 'amount_refunded'] as $field) {
                if (isset($payload[$field]) && trim((string) $payload[$field]) !== '') {
                    $amount = round((float) $payload[$field], 2);
                    break;
                }
            }
        }

        return $amount !== null
            && abs($amount - round((float) $request['amountExpected'], 2)) <= 0.01;
    }

    public function markSyncFailed(string $reference, string $message): void
    {
        $portalDb = $this->portalDb();
        $transaction = $portalDb->beginTransaction();
        try {
            $this->allowEcitizenWrite($portalDb);
            Ecitizen::updateAll([
                'sync_status' => self::SYNC_FAILED,
                'sync_error' => substr($message, 0, 1000),
            ], ['billRefNumber' => $reference]);
            $transaction->commit();
            $this->mirrorEcitizenRowByReference($reference);
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            Yii::warning('Unable to mark eCitizen sync failed for ' . $reference . ': ' . $exception->getMessage(), 'ecitizen.payment');
        }
    }

    private function mirrorEcitizenRowByReference(string $billRefNumber): void
    {
        $freshRow = Ecitizen::find()
            ->where(['billRefNumber' => $billRefNumber])
            ->orderBy(['payment_id' => SORT_DESC])
            ->asArray()
            ->one($this->portalDb());
        if ($freshRow) {
            $this->smisSync->mirrorEcitizenState($freshRow);
        }
    }

    public function postPaidBankingSlip(string $reference, float $amount, string $paymentDate, string $gatewayReference, array $payload = []): int
    {
        $slip = BankingSlip::find()
            ->where([
                'or',
                ['source_reference' => $reference],
                ['trans_reference' => $reference],
            ])
            ->orderBy(['trans_id' => SORT_DESC])
            ->asArray()
            ->one($this->db);

        $pendingRequest = $this->pendingRequestByReference($reference);
        if (!$slip && !$pendingRequest) {
            throw new NotFoundHttpException('Payment request not found.');
        }

        $expectedAmount = $slip ? (float) $slip['deposit_amount'] : (float) $pendingRequest['amountExpected'];
        if (abs($expectedAmount - $amount) > 0.01) {
            throw new ServerErrorHttpException('The paid amount does not match the requested amount.');
        }

        if ($pendingRequest) {
            $portalExpectedAmount = round((float) $pendingRequest['amountExpected'], 2);
            if (abs($portalExpectedAmount - round($amount, 2)) > 0.01) {
                throw new ServerErrorHttpException('The paid amount does not match the requested amount.');
            }
        }

        $transaction = $this->db->beginTransaction();

        try {
            $this->lockPosting($this->db, $reference);
            if (!$slip) {
                $slip = $this->createSettledBankingSlip($pendingRequest, $paymentDate, $gatewayReference);
            }

            $paymentDescription = $this->paymentTypeDescription($slip);
            BankingSlip::updateAll([
                'deposit_date' => $paymentDate,
                'process_date' => date('Y-m-d'),
                'post_status' => 'POSTED',
                'post_comment' => substr($paymentDescription, 0, 20),
                'trans_reference' => substr($gatewayReference ?: $reference, 0, 50),
                'last_update' => date('Y-m-d H:i:s'),
            ], ['trans_id' => $slip['trans_id']]);

            $slip = BankingSlip::find()
                ->where(['trans_id' => $slip['trans_id']])
                ->asArray()
                ->one($this->db);

            $this->ensureFeeTransactionDescription($slip, $amount, $paymentDate, $paymentDescription);
            $this->ensureSmisFeePayment($slip, $amount, $paymentDate);

            $transaction->commit();
            $this->markPendingRequestSettled($reference, $gatewayReference, (int) $slip['trans_id'], $payload);
            $this->mirrorPostedSlipToSmis($slip, $paymentDate, $gatewayReference, $paymentDescription);
            return (int) $slip['trans_id'];
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    private function mirrorPostedSlipToSmis(array $slip, string $paymentDate, string $gatewayReference, string $paymentDescription): void
    {
        $registrationNumber = (string) ($slip['reg_number'] ?? $slip['registration_number'] ?? '');
        if ($registrationNumber === '') {
            return;
        }

        // See mirrorCreditedRequestToSmis: smis and smisportal share ids for
        // academic progress / programme curriculum, so we resolve them once
        // against smisportal and reuse them as-is on the smis side.
        try {
            $studentContext = $this->studentContextByRegistrationNumber($registrationNumber);
            $academicProgress = $studentContext['academicProgress'];
            $progressCode = $this->progressCode($studentContext['registrationNumber'], (int) $academicProgress['acad_session_id']);
            $studentSemesterSessionId = $this->studentSemesterSessionId($this->db, (int) $academicProgress['academic_progress_id']);
        } catch (\Throwable $exception) {
            Yii::warning(
                'SMIS mirror skipped banking slip posting for ' . $registrationNumber . ': could not resolve smisportal context: ' . $exception->getMessage(),
                'ecitizen.smis_sync'
            );
            return;
        }

        $this->smisSync->mirrorBankingSlipPosted(
            $slip,
            $paymentDate,
            $gatewayReference,
            $paymentDescription,
            (int) $academicProgress['academic_progress_id'],
            (int) $studentContext['programme']['student_prog_curriculum_id'],
            $studentSemesterSessionId,
            $progressCode
        );
    }

    public function gatewayConfig(): array
    {
        $config = $this->params();
        foreach (['apiClientID', 'apiKey', 'secret', 'url', 'currency'] as $key) {
            if (empty($config[$key])) {
                throw new InvalidConfigException("Missing eCitizen configuration value: {$key}.");
            }
        }

        $allowedGatewayHosts = array_values(array_filter(array_map(
            static fn (mixed $host): string => strtolower(trim((string) $host)),
            (array) ($config['allowedGatewayHosts'] ?? [])
        )));
        if ($allowedGatewayHosts === []) {
            throw new InvalidConfigException('No trusted eCitizen gateway hosts are configured.');
        }
        $this->assertTrustedHttpsUrl((string) $config['url'], $allowedGatewayHosts, 'eCitizen gateway URL');
        if (!empty($config['statusUrl'])) {
            $this->assertTrustedHttpsUrl((string) $config['statusUrl'], $allowedGatewayHosts, 'eCitizen status URL');
        }
        $this->assertTrustedHttpsUrl(
            (string) ($config['callbackBaseUrl'] ?? ''),
            [],
            'eCitizen callback base URL'
        );

        return $config;
    }

    private function pendingRequestByReference(string $reference): ?array
    {
        $request = Ecitizen::find()
            ->where(['billRefNumber' => $reference])
            ->andWhere([
                'or',
                ['status' => ['Pending', 'Paid', 'Credited', 'Settled']],
                ['status' => null],
            ])
            ->orderBy(['payment_id' => SORT_DESC])
            ->asArray()
            ->one($this->portalDb());

        return $request ?: null;
    }

    private function creditPortalFeeStatement(
        Connection $portalDb,
        array $request,
        float $amount,
        string $paymentDate,
        string $gatewayReference,
        array &$metadata
    ): void {
        $paymentId = (int) $request['payment_id'];
        $transId = (int) ($metadata['portal_fee_trans_id'] ?? 0);
        $existingCredit = false;

        if ($transId > 0) {
            $existingCredit = FeeTransaction::find()
                ->where(['trans_id' => $transId, 'trans_type' => 'CR'])
                ->exists($portalDb);
        }

        if (!$existingCredit) {
            $legacyTransId = $this->portalCreditTransId($paymentId);
            $existingCredit = FeeTransaction::find()
                ->where(['trans_id' => $legacyTransId, 'trans_type' => 'CR'])
                ->exists($portalDb);
            if ($existingCredit) {
                $transId = $legacyTransId;
            }
        }

        $registrationNumber = (string) $request['registration_number'];
        $context = $this->portalStudentContextByRegistrationNumber($registrationNumber);
        $description = substr((string) ($request['billDesc'] ?? 'eCitizen student fee payment'), 0, 150);
        $userId = (string) ($metadata['user_id'] ?? $registrationNumber);
        $progressCode = $this->progressCodeFor($portalDb, $registrationNumber, (int) $context['academicProgress']['acad_session_id']);

        if (!$existingCredit) {
            $feeTransaction = new FeeTransaction();
            $feeTransaction->academic_progress_id = $context['academicProgress']['academic_progress_id'];
            $feeTransaction->trans_date = $paymentDate;
            $feeTransaction->trans_type = 'CR';
            $feeTransaction->trans_amount = $amount;
            $feeTransaction->trans_desc = $description;
            $feeTransaction->user_id = $userId;
            $feeTransaction->receipt_status = '';
            $feeTransaction->exchange_rate = 1;
            $feeTransaction->progress_code = $progressCode;
            $feeTransaction->sync_status = false;
            $feeTransaction->student_semester_session_id = $this->studentSemesterSessionId($portalDb, (int) $context['academicProgress']['academic_progress_id']);
            if (!$feeTransaction->save(false)) {
                throw new ServerErrorHttpException('Unable to create portal fee transaction.');
            }
            $transId = (int) $feeTransaction->trans_id;
        }
        $metadata['portal_fee_trans_id'] = $transId;

        $existingPayment = FeePayment::find()
            ->where(['trans_id' => $transId])
            ->exists($portalDb);

        $collectionPointId = $metadata['bank_id'] ?? null;
        if ($existingPayment || $collectionPointId === null || $collectionPointId === '') {
            return;
        }

        $paymentAttributes = [
            'receipt_no' => $this->receiptNumber($gatewayReference, (string) $request['billRefNumber']),
            'trans_date' => $paymentDate,
            'trans_amount' => $amount,
            'pay_mode' => self::PAYMENT_MODE_ID,
            'collection_point_id' => (int) $collectionPointId,
            'user_id' => $userId,
            'entry_date' => date('Y-m-d'),
            'trans_id' => $transId,
            'academic_session' => '',
            'authorized_by' => $userId,
            'authorized_date' => date('Y-m-d'),
            'receipt_status' => '',
            'exchange_rate' => 1,
            'student_prog_curriculum_id' => $context['programme']['student_prog_curriculum_id'],
        ];
        if (!$this->columnHasDatabaseGeneratedValue($portalDb, 'smisportal.fss_fee_payments', 'fee_paymt_id')) {
            $paymentAttributes['fee_paymt_id'] = $transId;
        }

        $payment = new FeePayment();
        $payment->setAttributes($paymentAttributes, false);
        if (!$payment->save(false)) {
            throw new ServerErrorHttpException('Unable to create portal fee payment.');
        }
    }

    private function createSettledBankingSlip(array $request, string $paymentDate, string $gatewayReference): array
    {
        $metadata = json_decode((string) ($request['response'] ?? ''), true);
        $metadata = is_array($metadata) ? $metadata : [];
        $reference = (string) $request['billRefNumber'];
        $paymentTypeId = $metadata['payment_type_id'] ?? null;

        $slip = new BankingSlip();
        $slip->deposit_date = $paymentDate;
        $slip->deposit_type = $paymentTypeId;
        $slip->payment_type_id = $paymentTypeId;
        $slip->deposit_amount = (float) $request['amountExpected'];
        $slip->reg_number = $request['registration_number'];
        $slip->registration_number = $request['registration_number'];
        $slip->other_names = $metadata['other_names'] ?? $request['clientName'] ?? '';
        $slip->post_status = 'NOT POSTED';
        $slip->post_comment = substr((string) ($request['billDesc'] ?? 'eCitizen payment'), 0, 20);
        $slip->account_no = $metadata['account_no'] ?? null;
        $slip->process_date = date('Y-m-d');
        $slip->pay_mode = self::PAYMENT_MODE_ID;
        $slip->trans_reference = substr($gatewayReference ?: $reference, 0, 50);
        $slip->branch_code = $metadata['branch_code'] ?? null;
        $slip->last_update = date('Y-m-d H:i:s');
        $slip->user_id = $metadata['user_id'] ?? null;
        $slip->drawer_name = 'eCitizen';
        $slip->source_reference = $reference;
        $slip->value_date = date('Y-m-d H:i:s');
        $slip->bank_id = $metadata['bank_id'] ?? null;
        $slip->bank_number = $metadata['bank_number'] ?? null;
        if (!$slip->save(false)) {
            throw new ServerErrorHttpException('Unable to create portal banking slip.');
        }

        return BankingSlip::find()
            ->where(['trans_id' => $slip->trans_id])
            ->asArray()
            ->one($this->db);
    }

    private function markPendingRequestSettled(string $reference, string $gatewayReference, int $transId, array $payload = []): void
    {
        $portalDb = $this->portalDb();
        $transaction = $portalDb->beginTransaction();
        try {
            $existingResponse = Ecitizen::find()
                ->select('response')
                ->where(['billRefNumber' => $reference])
                ->scalar($portalDb);
            $metadata = json_decode((string) $existingResponse, true);
            $metadata = is_array($metadata) ? $metadata : [];
            $metadata['gateway_reference'] = $gatewayReference;
            if ($payload !== []) {
                $metadata['notification_payload'] = $payload;
            }

            $this->allowEcitizenWrite($portalDb);
            Ecitizen::updateAll([
                'status' => 'Settled',
                'response' => json_encode($metadata),
                'synced_trans_id' => $transId,
                'sync_status' => self::SYNC_DONE,
                'sync_error' => null,
                'last_synced_at' => date('Y-m-d H:i:s'),
            ], ['billRefNumber' => $reference]);
            $transaction->commit();
            $this->mirrorEcitizenRowByReference($reference);
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            Yii::warning('Unable to mark eCitizen request settled for ' . $reference . ': ' . $exception->getMessage(), 'ecitizen.payment');
        }
    }

    private function ensureFeeTransactionDescription(array $slip, float $amount, string $paymentDate, string $paymentDescription): void
    {
        $existing = FeeTransaction::find()
            ->where(['trans_id' => $slip['trans_id']])
            ->exists($this->db);

        if ($existing) {
            FeeTransaction::updateAll([
                'trans_desc' => substr($paymentDescription, 0, 150),
            ], ['trans_id' => $slip['trans_id']]);
            return;
        }

        $studentContext = $this->studentContextByRegistrationNumber((string) $slip['reg_number']);
        $academicProgress = $studentContext['academicProgress'];
        $progressCode = $this->progressCode($studentContext['registrationNumber'], (int) $academicProgress['acad_session_id']);

        $feeTransaction = new FeeTransaction();
        $feeTransaction->trans_id = $slip['trans_id'];
        $feeTransaction->academic_progress_id = $academicProgress['academic_progress_id'];
        $feeTransaction->trans_date = $paymentDate;
        $feeTransaction->trans_type = 'CR';
        $feeTransaction->trans_amount = $amount;
        $feeTransaction->trans_desc = substr($paymentDescription, 0, 150);
        $feeTransaction->user_id = (string) ($slip['user_id'] ?? '');
        $feeTransaction->receipt_status = '';
        $feeTransaction->exchange_rate = 1;
        $feeTransaction->progress_code = $progressCode;
        $feeTransaction->student_semester_session_id = $this->studentSemesterSessionId($this->db, (int) $academicProgress['academic_progress_id']);
        if (!$feeTransaction->save(false)) {
            throw new ServerErrorHttpException('Unable to create portal fee transaction.');
        }
    }

    private function ensureSmisFeePayment(array $slip, float $amount, string $paymentDate): void
    {
        $existingPayment = FeePayment::find()
            ->where(['trans_id' => $slip['trans_id']])
            ->asArray()
            ->one($this->db);

        if ($existingPayment) {
            if (empty($slip['receipt_no']) && !empty($existingPayment['receipt_no']) && ctype_digit((string) $existingPayment['receipt_no'])) {
                BankingSlip::updateAll([
                    'receipt_no' => (int) $existingPayment['receipt_no'],
                    'post_status' => 'POSTED',
                    'last_update' => date('Y-m-d H:i:s'),
                ], ['trans_id' => $slip['trans_id']]);
            }
            return;
        }

        if (empty($slip['bank_id'])) {
            throw new ServerErrorHttpException('The eCitizen banking slip is missing a collection point.');
        }

        $studentContext = $this->studentContextByRegistrationNumber((string) $slip['reg_number']);
        $receiptNo = $this->ensureSmisReceiptNo($slip);
        $userId = (string) ($slip['user_id'] ?? $slip['reg_number'] ?? '');

        $feePayment = new FeePayment();
        $feePayment->receipt_no = (string) $receiptNo;
        $feePayment->trans_date = $paymentDate;
        $feePayment->trans_amount = $amount;
        $feePayment->pay_mode = self::PAYMENT_MODE_ID;
        $feePayment->collection_point_id = (int) $slip['bank_id'];
        $feePayment->user_id = $userId;
        $feePayment->entry_date = date('Y-m-d');
        $feePayment->trans_id = (int) $slip['trans_id'];
        $feePayment->academic_session = '';
        $feePayment->authorized_by = $userId;
        $feePayment->authorized_date = date('Y-m-d');
        $feePayment->receipt_status = '';
        $feePayment->exchange_rate = 1;
        $feePayment->student_prog_curriculum_id = $studentContext['programme']['student_prog_curriculum_id'];
        if (!$feePayment->save(false)) {
            throw new ServerErrorHttpException('Unable to create portal fee payment.');
        }

        BankingSlip::updateAll([
            'receipt_no' => $receiptNo,
            'post_status' => 'POSTED',
            'last_update' => date('Y-m-d H:i:s'),
        ], ['trans_id' => $slip['trans_id']]);
    }

    private function ensureSmisReceiptNo(array $slip): int
    {
        if (!empty($slip['receipt_no'])) {
            return (int) $slip['receipt_no'];
        }

        $lastValue = $this->highestNumericReceiptNumber();
        $nextValue = $lastValue + 1;

        BankingSlip::updateAll([
            'receipt_no' => $nextValue,
        ], ['trans_id' => $slip['trans_id']]);

        return $nextValue;
    }

    private function highestNumericReceiptNumber(): int
    {
        $lastValue = 0;
        $receiptNumbers = array_merge(
            FeePayment::find()
                ->select('receipt_no')
                ->where(['not', ['receipt_no' => null]])
                ->column($this->db),
            BankingSlip::find()
                ->select('receipt_no')
                ->where(['not', ['receipt_no' => null]])
                ->column($this->db)
        );

        foreach ($receiptNumbers as $receiptNumber) {
            $receiptNumber = trim((string) $receiptNumber);
            if ($receiptNumber !== '' && ctype_digit($receiptNumber)) {
                $lastValue = max($lastValue, (int) $receiptNumber);
            }
        }

        return $lastValue;
    }

    private function portalStudentContextByRegistrationNumber(string $registrationNumber): array
    {
        $programme = StudentProgCurriculum::find()
            ->where(['registration_number' => $registrationNumber])
            ->orderBy(['student_prog_curriculum_id' => SORT_DESC])
            ->asArray()
            ->one();
        $academicProgress = AcademicProgress::find()
            ->where(['student_prog_curriculum_id' => $programme['student_prog_curriculum_id'] ?? null])
            ->orderBy(['academic_progress_id' => SORT_DESC])
            ->asArray()
            ->one();

        if (!$programme || !$academicProgress) {
            throw new NotFoundHttpException('Student portal fee posting records could not be resolved.');
        }

        return [
            'registrationNumber' => $registrationNumber,
            'programme' => $programme,
            'academicProgress' => $academicProgress,
        ];
    }

    private function studentSemesterSessionId(Connection $db, int $academicProgressId): ?int
    {
        try {
            $id = StudentSemesterSessionProgress::find()
                ->select('student_semester_session_id')
                ->where(['academic_progress_id' => $academicProgressId])
                ->orderBy(['student_semester_session_id' => SORT_DESC])
                ->scalar($db);
        } catch (\Throwable) {
            return null;
        }

        return $id === false || $id === null ? null : (int) $id;
    }

    private function portalCreditTransId(int $paymentId): int
    {
        return self::PORTAL_ECITIZEN_TRANS_ID_OFFSET + $paymentId;
    }

    private function smisEcitizenPaymentId(int $transId): int
    {
        return self::PORTAL_ECITIZEN_TRANS_ID_OFFSET + $transId;
    }

    private function lockPosting(Connection $db, string $reference): void
    {
        static $locks = [];
        static $releaseRegistered = false;

        $lockDir = Yii::getAlias('@runtime/ecitizen-locks');
        if (!is_dir($lockDir)) {
            @mkdir($lockDir, 0775, true);
        }

        $lockKey = hash('sha256', 'ecitizen-payment-' . $reference);
        $handle = fopen($lockDir . DIRECTORY_SEPARATOR . $lockKey . '.lock', 'c');
        if ($handle === false || !flock($handle, LOCK_EX)) {
            throw new ServerErrorHttpException('Unable to lock the eCitizen payment for posting.');
        }

        $locks[] = $handle;
        if (!$releaseRegistered) {
            register_shutdown_function(static function () use (&$locks): void {
                foreach ($locks as $lock) {
                    if (is_resource($lock)) {
                        flock($lock, LOCK_UN);
                        fclose($lock);
                    }
                }
                $locks = [];
            });
            $releaseRegistered = true;
        }
    }

    private function allowEcitizenWrite(Connection $db): void
    {
        // eCitizen writes are now performed through the smisportal ActiveRecord model.
    }

    private function columnHasDatabaseGeneratedValue(Connection $db, string $table, string $column): bool
    {
        $schema = $db->schema->getTableSchema($table, true);
        $tableColumn = $schema?->columns[$column] ?? null;

        return $tableColumn !== null && ($tableColumn->autoIncrement || $tableColumn->defaultValue !== null);
    }

    private function receiptNumber(string $gatewayReference, string $fallback): string
    {
        $receiptNo = trim($gatewayReference) !== '' ? $gatewayReference : $fallback;
        return substr($receiptNo, 0, 30);
    }

    private function progressCode(string $registrationNumber, int $academicSessionId): string
    {
        return $this->progressCodeFor($this->db, $registrationNumber, $academicSessionId);
    }

    private function progressCodeFor(Connection $db, string $registrationNumber, int $academicSessionId): string
    {
        $sessionName = AcademicSession::find()
            ->select('acad_session_name')
            ->where(['acad_session_id' => $academicSessionId])
            ->scalar($db);

        return $registrationNumber . '-' . ($sessionName ?: $academicSessionId);
    }

    public function buildGatewayPayload(
        array $studentContext,
        float $amount,
        string $reference,
        string $description,
        string $serviceId,
        ?string $phoneNumber = null
    ): array
    {
        $config = $this->gatewayConfig();
        $student = $studentContext['student'];
        $identity = Yii::$app->user->identity;
        $clientIdNumber = $student['id_no'] ?: $student['passport_no'] ?: $identity->national_id ?: $identity->passport_no ?: $studentContext['registrationNumber'];
        $clientName = trim(($student['surname'] ?? '') . ' ' . ($student['other_names'] ?? ''));
        $clientPhone = $phoneNumber ?: ($student['primary_phone_no'] ?: $identity->primary_phone_no);
        $amountExpected = $this->formatAmount($amount);

        $payload = [
            'apiClientID' => $config['apiClientID'],
            'serviceID' => $serviceId,
            'billRefNumber' => $reference,
            'billDesc' => substr($description, 0, 100),
            'clientMSISDN' => $clientPhone,
            'clientIDNumber' => $clientIdNumber,
            'clientName' => $clientName,
            'clientEmail' => $student['primary_email'] ?: $identity->primary_email,
            'notificationURL' => $this->callbackUrl('/ecitizen/payment/notify'),
            'callBackURLOnSuccess' => $this->callbackUrl('/ecitizen/payment/invoices'),
            'pictureURL' => '',
            'currency' => $config['currency'],
            'amountExpected' => $amountExpected,
            'format' => 'iframe',
            'sendSTK' => !empty($config['sendSTK']) ? 'true' : 'false',
        ];

        $dataString = $payload['apiClientID'] . $payload['amountExpected'] . $payload['serviceID']
            . $payload['clientIDNumber'] . $payload['currency'] . $payload['billRefNumber']
            . $payload['billDesc'] . $payload['clientName'] . $config['secret'];
        $payload['secureHash'] = base64_encode(hash_hmac('sha256', $dataString, $config['apiKey']));

        return $payload;
    }

    public function validateNotificationHash(array $payload): bool
    {
        $config = $this->gatewayConfig();
        $receivedHash = $payload['secure_hash'] ?? $payload['secureHash'] ?? null;
        if (empty($receivedHash)) {
            return false;
        }

        $reference = $payload['client_invoice_ref'] ?? $payload['billRefNumber'] ?? $payload['clientInvoiceRef'] ?? null;
        $invoiceNumber = $payload['invoice_number'] ?? $payload['invoiceNumber'] ?? $payload['trans_reference'] ?? '';
        $amountPaid = $payload['amount_paid'] ?? $payload['amountPaid'] ?? $payload['amount'] ?? null;
        $paymentDate = $payload['payment_date'] ?? $payload['paymentDate'] ?? null;

        if (!$reference || !$amountPaid || !$paymentDate) {
            return false;
        }

        $dataString = $reference . $invoiceNumber . $amountPaid . $paymentDate . $config['secret'];
        $expectedHash = base64_encode(hash_hmac('sha256', $dataString, $config['apiKey']));

        return hash_equals($expectedHash, $receivedHash);
    }

    public function extractNotification(array $payload): array
    {
        return [
            'reference' => $payload['client_invoice_ref'] ?? $payload['billRefNumber'] ?? $payload['clientInvoiceRef'] ?? '',
            'gatewayReference' => $payload['invoice_number'] ?? $payload['invoiceNumber'] ?? $payload['transaction_id'] ?? $payload['trans_reference'] ?? '',
            'amount' => (float) ($payload['amount_paid'] ?? $payload['amountPaid'] ?? $payload['amount'] ?? 0),
            'paymentDate' => self::normalizePaymentDate((string) ($payload['payment_date'] ?? $payload['paymentDate'] ?? '')),
            'status' => strtoupper((string) ($payload['status'] ?? $payload['payment_status'] ?? '')),
        ];
    }

    public static function normalizePaymentDate(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        if (preg_match('/\b(\d{4}-\d{2}-\d{2})\b/', $value, $matches) === 1) {
            $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $matches[1]);
            $errors = \DateTimeImmutable::getLastErrors();
            if ($date !== false && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))) {
                return $date->format('Y-m-d');
            }
        }

        try {
            return (new \DateTimeImmutable($value))->format('Y-m-d');
        } catch (\Throwable) {
            return '';
        }
    }

    public static function notificationStatusIsPaid(string $status): bool
    {
        return in_array(strtoupper(trim($status)), ['PAID', 'SUCCESS', 'COMPLETED', 'SETTLED'], true);
    }

    public function studentContextByRegistrationNumber(string $registrationNumber): array
    {
        $student = Student::find()
            ->where(['student_number' => $registrationNumber])
            ->asArray()
            ->one();
        $programme = StudentProgCurriculum::find()
            ->where(['registration_number' => $registrationNumber])
            ->orderBy(['student_prog_curriculum_id' => SORT_DESC])
            ->asArray()
            ->one();
        $academicProgress = AcademicProgress::find()
            ->where(['student_prog_curriculum_id' => $programme['student_prog_curriculum_id'] ?? null])
            ->orderBy(['academic_progress_id' => SORT_DESC])
            ->asArray()
            ->one();

        if (!$student || !$programme || !$academicProgress) {
            throw new NotFoundHttpException('Student fee posting records could not be resolved.');
        }

        return [
            'registrationNumber' => $registrationNumber,
            'student' => $student,
            'programme' => $programme,
            'academicProgress' => $academicProgress,
        ];
    }

    private function buildReference(string $registrationNumber): string
    {
        $cleanRegistrationNumber = preg_replace('/[^A-Z0-9]/i', '', $registrationNumber);
        return substr('ECIT-' . $cleanRegistrationNumber . '-' . date('YmdHis') . '-' . random_int(100, 999), 0, 35);
    }

    private function formatAmount(float $amount): string
    {
        return number_format($amount, 2, '.', '');
    }

    public function queryPaymentStatus(string $reference): array
    {
        $config = $this->gatewayConfig();
        $apiClientId = (string) $config['apiClientID'];
        $secureHash = base64_encode(hash_hmac('sha256', $apiClientId . $reference, (string) $config['apiKey']));
        $url = $this->paymentStatusUrl($config);
        $payload = [
            'api_client_id' => $apiClientId,
            'ref_no' => $reference,
            'secure_hash' => $secureHash,
        ];

        $body = $this->requestJson($url, $payload);
        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            throw new \RuntimeException('eCitizen returned a non-JSON status response.');
        }

        return $decoded;
    }

    public function statusPayloadIsSettled(array $invoice, array $payload): bool
    {
        $status = strtolower(trim((string) ($payload['status'] ?? '')));
        if ($status !== 'settled') {
            return false;
        }

        $reference = trim((string) ($payload['client_invoice_ref'] ?? $payload['ref_no'] ?? ''));
        $expectedReference = (string) ($invoice['source_reference'] ?: $invoice['trans_reference']);
        if ($reference !== '' && $reference !== $expectedReference) {
            return false;
        }

        $paidAmount = $this->paidAmount($payload);
        if ($paidAmount === null) {
            return false;
        }

        return abs($paidAmount - round((float) $invoice['deposit_amount'], 2)) <= 0.01;
    }

    public function paidAmount(array $payload): ?float
    {
        foreach (['amount_paid', 'amountPaid', 'paid_amount', 'amount'] as $field) {
            if (isset($payload[$field]) && trim((string) $payload[$field]) !== '') {
                return round((float) $payload[$field], 2);
            }
        }

        return null;
    }

    public function paymentDate(array $payload): string
    {
        $paymentDate = self::normalizePaymentDate(
            (string) ($payload['payment_date'] ?? $payload['paymentDate'] ?? '')
        );
        if ($paymentDate === '') {
            throw new \RuntimeException('eCitizen returned an invalid payment date.');
        }

        return $paymentDate;
    }

    public function gatewayReference(array $payload, string $fallback): string
    {
        return (string) ($payload['invoice_number'] ?? $payload['invoiceNumber'] ?? $payload['transaction_id'] ?? $payload['trans_reference'] ?? $fallback);
    }

    private function paymentTypeDescription(array $slip): string
    {
        $paymentTypeId = $slip['payment_type_id'] ?? $slip['deposit_type'] ?? null;
        if ($paymentTypeId !== null && $paymentTypeId !== '') {
            $description = (new Query())
                ->select('payment_desc')
                ->from('smisportal.fss_payment_types')
                ->where(['payment_type_id' => $paymentTypeId])
                ->scalar($this->db);

            if ($description !== false && trim((string) $description) !== '') {
                return (string) $description;
            }
        }

        return 'eCitizen student fee payment';
    }

    private function paymentStatusUrl(array $config): string
    {
        if (!empty($config['statusUrl'])) {
            return (string) $config['statusUrl'];
        }

        $parts = parse_url((string) $config['url']);
        if (empty($parts['scheme']) || empty($parts['host'])) {
            return 'https://payments.ecitizen.go.ke/api/invoice/payment/status';
        }

        return $parts['scheme'] . '://' . $parts['host'] . '/api/invoice/payment/status';
    }

    private function callbackUrl(string $route): string
    {
        $config = $this->gatewayConfig();
        $baseUrl = rtrim((string) $config['callbackBaseUrl'], '/');
        return $baseUrl . Url::to([$route]);
    }

    private function assertTrustedHttpsUrl(string $url, array $allowedHosts, string $label): void
    {
        $parts = parse_url($url);
        $host = strtolower((string) ($parts['host'] ?? ''));
        if (($parts['scheme'] ?? null) !== 'https' || $host === '' || isset($parts['user']) || isset($parts['pass'])) {
            throw new InvalidConfigException("{$label} must be an HTTPS URL without embedded credentials.");
        }

        if ($allowedHosts !== [] && !in_array($host, $allowedHosts, true)) {
            throw new InvalidConfigException("{$label} uses an untrusted host.");
        }
    }

    private function requestJson(string $url, array $payload): string
    {
        $queryUrl = $url . (str_contains($url, '?') ? '&' : '?') . http_build_query($payload);
        if (function_exists('curl_init')) {
            $ch = curl_init($queryUrl);
            curl_setopt_array($ch, [
                CURLOPT_HTTPGET => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 45,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_HTTPHEADER => ['Accept: application/json'],
            ]);
            $caBundlePath = $this->caBundlePath();
            if ($caBundlePath !== null) {
                curl_setopt($ch, CURLOPT_CAINFO, $caBundlePath);
            }

            $body = curl_exec($ch);
            if ($body === false) {
                $message = curl_error($ch);
                curl_close($ch);
                throw new \RuntimeException('Unable to connect to eCitizen status endpoint: ' . $message);
            }

            $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($statusCode < 200 || $statusCode >= 300) {
                throw new \RuntimeException('eCitizen status endpoint returned HTTP ' . $statusCode . '.');
            }
            if (strlen((string) $body) > 1048576) {
                throw new \RuntimeException('eCitizen status endpoint returned an oversized response.');
            }

            return (string) $body;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Accept: application/json\r\n",
                'ignore_errors' => true,
                'follow_location' => 0,
                'max_redirects' => 0,
                'timeout' => 45,
            ],
            'ssl' => array_filter([
                'cafile' => $this->caBundlePath(),
                'verify_peer' => true,
                'verify_peer_name' => true,
            ]),
        ]);
        $body = @file_get_contents($queryUrl, false, $context);
        if ($body === false) {
            throw new \RuntimeException('Unable to connect to eCitizen status endpoint.');
        }
        $statusLine = (string) (($http_response_header ?? [])[0] ?? '');
        if (!preg_match('/\s2\d{2}\s/', $statusLine)) {
            throw new \RuntimeException('eCitizen status endpoint returned an unsuccessful response.');
        }
        if (strlen($body) > 1048576) {
            throw new \RuntimeException('eCitizen status endpoint returned an oversized response.');
        }

        return $body;
    }

    private function caBundlePath(): ?string
    {
        $configuredPath = $this->params()['caBundlePath'] ?? null;
        $candidates = array_filter([
            $configuredPath,
            'C:/Program Files/Git/mingw64/etc/ssl/certs/ca-bundle.crt',
            'C:/Program Files/Git/usr/ssl/certs/ca-bundle.crt',
            ini_get('curl.cainfo') ?: null,
            ini_get('openssl.cafile') ?: null,
        ]);

        foreach ($candidates as $path) {
            if (is_string($path) && is_file($path)) {
                return $path;
            }
        }

        return null;
    }
}
