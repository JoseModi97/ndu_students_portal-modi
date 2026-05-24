<?php

namespace app\services;

use app\models\StudentProgCurriculum;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class EcitizenPaymentService
{
    public const PAYMENT_MODE_ID = 12;

    private Connection $db;

    public function __construct()
    {
        $this->db = Yii::$app->smisDb;
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
        $student = $this->db->createCommand(
            'select * from smis.sm_student where student_number = :registration_number',
            [':registration_number' => $registrationNumber]
        )->queryOne();

        if (!$student) {
            throw new NotFoundHttpException('The logged in student was not found in SMIS.');
        }

        $programme = $this->db->createCommand(
            'select * from smis.sm_student_programme_curriculum where registration_number = :registration_number order by student_prog_curriculum_id desc limit 1',
            [':registration_number' => $registrationNumber]
        )->queryOne();

        if (!$programme) {
            throw new NotFoundHttpException('The logged in student programme curriculum was not found in SMIS.');
        }

        $academicProgress = $this->db->createCommand(
            'select * from smis.sm_academic_progress where student_prog_curriculum_id = :student_prog_curriculum_id order by current_status desc, academic_progress_id desc limit 1',
            [':student_prog_curriculum_id' => $programme['student_prog_curriculum_id']]
        )->queryOne();

        if (!$academicProgress) {
            throw new NotFoundHttpException('The logged in student academic progress was not found in SMIS.');
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
        return (bool) $this->db->createCommand(
            'select 1 from smis.fss_payment_modes where payment_mode_id = :payment_mode_id',
            [':payment_mode_id' => self::PAYMENT_MODE_ID]
        )->queryScalar();
    }

    public function paymentTypes(): array
    {
        return $this->db->createCommand(
            'select payment_type_id, payment_desc from smis.fss_payment_types order by payment_type_id'
        )->queryAll();
    }

    public function bankAccounts(): array
    {
        return $this->db->createCommand(
            "select ba.brank_account_id, ba.account_no, ba.account_details, ba.branch_code,
                    bb.branch_name, bb.bank_code, b.brank_id, b.bank_name
               from smis.fss_bank_accounts ba
               left join smis.fss_bank_branches bb on bb.branch_code = ba.branch_code
               left join smis.fss_banks b on b.bank_code = bb.bank_code
              order by b.bank_name, ba.account_no"
        )->queryAll();
    }

    public function findBankAccount(int $bankAccountId): ?array
    {
        $account = $this->db->createCommand(
            "select ba.brank_account_id, ba.account_no, ba.account_details, ba.branch_code,
                    bb.branch_name, bb.bank_code, b.brank_id, b.bank_name
               from smis.fss_bank_accounts ba
               left join smis.fss_bank_branches bb on bb.branch_code = ba.branch_code
               left join smis.fss_banks b on b.bank_code = bb.bank_code
              where ba.brank_account_id = :bank_account_id",
            [':bank_account_id' => $bankAccountId]
        )->queryOne();

        return $account ?: null;
    }

    public function recentRequests(string $registrationNumber): array
    {
        return $this->db->createCommand(
            "select trans_id, deposit_date, deposit_amount, post_status, post_comment, trans_reference, source_reference
               from smis.fss_banking_slips
              where reg_number = :registration_number
                and pay_mode = :payment_mode_id
              order by trans_id desc
              limit 10",
            [
                ':registration_number' => $registrationNumber,
                ':payment_mode_id' => self::PAYMENT_MODE_ID,
            ]
        )->queryAll();
    }

    public function invoiceRequests(string $registrationNumber): array
    {
        return $this->db->createCommand(
            "select trans_id, deposit_date, deposit_amount, reg_number, registration_number, post_status,
                    post_comment, receipt_no, pay_mode, trans_reference, source_reference, last_update
               from smis.fss_banking_slips
              where reg_number = :registration_number
                and pay_mode = :payment_mode_id
              order by trans_id desc",
            [
                ':registration_number' => $registrationNumber,
                ':payment_mode_id' => self::PAYMENT_MODE_ID,
            ]
        )->queryAll();
    }

    public function findInvoiceRequest(int $transId, string $registrationNumber): ?array
    {
        $request = $this->db->createCommand(
            "select *
               from smis.fss_banking_slips
              where trans_id = :trans_id
                and reg_number = :registration_number
                and pay_mode = :payment_mode_id",
            [
                ':trans_id' => $transId,
                ':registration_number' => $registrationNumber,
                ':payment_mode_id' => self::PAYMENT_MODE_ID,
            ]
        )->queryOne();

        return $request ?: null;
    }

    public function workflowReport(): array
    {
        $identity = Yii::$app->user->identity;
        $admRefNo = $identity->adm_refno;
        $portalDb = Yii::$app->db;

        $portalAdmittedStudent = $portalDb->createCommand(
            "select adm_refno, surname, other_names, primary_email, primary_phone_no,
                    national_id, passport_no, admission_status
               from smisportal.sm_admitted_student
              where adm_refno = :adm_refno",
            [':adm_refno' => $admRefNo]
        )->queryOne() ?: [];

        $portalProgramme = $portalDb->createCommand(
            "select student_prog_curriculum_id, student_id, registration_number, adm_refno, status_id
               from smisportal.sm_student_programme_curriculum
              where adm_refno = :adm_refno
              order by student_prog_curriculum_id desc
              limit 1",
            [':adm_refno' => $admRefNo]
        )->queryOne() ?: [];

        $registrationNumber = (string) ($portalProgramme['registration_number'] ?? '');
        $portalStudent = [];
        if ($registrationNumber !== '') {
            $portalStudent = $portalDb->createCommand(
                "select student_id, student_number, surname, other_names, primary_email, primary_phone_no
                   from smisportal.sm_student
                  where student_number = :registration_number",
                [':registration_number' => $registrationNumber]
            )->queryOne() ?: [];
        }

        $smisStudent = [];
        $smisProgramme = [];
        $academicProgress = [];
        $workflowRows = [];
        if ($registrationNumber !== '') {
            $smisStudent = $this->db->createCommand(
                "select student_id, student_number, surname, other_names, primary_email, primary_phone_no
                   from smis.sm_student
                  where student_number = :registration_number",
                [':registration_number' => $registrationNumber]
            )->queryOne() ?: [];

            $smisProgramme = $this->db->createCommand(
                "select student_prog_curriculum_id, student_id, registration_number, prog_curriculum_id,
                        student_category_id, adm_refno, status_id
                   from smis.sm_student_programme_curriculum
                  where registration_number = :registration_number
                  order by student_prog_curriculum_id desc
                  limit 1",
                [':registration_number' => $registrationNumber]
            )->queryOne() ?: [];

            if (!empty($smisProgramme['student_prog_curriculum_id'])) {
                $academicProgress = $this->db->createCommand(
                    "select academic_progress_id, acad_session_id, academic_level_id,
                            student_prog_curriculum_id, progress_status_id, current_status
                       from smis.sm_academic_progress
                      where student_prog_curriculum_id = :student_prog_curriculum_id
                      order by current_status desc, academic_progress_id desc
                      limit 1",
                    [':student_prog_curriculum_id' => $smisProgramme['student_prog_curriculum_id']]
                )->queryOne() ?: [];
            }

            $workflowRows = $this->db->createCommand(
                "select
                    bs.trans_id,
                    bs.reg_number,
                    bs.deposit_date,
                    bs.deposit_amount,
                    bs.post_status,
                    bs.post_comment,
                    bs.receipt_no,
                    bs.source_reference,
                    bs.trans_reference,
                    bs.last_update,
                    fp.fee_paymt_id,
                    fp.trans_date as fee_payment_date,
                    fp.trans_amount as fee_payment_amount,
                    fp.student_prog_curriculum_id,
                    ft.academic_progress_id,
                    ft.trans_type,
                    ft.trans_amount as fee_transaction_amount,
                    ft.trans_desc,
                    ft.sync_status
                from smis.fss_banking_slips bs
                left join smis.fss_fee_payments fp
                    on fp.trans_id = bs.trans_id
                left join smis.fss_fee_transactions ft
                    on ft.trans_id = bs.trans_id
                where bs.pay_mode = :payment_mode_id
                  and bs.reg_number = :registration_number
                order by bs.trans_id desc",
                [
                    ':payment_mode_id' => self::PAYMENT_MODE_ID,
                    ':registration_number' => $registrationNumber,
                ]
            )->queryAll();
        }

        $paymentMode = $this->db->createCommand(
            "select payment_mode_id, mode_code, description, mode_flag
               from smis.fss_payment_modes
              where payment_mode_id = :payment_mode_id",
            [':payment_mode_id' => self::PAYMENT_MODE_ID]
        )->queryOne() ?: [];

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
            'smis' => [
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
            'smisDb' => [
                'eCitizen payment mode' => "SELECT payment_mode_id, mode_code, description, mode_flag\nFROM smis.fss_payment_modes\nWHERE payment_mode_id = 12;",
                'SMIS student' => "SELECT student_id, student_number, surname, other_names, primary_email, primary_phone_no\nFROM smis.sm_student\nWHERE student_number = {$registrationValue};",
                'SMIS programme curriculum' => "SELECT student_prog_curriculum_id, student_id, registration_number, prog_curriculum_id, student_category_id, adm_refno, status_id\nFROM smis.sm_student_programme_curriculum\nWHERE registration_number = {$registrationValue}\nORDER BY student_prog_curriculum_id DESC;",
                'Academic progress' => "SELECT sap.academic_progress_id, sap.acad_session_id, sap.academic_level_id, sap.student_prog_curriculum_id, sap.progress_status_id, sap.current_status\nFROM smis.sm_academic_progress sap\nINNER JOIN smis.sm_student_programme_curriculum spc\n    ON spc.student_prog_curriculum_id = sap.student_prog_curriculum_id\nWHERE spc.registration_number = {$registrationValue}\nORDER BY sap.current_status DESC, sap.academic_progress_id DESC;",
                'Settlement bank accounts' => "SELECT ba.brank_account_id, ba.account_no, ba.account_details, ba.branch_code, bb.branch_name, bb.bank_code, b.brank_id, b.bank_name\nFROM smis.fss_bank_accounts ba\nLEFT JOIN smis.fss_bank_branches bb ON bb.branch_code = ba.branch_code\nLEFT JOIN smis.fss_banks b ON b.bank_code = bb.bank_code\nORDER BY b.bank_name, ba.account_no;",
                'Full eCitizen workflow trace' => "SELECT bs.trans_id, bs.reg_number, bs.deposit_date, bs.deposit_amount, bs.post_status, bs.post_comment, bs.receipt_no, bs.source_reference, bs.trans_reference, fp.fee_paymt_id, fp.trans_date AS fee_payment_date, fp.trans_amount AS fee_payment_amount, fp.student_prog_curriculum_id, ft.academic_progress_id, ft.trans_type, ft.trans_amount AS fee_transaction_amount, ft.trans_desc, ft.sync_status\nFROM smis.fss_banking_slips bs\nLEFT JOIN smis.fss_fee_payments fp ON fp.trans_id = bs.trans_id\nLEFT JOIN smis.fss_fee_transactions ft ON ft.trans_id = bs.trans_id\nWHERE bs.pay_mode = 12\n  AND bs.reg_number = {$registrationValue}\nORDER BY bs.trans_id DESC;",
            ],
        ];
    }

    public function createPendingBankingSlip(
        array $studentContext,
        array $bankAccount,
        float $amount,
        int $paymentTypeId,
        string $narration
    ): array {
        $reference = $this->buildReference($studentContext['registrationNumber']);
        $student = $studentContext['student'];
        $identity = Yii::$app->user->identity;
        $today = date('Y-m-d');

        $transaction = $this->db->beginTransaction();
        try {
            $existing = $this->db->createCommand(
                'select trans_id from smis.fss_banking_slips where source_reference = :source_reference',
                [':source_reference' => $reference]
            )->queryScalar();

            if ($existing) {
                throw new ServerErrorHttpException('A payment request with the same reference already exists.');
            }

            $transId = $this->db->createCommand(
                "insert into smis.fss_banking_slips
                    (deposit_date, deposit_type, payment_type_id, deposit_amount, reg_number, registration_number,
                     other_names, post_status, post_comment, account_no, receipt_no, process_date, batch_no,
                     pay_mode, trans_reference, branch_code, last_update, user_id, drawer_name, source_reference,
                     value_date, bank_id, bank_number)
                 values
                    (:deposit_date, :deposit_type, :payment_type_id, :deposit_amount, :reg_number, :registration_number,
                     :other_names, 'NOT POSTED', :post_comment, :account_no, :receipt_no, :process_date, :batch_no,
                     :pay_mode, :trans_reference, :branch_code, now(), :user_id, :drawer_name, :source_reference,
                     now(), :bank_id, :bank_number)
                 returning trans_id",
                [
                    ':deposit_date' => $today,
                    ':deposit_type' => $paymentTypeId,
                    ':payment_type_id' => $paymentTypeId,
                    ':deposit_amount' => $amount,
                    ':reg_number' => $studentContext['registrationNumber'],
                    ':registration_number' => $studentContext['registrationNumber'],
                    ':other_names' => trim(($student['surname'] ?? '') . ' ' . ($student['other_names'] ?? '')),
                    ':post_comment' => substr($narration, 0, 20),
                    ':account_no' => $bankAccount['account_no'] ?? null,
                    ':receipt_no' => random_int(234567, 2147483647),
                    ':process_date' => $today,
                    ':batch_no' => substr($reference, 0, 25),
                    ':pay_mode' => self::PAYMENT_MODE_ID,
                    ':trans_reference' => $reference,
                    ':branch_code' => $bankAccount['branch_code'] ?? null,
                    ':user_id' => $identity->adm_refno,
                    ':drawer_name' => 'eCitizen',
                    ':source_reference' => $reference,
                    ':bank_id' => $bankAccount['brank_id'] ?? null,
                    ':bank_number' => $bankAccount['bank_code'] ?? null,
                ]
            )->queryScalar();

            $transaction->commit();
            return ['trans_id' => (int) $transId, 'reference' => $reference];
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function postPaidBankingSlip(string $reference, float $amount, string $paymentDate, string $gatewayReference): int
    {
        $slip = $this->db->createCommand(
            'select * from smis.fss_banking_slips where source_reference = :reference or trans_reference = :reference order by trans_id desc limit 1',
            [':reference' => $reference]
        )->queryOne();

        if (!$slip) {
            throw new NotFoundHttpException('Payment request not found.');
        }

        if ($slip['post_status'] === 'POSTED') {
            return (int) $slip['trans_id'];
        }

        if ((float) $slip['deposit_amount'] !== $amount) {
            throw new ServerErrorHttpException('The paid amount does not match the requested amount.');
        }

        $studentContext = $this->studentContextByRegistrationNumber($slip['reg_number']);
        $transaction = $this->db->beginTransaction();

        try {
            $this->db->createCommand()->update('smis.fss_banking_slips', [
                'deposit_date' => $paymentDate,
                'process_date' => date('Y-m-d'),
                'post_comment' => substr($gatewayReference ?: 'eCitizen', 0, 20),
                'trans_reference' => substr($gatewayReference ?: $reference, 0, 50),
                'last_update' => new \yii\db\Expression('now()'),
            ], ['trans_id' => $slip['trans_id']])->execute();

            $feeExists = $this->db->createCommand(
                'select 1 from smis.fss_fee_payments where trans_id = :trans_id',
                [':trans_id' => $slip['trans_id']]
            )->queryScalar();

            if (!$feeExists) {
                $this->db->createCommand()->insert('smis.fss_fee_payments', [
                    'receipt_no' => (string) $slip['receipt_no'],
                    'trans_date' => $paymentDate,
                    'trans_amount' => $amount,
                    'pay_mode' => self::PAYMENT_MODE_ID,
                    'collection_point_id' => $slip['bank_id'],
                    'user_id' => (string) ($slip['user_id'] ?? ''),
                    'entry_date' => date('Y-m-d'),
                    'trans_id' => $slip['trans_id'],
                    'academic_session' => $studentContext['academicProgress']['acad_session_id'],
                    'authorized_by' => (string) ($slip['user_id'] ?? ''),
                    'authorized_date' => date('Y-m-d'),
                    'receipt_status' => '',
                    'exchange_rate' => 1,
                    'student_prog_curriculum_id' => $studentContext['programme']['student_prog_curriculum_id'],
                ])->execute();

                $this->db->createCommand()->insert('smis.fss_fee_transactions', [
                    'trans_id' => $slip['trans_id'],
                    'academic_progress_id' => $studentContext['academicProgress']['academic_progress_id'],
                    'trans_date' => $paymentDate,
                    'trans_type' => 'CR',
                    'trans_amount' => $amount,
                    'trans_desc' => substr($gatewayReference ?: 'eCitizen', 0, 150),
                    'user_id' => (string) ($slip['user_id'] ?? ''),
                    'receipt_status' => '',
                    'exchange_rate' => 1,
                    'progress_code' => '',
                    'sync_status' => false,
                    'fee_trans_id' => $slip['trans_id'],
                ])->execute();
            }

            $this->db->createCommand()->update('smis.fss_banking_slips', [
                'post_status' => 'POSTED',
                'last_update' => new \yii\db\Expression('now()'),
            ], ['trans_id' => $slip['trans_id']])->execute();

            $transaction->commit();
            return (int) $slip['trans_id'];
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function gatewayConfig(): array
    {
        $config = Yii::$app->params['ecitizen'] ?? [];
        foreach (['apiClientID', 'apiKey', 'secret', 'serviceID', 'url', 'currency'] as $key) {
            if (empty($config[$key])) {
                throw new InvalidConfigException("Missing eCitizen configuration value: {$key}.");
            }
        }
        return $config;
    }

    public function buildGatewayPayload(array $studentContext, float $amount, string $reference, string $description): array
    {
        $config = $this->gatewayConfig();
        $student = $studentContext['student'];
        $identity = Yii::$app->user->identity;
        $clientIdNumber = $student['id_no'] ?: $student['passport_no'] ?: $identity->national_id ?: $identity->passport_no ?: $studentContext['registrationNumber'];
        $clientName = trim(($student['surname'] ?? '') . ' ' . ($student['other_names'] ?? ''));
        $amountExpected = $this->formatAmount($amount);

        $payload = [
            'apiClientID' => $config['apiClientID'],
            'serviceID' => $config['serviceID'],
            'billRefNumber' => $reference,
            'billDesc' => substr($description, 0, 100),
            'clientMSISDN' => $student['primary_phone_no'] ?: $identity->primary_phone_no,
            'clientIDNumber' => $clientIdNumber,
            'clientName' => $clientName,
            'clientEmail' => $student['primary_email'] ?: $identity->primary_email,
            'notificationURL' => \yii\helpers\Url::to(['/ecitizen-payment/notify'], true),
            'callBackURLOnSuccess' => \yii\helpers\Url::to(['/ecitizen-payment/success', 'reference' => $reference], true),
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
            'paymentDate' => date('Y-m-d', strtotime($payload['payment_date'] ?? $payload['paymentDate'] ?? 'now')),
            'status' => strtoupper((string) ($payload['status'] ?? $payload['payment_status'] ?? '')),
        ];
    }

    public function studentContextByRegistrationNumber(string $registrationNumber): array
    {
        $student = $this->db->createCommand(
            'select * from smis.sm_student where student_number = :registration_number',
            [':registration_number' => $registrationNumber]
        )->queryOne();
        $programme = $this->db->createCommand(
            'select * from smis.sm_student_programme_curriculum where registration_number = :registration_number order by student_prog_curriculum_id desc limit 1',
            [':registration_number' => $registrationNumber]
        )->queryOne();
        $academicProgress = $this->db->createCommand(
            'select * from smis.sm_academic_progress where student_prog_curriculum_id = :student_prog_curriculum_id order by current_status desc, academic_progress_id desc limit 1',
            [':student_prog_curriculum_id' => $programme['student_prog_curriculum_id'] ?? null]
        )->queryOne();

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
        return rtrim(rtrim(number_format($amount, 2, '.', ''), '0'), '.');
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
        return date('Y-m-d', strtotime($payload['payment_date'] ?? $payload['paymentDate'] ?? 'now'));
    }

    public function gatewayReference(array $payload, string $fallback): string
    {
        return (string) ($payload['invoice_number'] ?? $payload['invoiceNumber'] ?? $payload['transaction_id'] ?? $payload['trans_reference'] ?? $fallback);
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

    private function requestJson(string $url, array $payload): string
    {
        $queryUrl = $url . (str_contains($url, '?') ? '&' : '?') . http_build_query($payload);
        if (function_exists('curl_init')) {
            $ch = curl_init($queryUrl);
            curl_setopt_array($ch, [
                CURLOPT_HTTPGET => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 45,
                CURLOPT_SSL_VERIFYPEER => true,
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
            if ($statusCode >= 400) {
                throw new \RuntimeException('eCitizen status endpoint returned HTTP ' . $statusCode . '.');
            }

            return (string) $body;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Accept: application/json\r\n",
                'ignore_errors' => true,
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

        return $body;
    }

    private function caBundlePath(): ?string
    {
        $configuredPath = Yii::$app->params['ecitizen']['caBundlePath'] ?? null;
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
