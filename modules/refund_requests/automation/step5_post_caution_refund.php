<?php
/**
 * Step 5: Post Caution Refund
 * Posts the latest fully approved caution refund for NR605/0001/2022.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Step 5: Posting caution refund for $regNo ---\n";

$request = (new \yii\db\Query())
    ->select(['r.*', 'spc.registration_number', 'rt.refund_type_name'])
    ->from('smisportal.fss_refund_requests r')
    ->innerJoin('smisportal.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id = r.student_prog_curriculum_id')
    ->innerJoin('smisportal.fss_refund_types rt', 'rt.refund_type_id = r.refund_type')
    ->where(['spc.registration_number' => $regNo])
    ->andWhere(['rt.refund_type_name' => 'CAUTION'])
    ->orderBy(['r.request_id' => SORT_DESC])
    ->one(Yii::$app->db);

if (!$request) {
    die("ERROR: Caution refund request not found.\n");
}

$requestId = (int)$request['request_id'];
$amount = (float)($request['amount_approved'] ?: $request['amount_requested']);

$transactionPortal = Yii::$app->db->beginTransaction();
$transactionSmis = Yii::$app->smisDb->beginTransaction();

try {
    ensurePostingTables(Yii::$app->db, 'smisportal');
    ensurePostingTables(Yii::$app->smisDb, 'smis');
    ensurePostable(Yii::$app->db, 'smisportal', $requestId);
    ensurePostable(Yii::$app->smisDb, 'smis', $requestId);

    $voucherNo = nextVoucherNo();
    saveRefundDetails(Yii::$app->db, 'smisportal', $voucherNo);
    saveRefundDetails(Yii::$app->smisDb, 'smis', $voucherNo);
    insertPostingBatch(Yii::$app->db, 'smisportal', $voucherNo, 1, $amount);
    insertPostingBatch(Yii::$app->smisDb, 'smis', $voucherNo, 1, $amount);

    postSide(Yii::$app->db, 'smisportal', $requestId, $voucherNo, $amount);
    postSide(Yii::$app->smisDb, 'smis', $requestId, $voucherNo, $amount);

    $transactionPortal->commit();
    $transactionSmis->commit();

    echo "SUCCESS: Request {$requestId} posted under voucher #{$voucherNo}.\n";
} catch (\Throwable $e) {
    $transactionPortal->rollBack();
    $transactionSmis->rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}

function ensurePostingTables(\yii\db\Connection $db, string $schema): void
{
    $quotedSchema = $db->quoteTableName($schema);
    $detailsTable = $db->quoteTableName($schema . '.fss_refund_details');
    $batchTable = $db->quoteTableName($schema . '.fss_refund_posting_batches');
    $itemTable = $db->quoteTableName($schema . '.fss_refund_posting_items');

    $db->createCommand(<<<SQL
CREATE TABLE IF NOT EXISTS {$detailsTable} (
    pv_no int8 NOT NULL,
    date_posted timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_id varchar(30) NOT NULL,
    amount numeric(15,2) NOT NULL DEFAULT 0,
    source varchar(50) NULL,
    CONSTRAINT fss_refund_details_pkey PRIMARY KEY (pv_no)
)
SQL)->execute();

    $db->createCommand(<<<SQL
CREATE TABLE IF NOT EXISTS {$batchTable} (
    posting_batch_id int8 NOT NULL,
    voucher_no int8 NOT NULL,
    refund_type varchar(50) NOT NULL,
    request_count int4 NOT NULL DEFAULT 0,
    total_amount numeric(15,2) NOT NULL DEFAULT 0,
    posted_by varchar(30) NOT NULL,
    posted_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    remarks varchar(255) NULL,
    CONSTRAINT fss_refund_posting_batches_pkey PRIMARY KEY (posting_batch_id),
    CONSTRAINT fss_refund_posting_batches_voucher_no_key UNIQUE (voucher_no)
)
SQL)->execute();

    $db->createCommand(<<<SQL
CREATE TABLE IF NOT EXISTS {$itemTable} (
    posting_item_id int8 NOT NULL,
    posting_batch_id int8 NOT NULL,
    request_id int8 NOT NULL,
    registration_number varchar(100) NULL,
    amount_posted numeric(15,2) NOT NULL,
    debit_trans_id int8 NULL,
    credit_trans_id int8 NULL,
    posted_by varchar(30) NOT NULL,
    posted_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    posting_status varchar(20) NOT NULL DEFAULT 'POSTED',
    remarks varchar(255) NULL,
    CONSTRAINT fss_refund_posting_items_pkey PRIMARY KEY (posting_item_id),
    CONSTRAINT fss_refund_posting_items_request_key UNIQUE (request_id),
    CONSTRAINT fk_refund_posting_batch
        FOREIGN KEY (posting_batch_id)
        REFERENCES {$quotedSchema}.fss_refund_posting_batches(posting_batch_id),
    CONSTRAINT fk_refund_posting_request
        FOREIGN KEY (request_id)
        REFERENCES {$quotedSchema}.fss_refund_requests(request_id)
)
SQL)->execute();
}

function ensurePostable(\yii\db\Connection $db, string $schema, int $requestId): void
{
    $approved = (new \yii\db\Query())
        ->from($schema . '.fss_refund_requests r')
        ->innerJoin($schema . '.fss_refund_types rt', 'rt.refund_type_id = r.refund_type')
        ->where(['r.request_id' => $requestId])
        ->andWhere(['r.approval_status' => 'APPROVED'])
        ->andWhere(['rt.refund_type_name' => 'CAUTION'])
        ->andWhere("UPPER(COALESCE(r.refund_status, 'NOT REFUNDED')) <> 'REFUNDED'")
        ->exists($db);

    if (!$approved) {
        throw new \RuntimeException("Request {$requestId} is not a fully approved, unposted caution refund in {$schema}.");
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

function nextVoucherNo(): int
{
    $next = 1;
    foreach ([[Yii::$app->db, 'smisportal'], [Yii::$app->smisDb, 'smis']] as [$db, $schema]) {
        $detailsMax = (int)$db->createCommand("SELECT COALESCE(MAX(pv_no), 0) FROM {$schema}.fss_refund_details")->queryScalar();
        $batchMax = (int)$db->createCommand("SELECT COALESCE(MAX(posting_batch_id), 0) FROM {$schema}.fss_refund_posting_batches")->queryScalar();
        $next = max($next, $detailsMax + 1, $batchMax + 1);
    }

    return $next;
}

function saveRefundDetails(\yii\db\Connection $db, string $schema, int $voucherNo): void
{
    $db->createCommand()
        ->insert($schema . '.fss_refund_details', [
            'pv_no' => $voucherNo,
            'date_posted' => date('Y-m-d H:i:s'),
            'user_id' => 'AUTO-POST',
            'amount' => 0,
            'source' => null,
        ])
        ->execute();
}

function insertPostingBatch(\yii\db\Connection $db, string $schema, int $voucherNo, int $count, float $amount): void
{
    $db->createCommand()
        ->insert($schema . '.fss_refund_posting_batches', [
            'posting_batch_id' => $voucherNo,
            'voucher_no' => $voucherNo,
            'refund_type' => 'CAUTION',
            'request_count' => $count,
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

    $progress = refundAcademicProgress($db, $schema, (int)$request['student_prog_curriculum_id']);
    $debitTransId = insertFeeTransaction($db, $schema, $progress, $amount, 'DR', 'Caution Money');
    $creditTransId = insertFeeTransaction($db, $schema, $progress, $amount, 'CR', 'Caution Refund - ' . $voucherNo);

    $db->createCommand()
        ->insert($schema . '.fss_refund_posting_items', [
            'posting_item_id' => nextPostingItemId($db, $schema),
            'posting_batch_id' => $voucherNo,
            'request_id' => $requestId,
            'registration_number' => $progress['registration_number'],
            'amount_posted' => $amount,
            'debit_trans_id' => $debitTransId,
            'credit_trans_id' => $creditTransId,
            'posted_by' => 'AUTO-POST',
            'posted_at' => date('Y-m-d H:i:s'),
            'posting_status' => 'POSTED',
        ])
        ->execute();

    $db->createCommand()
        ->update($schema . '.fss_refund_requests', [
            'refund_status' => 'REFUNDED',
            'voucher_no' => $voucherNo,
            'amount_approved' => $amount,
        ], ['request_id' => $requestId])
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

function nextPostingItemId(\yii\db\Connection $db, string $schema): int
{
    return ((int)$db->createCommand("SELECT COALESCE(MAX(posting_item_id), 0) FROM {$schema}.fss_refund_posting_items")->queryScalar()) + 1;
}
