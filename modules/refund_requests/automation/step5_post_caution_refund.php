<?php
/**
 * Step 5: Post Caution Refund
 * Posts the latest fully approved caution refund for NR605/0001/2022 on SMIS only.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Step 5: Posting caution refund for $regNo on SMIS ---\n";

$request = (new \yii\db\Query())
    ->select(['r.*', 'spc.registration_number', 'rt.refund_type_name'])
    ->from('smis.fss_refund_requests r')
    ->innerJoin('smis.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id = r.student_prog_curriculum_id')
    ->innerJoin('smis.fss_refund_types rt', 'rt.refund_type_id = r.refund_type')
    ->where(['spc.registration_number' => $regNo])
    ->andWhere('UPPER(rt.refund_type_name) = :refund_type_name', [':refund_type_name' => 'CAUTION'])
    ->andWhere(['r.voucher_no' => null])
    ->orderBy(['r.request_id' => SORT_DESC])
    ->one(Yii::$app->smisDb);

if (!$request) {
    die("ERROR: Unposted caution refund request not found in SMIS.\n");
}

$requestId = (int)$request['request_id'];
$amount = (float)($request['amount_approved'] ?: $request['amount_requested']);

$transaction = Yii::$app->smisDb->beginTransaction();

try {
    ensureRefundStructure(Yii::$app->smisDb, 'smis');
    ensurePostable(Yii::$app->smisDb, 'smis', $requestId);

    $voucherNo = nextVoucherNo(Yii::$app->smisDb, 'smis');
    insertRefundBatch(Yii::$app->smisDb, 'smis', $voucherNo, $amount);
    postSide(Yii::$app->smisDb, 'smis', $requestId, $voucherNo, $amount);

    $transaction->commit();

    echo "SUCCESS: SMIS request {$requestId} posted under voucher #{$voucherNo}.\n";
    echo "Portal request table was not updated; posting workflow is SMIS-only.\n";
} catch (\Throwable $e) {
    $transaction->rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}

function ensureRefundStructure(\yii\db\Connection $db, string $schema): void
{
    $batchTable = $db->quoteTableName($schema . '.fss_refund_batches');
    $cancelledTable = $db->quoteTableName($schema . '.fss_cancelled_vouchers');
    $requestTable = $db->quoteTableName($schema . '.fss_refund_requests');

    $db->createCommand("DROP TABLE IF EXISTS {$db->quoteTableName($schema . '.fss_refund_posting_items')}")->execute();
    $db->createCommand("DROP TABLE IF EXISTS {$db->quoteTableName($schema . '.fss_refund_details')}")->execute();

    if (tableExists($db, $schema, 'fss_refund_posting_batches')) {
        $oldBatchTable = $db->quoteTableName($schema . '.fss_refund_posting_batches');
        $db->createCommand(<<<SQL
CREATE TABLE IF NOT EXISTS {$batchTable} (
    voucher_no int8 NOT NULL,
    total_amount numeric(15,2) NOT NULL DEFAULT 0,
    posted_by varchar(30) NULL,
    posted_at timestamp NULL,
    status varchar(20) NULL,
    date_paid timestamp NULL,
    CONSTRAINT fss_refund_batches_pkey PRIMARY KEY (voucher_no)
)
SQL)->execute();
        $db->createCommand(<<<SQL
INSERT INTO {$batchTable} (voucher_no, total_amount, posted_by, posted_at, status, date_paid)
SELECT voucher_no, total_amount, posted_by, posted_at, NULL, NULL
FROM {$oldBatchTable}
ON CONFLICT (voucher_no) DO UPDATE SET
    total_amount = EXCLUDED.total_amount,
    posted_by = EXCLUDED.posted_by,
    posted_at = EXCLUDED.posted_at,
    status = NULL,
    date_paid = NULL
SQL)->execute();
        $db->createCommand("DROP TABLE IF EXISTS {$oldBatchTable}")->execute();
    }

    $db->createCommand(<<<SQL
CREATE TABLE IF NOT EXISTS {$batchTable} (
    voucher_no int8 NOT NULL,
    total_amount numeric(15,2) NOT NULL DEFAULT 0,
    posted_by varchar(30) NULL,
    posted_at timestamp NULL,
    status varchar(20) NULL,
    date_paid timestamp NULL,
    CONSTRAINT fss_refund_batches_pkey PRIMARY KEY (voucher_no)
)
SQL)->execute();

    $db->createCommand("ALTER TABLE {$batchTable} ALTER COLUMN posted_by DROP NOT NULL")->execute();
    $db->createCommand("ALTER TABLE {$batchTable} ALTER COLUMN posted_at DROP NOT NULL")->execute();
    $db->createCommand("ALTER TABLE {$batchTable} ALTER COLUMN posted_at DROP DEFAULT")->execute();

    $db->createCommand(<<<SQL
CREATE TABLE IF NOT EXISTS {$cancelledTable} (
    cancelled_vid int8 NOT NULL,
    voucher_no int8 NULL,
    request_id int8 NULL,
    date_cancelled timestamp NULL,
    amount numeric(15,2) NULL,
    userid varchar(30) NULL,
    remarks varchar(255) NULL,
    CONSTRAINT fss_cancelled_vouchers_pkey PRIMARY KEY (cancelled_vid),
    CONSTRAINT fk_voucher_no
        FOREIGN KEY (voucher_no)
        REFERENCES {$batchTable}(voucher_no)
)
SQL)->execute();

    if (ownsTable($db, $schema, 'fss_cancelled_vouchers')) {
        $db->createCommand("ALTER TABLE {$cancelledTable} ADD COLUMN IF NOT EXISTS request_id int8 NULL")->execute();
    }

    if (ownsTable($db, $schema, 'fss_refund_requests')) {
        $db->createCommand("ALTER TABLE {$requestTable} DROP CONSTRAINT IF EXISTS fss_refund_requests_voucher_no_fkey")->execute();
        $db->createCommand("ALTER TABLE {$requestTable} DROP CONSTRAINT IF EXISTS fk_voucher_no")->execute();
        $db->createCommand(<<<SQL
ALTER TABLE {$requestTable}
ADD CONSTRAINT fk_voucher_no
FOREIGN KEY (voucher_no)
REFERENCES {$batchTable}(voucher_no)
SQL)->execute();
    }
}

function ensurePostable(\yii\db\Connection $db, string $schema, int $requestId): void
{
    $approved = (new \yii\db\Query())
        ->from($schema . '.fss_refund_requests r')
        ->innerJoin($schema . '.fss_refund_types rt', 'rt.refund_type_id = r.refund_type')
        ->where(['r.request_id' => $requestId])
        ->andWhere('UPPER(rt.refund_type_name) = :refund_type_name', [':refund_type_name' => 'CAUTION'])
        ->andWhere("UPPER(COALESCE(r.approval_status, 'PENDING')) <> 'NOT APPROVED'")
        ->andWhere(['r.voucher_no' => null])
        ->exists($db);

    if (!$approved) {
        throw new \RuntimeException("Request {$requestId} is not an unposted caution refund in {$schema}.");
    }

    $level3Approved = (new \yii\db\Query())
        ->from($schema . '.fss_refund_approval_process p')
        ->innerJoin($schema . '.fss_refund_approvers a', 'a.approver_id = p.approver_id')
        ->where([
            'p.request_id' => $requestId,
            'a.approval_level_id' => 3,
            'p.approval_status' => 'APPROVED',
        ])
        ->exists($db);

    if (!$level3Approved) {
        throw new \RuntimeException("Request {$requestId} does not have Level 3 approval in {$schema}.");
    }
}

function nextVoucherNo(\yii\db\Connection $db, string $schema): int
{
    return ((int)$db->createCommand("SELECT COALESCE(MAX(voucher_no), 0) FROM {$schema}.fss_refund_batches")->queryScalar()) + 1;
}

function insertRefundBatch(\yii\db\Connection $db, string $schema, int $voucherNo, float $amount): void
{
    $db->createCommand()
        ->insert($schema . '.fss_refund_batches', [
            'voucher_no' => $voucherNo,
            'total_amount' => $amount,
            'posted_by' => 'AUTO-POST',
            'posted_at' => date('Y-m-d H:i:s'),
        ])
        ->execute();
}

function postSide(\yii\db\Connection $db, string $schema, int $requestId, int $voucherNo, float $amount): void
{
    $request = (new \yii\db\Query())
        ->from($schema . '.fss_refund_requests')
        ->where(['request_id' => $requestId])
        ->one($db);

    if (!$request) {
        throw new \RuntimeException("Request {$requestId} was not found in {$schema}.");
    }

    $progress = refundAcademicProgress($db, $schema, (int)$request['student_prog_curriculum_id']);
    insertFeeTransaction($db, $schema, $progress, $amount, 'DR', ' CAUTION MONEY');
    insertFeeTransaction($db, $schema, $progress, $amount, 'CR', 'CAUTION REFUND - ' . $voucherNo);

    $db->createCommand()
        ->update($schema . '.fss_refund_requests', [
            'approval_status' => 'APPROVED',
            'voucher_no' => $voucherNo,
            'amount_approved' => $amount,
        ], ['request_id' => $requestId])
        ->execute();

    updateCancelledVoucherRequestVoucher($db, $schema, $requestId, $voucherNo);
}

function updateCancelledVoucherRequestVoucher(\yii\db\Connection $db, string $schema, int $requestId, int $voucherNo): void
{
    $table = $db->getTableSchema($schema . '.fss_cancelled_vouchers', true);
    if ($table === null || !in_array('request_id', $table->columnNames, true)) {
        return;
    }

    $db->createCommand()
        ->update($schema . '.fss_cancelled_vouchers', [
            'voucher_no' => $voucherNo,
        ], [
            'and',
            ['request_id' => $requestId],
            ['voucher_no' => null],
        ])
        ->execute();
}

function refundAcademicProgress(\yii\db\Connection $db, string $schema, int $studentProgCurriculumId): array
{
    $row = $db->createCommand(
        <<<SQL
SELECT
    ap.academic_progress_id,
    sssp.student_semester_session_id,
    spc.registration_number,
    CONCAT(spc.registration_number, '-', COALESCE(sess.acad_session_name, ap.acad_session_id::varchar, ap.academic_progress_id::varchar)) AS progress_code
FROM {$schema}.sm_academic_progress ap
INNER JOIN {$schema}.sm_student_programme_curriculum spc ON spc.student_prog_curriculum_id = ap.student_prog_curriculum_id
LEFT JOIN {$schema}.sm_student_sem_session_progress sssp ON sssp.academic_progress_id = ap.academic_progress_id
LEFT JOIN {$schema}.org_academic_session sess ON sess.acad_session_id = ap.acad_session_id
WHERE ap.student_prog_curriculum_id = :student_prog_curriculum_id
ORDER BY ap.acad_session_id DESC, ap.academic_level_id DESC, ap.academic_progress_id DESC, sssp.student_semester_session_id DESC
LIMIT 1
SQL,
        [':student_prog_curriculum_id' => $studentProgCurriculumId]
    )->queryOne();

    if (!$row) {
        throw new \RuntimeException("Academic progress was not found for student programme curriculum {$studentProgCurriculumId} in {$schema}.");
    }

    return $row;
}

function insertFeeTransaction(\yii\db\Connection $db, string $schema, array $progress, float $amount, string $type, string $description): int
{
    $transId = nextTransId($db, $schema);
    $table = $db->getTableSchema($schema . '.fss_fee_transactions', true);
    $attributes = [
        'trans_id' => $transId,
        'academic_progress_id' => (int)$progress['academic_progress_id'],
        'trans_date' => date('Y-m-d'),
        'trans_type' => $type,
        'trans_amount' => $amount,
        'trans_desc' => $description,
        'user_id' => 'AUTO-POST',
        'receipt_status' => '',
        'exchange_rate' => 1.00,
        'progress_code' => (string)$progress['progress_code'],
        'sync_status' => false,
        'student_semester_session_id' => $progress['student_semester_session_id'] === null ? null : (int)$progress['student_semester_session_id'],
    ];

    if ($table !== null && in_array('fee_trans_id', $table->columnNames, true)) {
        $attributes['fee_trans_id'] = $transId;
    }

    $db->createCommand()
        ->insert($schema . '.fss_fee_transactions', $attributes)
        ->execute();

    return $transId;
}

function nextTransId(\yii\db\Connection $db, string $schema): int
{
    return ((int)$db->createCommand("SELECT COALESCE(MAX(trans_id), 0) FROM {$schema}.fss_fee_transactions")->queryScalar()) + 1;
}

function tableExists(\yii\db\Connection $db, string $schema, string $table): bool
{
    return (bool)$db->createCommand(
        'SELECT to_regclass(:table)',
        [':table' => "{$schema}.{$table}"]
    )->queryScalar();
}

function ownsTable(\yii\db\Connection $db, string $schema, string $table): bool
{
    return (bool)$db->createCommand(
        <<<SQL
SELECT pg_catalog.pg_get_userbyid(c.relowner) = current_user
FROM pg_class c
INNER JOIN pg_namespace n ON n.oid = c.relnamespace
WHERE n.nspname = :schema
  AND c.relname = :table
SQL,
        [':schema' => $schema, ':table' => $table]
    )->queryScalar();
}
