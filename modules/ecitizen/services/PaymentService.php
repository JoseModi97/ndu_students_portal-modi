<?php

namespace app\modules\ecitizen\services;

use app\models\StudentProgCurriculum;
use app\modules\ecitizen\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class PaymentService
{
    public const PAYMENT_MODE_ID = 12;
    public const SYNC_PENDING = 0;
    public const SYNC_DONE = 1;
    public const SYNC_FAILED = 2;
    private const PORTAL_ECITIZEN_TRANS_ID_OFFSET = 900000000000;

    private Connection $db;

    public function __construct()
    {
        $this->db = $this->module()->connection($this->module()->smisDb);
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
            'select * from smis.sm_academic_progress where student_prog_curriculum_id = :student_prog_curriculum_id order by academic_progress_id desc limit 1',
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
        $serviceCodes = array_keys($this->serviceCatalog());
        $placeholders = [];
        $params = [];
        foreach ($serviceCodes as $index => $paymentTypeId) {
            $placeholder = ':payment_type_' . $index;
            $placeholders[] = $placeholder;
            $params[$placeholder] = $paymentTypeId;
        }

        return $this->db->createCommand(
            'select payment_type_id, payment_desc
               from smis.fss_payment_types
              where payment_type_id in (' . implode(', ', $placeholders) . ')
              order by array_position(
                  array[' . implode(', ', $serviceCodes) . ']::bigint[],
                  payment_type_id
              )',
            $params
        )->queryAll();
    }

    public function serviceIdForPaymentType(int $paymentTypeId): string
    {
        $catalog = $this->serviceCatalog();
        if (!isset($catalog[$paymentTypeId])) {
            throw new InvalidConfigException('The selected payment type has no service ID in NDU SERVICE CODES.xlsx.');
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
        $bankingSlipInvoices = $this->db->createCommand(
            "select bs.trans_id, bs.deposit_date, bs.deposit_amount, bs.reg_number, bs.registration_number, bs.post_status,
                    bs.post_comment, bs.receipt_no, bs.pay_mode, bs.trans_reference, bs.source_reference, bs.last_update,
                    case when exists (
                        select 1
                          from smis.fss_fee_payments fp
                         where fp.trans_id = bs.trans_id
                    ) then 1 else 0 end as has_fee_payment
               from smis.fss_banking_slips bs
              where bs.reg_number = :registration_number
                and bs.pay_mode = :payment_mode_id
              order by bs.trans_id desc",
            [
                ':registration_number' => $registrationNumber,
                ':payment_mode_id' => self::PAYMENT_MODE_ID,
            ]
        )->queryAll();

        return array_merge($bankingSlipInvoices, $this->pendingInvoiceRequests($registrationNumber));
    }

    public function findInvoiceRequest(int $transId, string $registrationNumber): ?array
    {
        $request = $this->db->createCommand(
            "select bs.*,
                    case when exists (
                        select 1
                          from smis.fss_fee_payments fp
                         where fp.trans_id = bs.trans_id
                    ) then 1 else 0 end as has_fee_payment
               from smis.fss_banking_slips bs
              where bs.trans_id = :trans_id
                and bs.reg_number = :registration_number
                and bs.pay_mode = :payment_mode_id",
            [
                ':trans_id' => $transId,
                ':registration_number' => $registrationNumber,
                ':payment_mode_id' => self::PAYMENT_MODE_ID,
            ]
        )->queryOne();

        if ($request) {
            return $request;
        }

        $pending = $this->portalDb()->createCommand(
            "select payment_id as trans_id,
                    trans_date::date as deposit_date,
                    \"amountExpected\" as deposit_amount,
                    registration_number as reg_number,
                    registration_number,
                    case when status = 'Paid' then 'Credited' else status end as post_status,
                    \"billDesc\" as post_comment,
                    null as receipt_no,
                    :payment_mode_id as pay_mode,
                    \"billRefNumber\" as trans_reference,
                    \"billRefNumber\" as source_reference,
                    response,
                    trans_date as last_update,
                    case when exists (
                        select 1
                          from smisportal.fss_fee_transactions ft
                         where ft.trans_id = (:portal_trans_id_offset + e.payment_id)
                           and ft.trans_type = 'CR'
                    ) then 1 else 0 end as has_fee_payment
               from smisportal.ecitizen
               as e
              where payment_id = :payment_id
                and registration_number = :registration_number
                and coalesce(status, 'Pending') in ('Pending', 'Paid', 'Credited', 'Settled')",
            [
                ':payment_id' => $transId,
                ':registration_number' => $registrationNumber,
                ':payment_mode_id' => self::PAYMENT_MODE_ID,
                ':portal_trans_id_offset' => self::PORTAL_ECITIZEN_TRANS_ID_OFFSET,
            ]
        )->queryOne();

        return $pending ?: null;
    }

    private function pendingInvoiceRequests(string $registrationNumber): array
    {
        $requests = $this->portalDb()->createCommand(
            "select payment_id as trans_id,
                    trans_date::date as deposit_date,
                    \"amountExpected\" as deposit_amount,
                    registration_number as reg_number,
                    registration_number,
                    case when status = 'Paid' then 'Credited' else status end as post_status,
                    \"billDesc\" as post_comment,
                    null as receipt_no,
                    :payment_mode_id as pay_mode,
                    \"billRefNumber\" as trans_reference,
                    \"billRefNumber\" as source_reference,
                    trans_date as last_update,
                    case when exists (
                        select 1
                          from smisportal.fss_fee_transactions ft
                         where ft.trans_id = (:portal_trans_id_offset + e.payment_id)
                           and ft.trans_type = 'CR'
                    ) then 1 else 0 end as has_fee_payment
               from smisportal.ecitizen e
              where registration_number = :registration_number
                and coalesce(status, 'Pending') in ('Pending', 'Paid', 'Credited', 'Settled')
              order by payment_id desc",
            [
                ':registration_number' => $registrationNumber,
                ':payment_mode_id' => self::PAYMENT_MODE_ID,
                ':portal_trans_id_offset' => self::PORTAL_ECITIZEN_TRANS_ID_OFFSET,
            ]
        )->queryAll();

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

        $existingReferences = (new \yii\db\Query())
            ->select('source_reference')
            ->from('smis.fss_banking_slips')
            ->where(['source_reference' => $references])
            ->column($this->db);
        $existingReferences = array_flip(array_map('strval', $existingReferences));

        return array_values(array_filter($requests, static function (array $request) use ($existingReferences): bool {
            return !isset($existingReferences[(string) ($request['source_reference'] ?? '')]);
        }));
    }

    public function workflowReport(): array
    {
        $identity = Yii::$app->user->identity;
        $admRefNo = $identity->adm_refno;
        $portalDb = $this->portalDb();

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
                      order by academic_progress_id desc
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
            $existing = $portalDb->createCommand(
                'select payment_id from smisportal.ecitizen where "billRefNumber" = :source_reference',
                [':source_reference' => $reference]
            )->queryScalar();

            if ($existing) {
                throw new ServerErrorHttpException('A payment request with the same reference already exists.');
            }

            $portalDb->createCommand("set local smisportal.ecitizen_app_write = '1'")->execute();
            $paymentId = $portalDb->createCommand(
                'insert into smisportal.ecitizen
                    ("apiClientID", "billDesc", "billRefNumber", currency, "serviceID", "clientMSISDN",
                     "clientName", "clientIDNumber", "clientEmail", "callBackURLOnSuccess", "pictureURL",
                     "notificationURL", "amountExpected", registration_number, response, status)
                 values
                    (:api_client_id, :bill_desc, :bill_ref_number, :currency, :service_id, :client_msisdn,
                     :client_name, :client_id_number, :client_email, :callback_url, :picture_url,
                     :notification_url, :amount_expected, :registration_number, :response, :status)
                 returning payment_id',
                [
                    ':api_client_id' => $config['apiClientID'],
                    ':bill_desc' => substr($narration, 0, 100),
                    ':bill_ref_number' => $reference,
                    ':currency' => $config['currency'],
                    ':service_id' => $serviceId,
                    ':client_msisdn' => $student['primary_phone_no'] ?: ($identity->primary_phone_no ?? null),
                    ':client_name' => $clientName,
                    ':client_id_number' => $student['id_no'] ?: $student['passport_no'] ?: ($identity->national_id ?? null) ?: ($identity->passport_no ?? null) ?: $studentContext['registrationNumber'],
                    ':client_email' => $student['primary_email'] ?: ($identity->primary_email ?? null),
                    ':callback_url' => \yii\helpers\Url::to(['/ecitizen/payment/invoices'], true),
                    ':picture_url' => '',
                    ':notification_url' => \yii\helpers\Url::to(['/ecitizen/payment/notify'], true),
                    ':amount_expected' => $amount,
                    ':registration_number' => $studentContext['registrationNumber'],
                    ':response' => json_encode($metadata),
                    ':status' => 'Pending',
                ]
            )->queryScalar();

            $transaction->commit();
            return ['trans_id' => (int) $paymentId, 'reference' => $reference];
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
            $request = $portalDb->createCommand(
                'select *
                   from smisportal.ecitizen
                  where "billRefNumber" = :reference
                  order by payment_id desc
                  limit 1',
                [':reference' => $reference]
            )->queryOne();

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
                $transaction->commit();

                return [
                    'payment_id' => (int) $request['payment_id'],
                    'reference' => $reference,
                ];
            }

            $portalDb->createCommand("set local smisportal.ecitizen_app_write = '1'")->execute();
            $portalDb->createCommand()->update('smisportal.ecitizen', [
                'status' => 'Credited',
                'paid_amount' => $amount,
                'payment_date' => $paymentDate,
                'gateway_reference' => substr($gatewayReference ?: $reference, 0, 100),
                'response' => json_encode($metadata),
                'sync_status' => self::SYNC_PENDING,
                'sync_error' => null,
                'last_synced_at' => null,
            ], ['payment_id' => $request['payment_id']])->execute();

            $this->creditPortalFeeStatement($portalDb, $request, $amount, $paymentDate, $gatewayReference, $metadata);

            $transaction->commit();

            return [
                'payment_id' => (int) $request['payment_id'],
                'reference' => $reference,
            ];
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function pendingPaidRequestsForSync(int $limit = 50): array
    {
        return $this->portalDb()->createCommand(
            'select *
               from smisportal.ecitizen
              where coalesce(status, :pending_status) in (:paid_status, :credited_status)
                and sync_status = :sync_status
              order by payment_id asc
              limit ' . (int) $limit,
            [
                ':pending_status' => 'Pending',
                ':paid_status' => 'Paid',
                ':credited_status' => 'Credited',
                ':sync_status' => self::SYNC_PENDING,
            ]
        )->queryAll();
    }

    public function markSyncFailed(string $reference, string $message): void
    {
        $portalDb = $this->portalDb();
        $transaction = $portalDb->beginTransaction();
        try {
            $portalDb->createCommand("set local smisportal.ecitizen_app_write = '1'")->execute();
            $portalDb->createCommand()->update('smisportal.ecitizen', [
                'sync_status' => self::SYNC_FAILED,
                'sync_error' => substr($message, 0, 1000),
            ], ['billRefNumber' => $reference])->execute();
            $transaction->commit();
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            Yii::warning('Unable to mark eCitizen sync failed for ' . $reference . ': ' . $exception->getMessage(), 'ecitizen.payment');
        }
    }

    public function postPaidBankingSlip(string $reference, float $amount, string $paymentDate, string $gatewayReference): int
    {
        $this->assertConsoleSmisWriteContext();

        $slip = $this->db->createCommand(
            'select * from smis.fss_banking_slips where source_reference = :reference or trans_reference = :reference order by trans_id desc limit 1',
            [':reference' => $reference]
        )->queryOne();

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

            $this->queuePaidRequestForSync($reference, $amount, $paymentDate, $gatewayReference);
            $pendingRequest = $this->pendingRequestByReference($reference) ?: $pendingRequest;
        }

        $transaction = $this->db->beginTransaction();

        try {
            $this->lockPosting($this->db, $reference);
            if (!$slip) {
                $slip = $this->createSettledBankingSlip($pendingRequest, $paymentDate, $gatewayReference);
            }

            $paymentDescription = $this->paymentTypeDescription($slip);
            $this->db->createCommand()->update('smis.fss_banking_slips', [
                'deposit_date' => $paymentDate,
                'process_date' => date('Y-m-d'),
                'post_status' => 'POSTED',
                'post_comment' => substr($paymentDescription, 0, 20),
                'trans_reference' => substr($gatewayReference ?: $reference, 0, 50),
                'last_update' => new \yii\db\Expression('now()'),
            ], ['trans_id' => $slip['trans_id']])->execute();

            $slip = $this->db->createCommand(
                'select * from smis.fss_banking_slips where trans_id = :trans_id',
                [':trans_id' => $slip['trans_id']]
            )->queryOne();

            $this->ensureFeeTransactionDescription($slip, $amount, $paymentDate, $paymentDescription);
            $this->ensureSmisFeePayment($slip, $amount, $paymentDate);

            $transaction->commit();
            $this->markPendingRequestSettled($reference, $gatewayReference, (int) $slip['trans_id']);
            return (int) $slip['trans_id'];
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function gatewayConfig(): array
    {
        $config = $this->params();
        foreach (['apiClientID', 'apiKey', 'secret', 'url', 'currency'] as $key) {
            if (empty($config[$key])) {
                throw new InvalidConfigException("Missing eCitizen configuration value: {$key}.");
            }
        }
        return $config;
    }

    private function assertConsoleSmisWriteContext(): void
    {
        if (Yii::$app instanceof \yii\console\Application) {
            return;
        }

        Yii::error('Blocked web-context attempt to post eCitizen payment directly into SMIS.', 'ecitizen.payment');
        throw new ServerErrorHttpException('SMIS posting is only available through the synchronization worker.');
    }

    private function pendingRequestByReference(string $reference): ?array
    {
        $request = $this->portalDb()->createCommand(
            'select *
               from smisportal.ecitizen
              where "billRefNumber" = :reference
                and coalesce(status, :pending) in (:pending, :paid, :credited, :settled)
              order by payment_id desc
              limit 1',
            [
                ':reference' => $reference,
                ':pending' => 'Pending',
                ':paid' => 'Paid',
                ':credited' => 'Credited',
                ':settled' => 'Settled',
            ]
        )->queryOne();

        return $request ?: null;
    }

    private function creditPortalFeeStatement(
        Connection $portalDb,
        array $request,
        float $amount,
        string $paymentDate,
        string $gatewayReference,
        array $metadata
    ): void {
        $paymentId = (int) $request['payment_id'];
        $transId = $this->portalCreditTransId($paymentId);
        $existingCredit = $portalDb->createCommand(
            'select 1 from smisportal.fss_fee_transactions where trans_id = :trans_id and trans_type = :trans_type',
            [
                ':trans_id' => $transId,
                ':trans_type' => 'CR',
            ]
        )->queryScalar();

        $registrationNumber = (string) $request['registration_number'];
        $context = $this->portalStudentContextByRegistrationNumber($registrationNumber);
        $description = substr((string) ($request['billDesc'] ?? 'eCitizen student fee payment'), 0, 150);
        $userId = (string) ($metadata['user_id'] ?? $registrationNumber);
        $progressCode = $this->progressCodeFor($portalDb, 'smisportal', $registrationNumber, (int) $context['academicProgress']['acad_session_id']);

        if (!$existingCredit) {
            $portalDb->createCommand()->insert('smisportal.fss_fee_transactions', [
                'trans_id' => $transId,
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
                'student_semester_session_id' => $this->studentSemesterSessionId($portalDb, 'smisportal', (int) $context['academicProgress']['academic_progress_id']),
            ])->execute();
        }

        $existingPayment = $portalDb->createCommand(
            'select 1 from smisportal.fss_fee_payments where trans_id = :trans_id',
            [':trans_id' => $transId]
        )->queryScalar();

        $collectionPointId = $metadata['bank_id'] ?? null;
        if ($existingPayment || $collectionPointId === null || $collectionPointId === '') {
            return;
        }

        $portalDb->createCommand()->insert('smisportal.fss_fee_payments', [
            'fee_paymt_id' => $transId,
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
        ])->execute();
    }

    private function createSettledBankingSlip(array $request, string $paymentDate, string $gatewayReference): array
    {
        $metadata = json_decode((string) ($request['response'] ?? ''), true);
        $metadata = is_array($metadata) ? $metadata : [];
        $reference = (string) $request['billRefNumber'];
        $paymentTypeId = $metadata['payment_type_id'] ?? null;

        $transId = $this->db->createCommand(
            "insert into smis.fss_banking_slips
                (deposit_date, deposit_type, payment_type_id, deposit_amount, reg_number, registration_number,
                 other_names, post_status, post_comment, account_no, process_date,
                 pay_mode, trans_reference, branch_code, last_update, user_id, drawer_name, source_reference,
                 value_date, bank_id, bank_number)
             values
                (:deposit_date, :deposit_type, :payment_type_id, :deposit_amount, :reg_number, :registration_number,
                 :other_names, 'NOT POSTED', :post_comment, :account_no, :process_date,
                 :pay_mode, :trans_reference, :branch_code, now(), :user_id, :drawer_name, :source_reference,
                 now(), :bank_id, :bank_number)
             returning trans_id",
            [
                ':deposit_date' => $paymentDate,
                ':deposit_type' => $paymentTypeId,
                ':payment_type_id' => $paymentTypeId,
                ':deposit_amount' => (float) $request['amountExpected'],
                ':reg_number' => $request['registration_number'],
                ':registration_number' => $request['registration_number'],
                ':other_names' => $metadata['other_names'] ?? $request['clientName'] ?? '',
                ':post_comment' => substr((string) ($request['billDesc'] ?? 'eCitizen payment'), 0, 20),
                ':account_no' => $metadata['account_no'] ?? null,
                ':process_date' => date('Y-m-d'),
                ':pay_mode' => self::PAYMENT_MODE_ID,
                ':trans_reference' => substr($gatewayReference ?: $reference, 0, 50),
                ':branch_code' => $metadata['branch_code'] ?? null,
                ':user_id' => $metadata['user_id'] ?? null,
                ':drawer_name' => 'eCitizen',
                ':source_reference' => $reference,
                ':bank_id' => $metadata['bank_id'] ?? null,
                ':bank_number' => $metadata['bank_number'] ?? null,
            ]
        )->queryScalar();

        return $this->db->createCommand(
            'select * from smis.fss_banking_slips where trans_id = :trans_id',
            [':trans_id' => $transId]
        )->queryOne();
    }

    private function markPendingRequestSettled(string $reference, string $gatewayReference, int $transId): void
    {
        $portalDb = $this->portalDb();
        $transaction = $portalDb->beginTransaction();
        try {
            $existingResponse = $portalDb->createCommand(
                'select response from smisportal.ecitizen where "billRefNumber" = :reference',
                [':reference' => $reference]
            )->queryScalar();
            $metadata = json_decode((string) $existingResponse, true);
            $metadata = is_array($metadata) ? $metadata : [];
            $metadata['gateway_reference'] = $gatewayReference;

            $portalDb->createCommand("set local smisportal.ecitizen_app_write = '1'")->execute();
            $portalDb->createCommand(
                'update smisportal.ecitizen
                    set status = :status,
                        response = :response,
                        synced_trans_id = :synced_trans_id,
                        sync_status = :sync_status,
                        sync_error = null,
                        last_synced_at = now()
                  where "billRefNumber" = :reference',
                [
                    ':status' => 'Settled',
                    ':response' => json_encode($metadata),
                    ':synced_trans_id' => $transId,
                    ':sync_status' => self::SYNC_DONE,
                    ':reference' => $reference,
                ]
            )->execute();
            $transaction->commit();
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            Yii::warning('Unable to mark eCitizen request settled for ' . $reference . ': ' . $exception->getMessage(), 'ecitizen.payment');
        }
    }

    private function ensureFeeTransactionDescription(array $slip, float $amount, string $paymentDate, string $paymentDescription): void
    {
        $existing = $this->db->createCommand(
            'select 1 from smis.fss_fee_transactions where trans_id = :trans_id',
            [':trans_id' => $slip['trans_id']]
        )->queryScalar();

        if ($existing) {
            $this->db->createCommand()->update('smis.fss_fee_transactions', [
                'trans_desc' => substr($paymentDescription, 0, 150),
            ], ['trans_id' => $slip['trans_id']])->execute();
            return;
        }

        $studentContext = $this->studentContextByRegistrationNumber((string) $slip['reg_number']);
        $academicProgress = $studentContext['academicProgress'];
        $progressCode = $this->progressCode($studentContext['registrationNumber'], (int) $academicProgress['acad_session_id']);

        $this->db->createCommand()->insert('smis.fss_fee_transactions', [
            'trans_id' => $slip['trans_id'],
            'academic_progress_id' => $academicProgress['academic_progress_id'],
            'trans_date' => $paymentDate,
            'trans_type' => 'CR',
            'trans_amount' => $amount,
            'trans_desc' => substr($paymentDescription, 0, 150),
            'user_id' => (string) ($slip['user_id'] ?? ''),
            'receipt_status' => '',
            'exchange_rate' => 1,
            'progress_code' => $progressCode,
            'sync_status' => false,
            'student_semester_session_id' => $this->studentSemesterSessionId($this->db, 'smis', (int) $academicProgress['academic_progress_id']),
        ])->execute();
    }

    private function ensureSmisFeePayment(array $slip, float $amount, string $paymentDate): void
    {
        $existingPayment = $this->db->createCommand(
            'select * from smis.fss_fee_payments where trans_id = :trans_id',
            [':trans_id' => $slip['trans_id']]
        )->queryOne();

        if ($existingPayment) {
            if (empty($slip['receipt_no']) && !empty($existingPayment['receipt_no']) && ctype_digit((string) $existingPayment['receipt_no'])) {
                $this->db->createCommand()->update('smis.fss_banking_slips', [
                    'receipt_no' => (int) $existingPayment['receipt_no'],
                    'post_status' => 'POSTED',
                    'last_update' => new \yii\db\Expression('now()'),
                ], ['trans_id' => $slip['trans_id']])->execute();
            }
            return;
        }

        if (empty($slip['bank_id'])) {
            throw new ServerErrorHttpException('The eCitizen banking slip is missing a collection point.');
        }

        $studentContext = $this->studentContextByRegistrationNumber((string) $slip['reg_number']);
        $receiptNo = $this->ensureSmisReceiptNo($slip);
        $userId = (string) ($slip['user_id'] ?? $slip['reg_number'] ?? '');

        $this->db->createCommand()->insert('smis.fss_fee_payments', [
            'receipt_no' => (string) $receiptNo,
            'trans_date' => $paymentDate,
            'trans_amount' => $amount,
            'pay_mode' => self::PAYMENT_MODE_ID,
            'collection_point_id' => (int) $slip['bank_id'],
            'user_id' => $userId,
            'entry_date' => date('Y-m-d'),
            'trans_id' => (int) $slip['trans_id'],
            'academic_session' => '',
            'authorized_by' => $userId,
            'authorized_date' => date('Y-m-d'),
            'receipt_status' => '',
            'exchange_rate' => 1,
            'student_prog_curriculum_id' => $studentContext['programme']['student_prog_curriculum_id'],
        ])->execute();

        $this->db->createCommand()->update('smis.fss_banking_slips', [
            'receipt_no' => $receiptNo,
            'post_status' => 'POSTED',
            'last_update' => new \yii\db\Expression('now()'),
        ], ['trans_id' => $slip['trans_id']])->execute();
    }

    private function ensureSmisReceiptNo(array $slip): int
    {
        if (!empty($slip['receipt_no'])) {
            return (int) $slip['receipt_no'];
        }

        $lastValue = (int) $this->db->createCommand(
            'select coalesce(max(last_value), 0) from smis.fss_receipt_counter'
        )->queryScalar();
        $nextValue = $lastValue + 1;

        $this->db->createCommand()->insert('smis.fss_receipt_counter', [
            'last_value' => $nextValue,
        ])->execute();

        $this->db->createCommand()->update('smis.fss_banking_slips', [
            'receipt_no' => $nextValue,
        ], ['trans_id' => $slip['trans_id']])->execute();

        return $nextValue;
    }

    private function portalStudentContextByRegistrationNumber(string $registrationNumber): array
    {
        $portalDb = $this->portalDb();
        $programme = $portalDb->createCommand(
            'select * from smisportal.sm_student_programme_curriculum where registration_number = :registration_number order by student_prog_curriculum_id desc limit 1',
            [':registration_number' => $registrationNumber]
        )->queryOne();
        $academicProgress = $portalDb->createCommand(
            'select * from smisportal.sm_academic_progress where student_prog_curriculum_id = :student_prog_curriculum_id order by academic_progress_id desc limit 1',
            [':student_prog_curriculum_id' => $programme['student_prog_curriculum_id'] ?? null]
        )->queryOne();

        if (!$programme || !$academicProgress) {
            throw new NotFoundHttpException('Student portal fee posting records could not be resolved.');
        }

        return [
            'registrationNumber' => $registrationNumber,
            'programme' => $programme,
            'academicProgress' => $academicProgress,
        ];
    }

    private function studentSemesterSessionId(Connection $db, string $schema, int $academicProgressId): ?int
    {
        try {
            $id = $db->createCommand(
                "select student_semester_session_id from {$schema}.sm_student_sem_session_progress where academic_progress_id = :academic_progress_id order by student_semester_session_id desc limit 1",
                [':academic_progress_id' => $academicProgressId]
            )->queryScalar();
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
        $db->createCommand(
            'select pg_advisory_xact_lock(hashtext(:lock_key))',
            [':lock_key' => 'ecitizen-payment-' . $reference]
        )->execute();
    }

    private function nextNumericValue(Connection $db, string $table, string $column): int
    {
        return ((int) $db->createCommand("select coalesce(max({$column}), 0) + 1 from {$table}")->queryScalar());
    }

    private function receiptNumber(string $gatewayReference, string $fallback): string
    {
        $receiptNo = trim($gatewayReference) !== '' ? $gatewayReference : $fallback;
        return substr($receiptNo, 0, 30);
    }

    private function progressCode(string $registrationNumber, int $academicSessionId): string
    {
        return $this->progressCodeFor($this->db, 'smis', $registrationNumber, $academicSessionId);
    }

    private function progressCodeFor(Connection $db, string $schema, string $registrationNumber, int $academicSessionId): string
    {
        $sessionName = $db->createCommand(
            "select acad_session_name from {$schema}.org_academic_session where acad_session_id = :acad_session_id",
            [':acad_session_id' => $academicSessionId]
        )->queryScalar();

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
            'notificationURL' => \yii\helpers\Url::to(['/ecitizen/payment/notify'], true),
            'callBackURLOnSuccess' => \yii\helpers\Url::to(['/ecitizen/payment/invoices'], true),
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
            'select * from smis.sm_academic_progress where student_prog_curriculum_id = :student_prog_curriculum_id order by academic_progress_id desc limit 1',
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
        return date('Y-m-d', strtotime($payload['payment_date'] ?? $payload['paymentDate'] ?? 'now'));
    }

    public function gatewayReference(array $payload, string $fallback): string
    {
        return (string) ($payload['invoice_number'] ?? $payload['invoiceNumber'] ?? $payload['transaction_id'] ?? $payload['trans_reference'] ?? $fallback);
    }

    private function paymentTypeDescription(array $slip): string
    {
        $paymentTypeId = $slip['payment_type_id'] ?? $slip['deposit_type'] ?? null;
        if ($paymentTypeId !== null && $paymentTypeId !== '') {
            $description = $this->db->createCommand(
                'select payment_desc from smis.fss_payment_types where payment_type_id = :payment_type_id',
                [':payment_type_id' => $paymentTypeId]
            )->queryScalar();

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
