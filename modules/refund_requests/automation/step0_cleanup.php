<?php
/**
 * Step 0: Cleanup for NR605/0001/2022
 * Deletes existing FSS refund records to allow a fresh start.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Step 0: Cleaning up FSS refund records for $regNo ---\n";

$requestIds = requestIds(Yii::$app->db, 'smisportal', $regNo);
$studentProgCurriculumId = (new \yii\db\Query())
    ->select('student_prog_curriculum_id')
    ->from('smisportal.sm_student_programme_curriculum')
    ->where(['registration_number' => $regNo])
    ->scalar();

$smisRequestIds = [];
if ($studentProgCurriculumId) {
    $smisRequestIds = (new \yii\db\Query())
        ->select('request_id')
        ->from('smis.fss_refund_requests')
        ->where(['student_prog_curriculum_id' => $studentProgCurriculumId])
        ->column(Yii::$app->smisDb);
}

$allSmisRequestIds = array_values(array_unique(array_merge($requestIds, $smisRequestIds)));
$portalVoucherNos = uniqueInts(array_merge(
    voucherNos(Yii::$app->db, 'smisportal', $requestIds),
    automationBatchVoucherNos(Yii::$app->db, 'smisportal'),
    orphanBatchVoucherNos(Yii::$app->db, 'smisportal')
));
$smisVoucherNos = uniqueInts(array_merge(
    voucherNos(Yii::$app->smisDb, 'smis', $allSmisRequestIds),
    postingVoucherNos(Yii::$app->smisDb, 'smis', $regNo),
    automationBatchVoucherNos(Yii::$app->smisDb, 'smis'),
    orphanBatchVoucherNos(Yii::$app->smisDb, 'smis')
));

$transactionPortal = Yii::$app->db->beginTransaction();
$transactionSmis = Yii::$app->smisDb->beginTransaction();

try {
    $deletedDisapproved = deleteDisapproved(Yii::$app->db, 'smisportal', $requestIds);
    $deletedSmisDisapproved = deleteDisapproved(Yii::$app->smisDb, 'smis', $allSmisRequestIds);
    $deletedApprovals = deleteApprovals(Yii::$app->db, 'smisportal', $requestIds);
    $deletedSmisApprovals = deleteApprovals(Yii::$app->smisDb, 'smis', $allSmisRequestIds);
    $deletedSmisFeeTransactions = deletePostingFeeTransactions(Yii::$app->smisDb, 'smis', $smisVoucherNos, $regNo);

    $deletedPortal = $requestIds
        ? Yii::$app->db->createCommand()->delete('smisportal.fss_refund_requests', ['request_id' => $requestIds])->execute()
        : 0;

    $deletedSmis = $allSmisRequestIds
        ? Yii::$app->smisDb->createCommand()->delete('smis.fss_refund_requests', ['request_id' => $allSmisRequestIds])->execute()
        : 0;

    if ($studentProgCurriculumId) {
        $deletedSmis += Yii::$app->smisDb->createCommand()
            ->delete('smis.fss_refund_requests', ['student_prog_curriculum_id' => $studentProgCurriculumId])
            ->execute();
    }

    $deletedPortalCancelled = deleteCancelledVouchers(Yii::$app->db, 'smisportal', $portalVoucherNos, $requestIds);
    $deletedSmisCancelled = deleteCancelledVouchers(Yii::$app->smisDb, 'smis', $smisVoucherNos, $allSmisRequestIds);
    $deletedPortalBatches = deleteRefundBatches(Yii::$app->db, 'smisportal', $portalVoucherNos);
    $deletedSmisBatches = deleteRefundBatches(Yii::$app->smisDb, 'smis', $smisVoucherNos);

    echo "Deleted $deletedDisapproved disapproved records from smisportal.fss_refund_requests_disapproved\n";
    echo "Deleted $deletedSmisDisapproved disapproved records from smis.fss_refund_requests_disapproved\n";
    echo "Deleted $deletedApprovals approval records from smisportal.fss_refund_approval_process\n";
    echo "Deleted $deletedSmisApprovals approval records from smis.fss_refund_approval_process\n";
    echo "Deleted $deletedSmisFeeTransactions posted fee transaction records from smis.fss_fee_transactions\n";
    echo "Deleted $deletedPortal records from smisportal.fss_refund_requests\n";
    echo "Deleted $deletedSmis records from smis.fss_refund_requests\n";
    echo "Deleted $deletedPortalCancelled cancelled voucher records from smisportal.fss_cancelled_vouchers\n";
    echo "Deleted $deletedSmisCancelled cancelled voucher records from smis.fss_cancelled_vouchers\n";
    echo "Deleted $deletedPortalBatches refund batch records from smisportal.fss_refund_batches\n";
    echo "Deleted $deletedSmisBatches refund batch records from smis.fss_refund_batches\n";

    $transactionPortal->commit();
    $transactionSmis->commit();
    echo "SUCCESS: Cleanup completed.\n";
} catch (\Throwable $e) {
    $transactionPortal->rollBack();
    $transactionSmis->rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}

function requestIds(\yii\db\Connection $db, string $schema, string $regNo): array
{
    return (new \yii\db\Query())
        ->select('r.request_id')
        ->from($schema . '.fss_refund_requests r')
        ->innerJoin($schema . '.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id = r.student_prog_curriculum_id')
        ->where(['spc.registration_number' => $regNo])
        ->column($db);
}

function voucherNos(\yii\db\Connection $db, string $schema, array $requestIds): array
{
    if (!$requestIds) {
        return [];
    }

    return (new \yii\db\Query())
        ->select('voucher_no')
        ->from($schema . '.fss_refund_requests')
        ->where(['request_id' => $requestIds])
        ->andWhere(['not', ['voucher_no' => null]])
        ->column($db);
}

function postingVoucherNos(\yii\db\Connection $db, string $schema, string $regNo): array
{
    if ($db->getTableSchema($schema . '.fss_fee_transactions', true) === null) {
        return [];
    }

    $descriptions = (new \yii\db\Query())
        ->select('trans_desc')
        ->from($schema . '.fss_fee_transactions')
        ->where(['LIKE', 'progress_code', $regNo . '%', false])
        ->andWhere(['LIKE', 'trans_desc', 'CAUTION REFUND - ', false])
        ->column($db);

    $voucherNos = [];
    foreach ($descriptions as $description) {
        if (preg_match('/CAUTION REFUND -\s*(\d+)/i', (string)$description, $matches)) {
            $voucherNos[] = (int)$matches[1];
        }
    }

    return uniqueInts($voucherNos);
}

function automationBatchVoucherNos(\yii\db\Connection $db, string $schema): array
{
    if ($db->getTableSchema($schema . '.fss_refund_batches', true) === null) {
        return [];
    }

    return uniqueInts((new \yii\db\Query())
        ->select('voucher_no')
        ->from($schema . '.fss_refund_batches')
        ->where(['posted_by' => 'AUTO-POST'])
        ->column($db));
}

function orphanBatchVoucherNos(\yii\db\Connection $db, string $schema): array
{
    if (
        $db->getTableSchema($schema . '.fss_refund_batches', true) === null
        || $db->getTableSchema($schema . '.fss_refund_requests', true) === null
    ) {
        return [];
    }

    return uniqueInts((new \yii\db\Query())
        ->select('b.voucher_no')
        ->from($schema . '.fss_refund_batches b')
        ->leftJoin($schema . '.fss_refund_requests r', 'r.voucher_no = b.voucher_no')
        ->where(['r.voucher_no' => null])
        ->column($db));
}

function uniqueInts(array $values): array
{
    $ints = [];
    foreach ($values as $value) {
        if ($value === null || $value === '') {
            continue;
        }

        $ints[(int)$value] = (int)$value;
    }

    sort($ints);
    return array_values($ints);
}

function deleteDisapproved(\yii\db\Connection $db, string $schema, array $requestIds): int
{
    if (!$requestIds || $db->getTableSchema($schema . '.fss_refund_requests_disapproved', true) === null) {
        return 0;
    }

    return $db->createCommand()
        ->delete($schema . '.fss_refund_requests_disapproved', ['request_id' => $requestIds])
        ->execute();
}

function deleteApprovals(\yii\db\Connection $db, string $schema, array $requestIds): int
{
    if (!$requestIds) {
        return 0;
    }

    return $db->createCommand()
        ->delete($schema . '.fss_refund_approval_process', ['request_id' => $requestIds])
        ->execute();
}

function deletePostingFeeTransactions(\yii\db\Connection $db, string $schema, array $voucherNos, string $regNo): int
{
    if ($db->getTableSchema($schema . '.fss_fee_transactions', true) === null) {
        return 0;
    }

    $refundDescriptions = array_map(static fn(int|string $voucherNo): string => 'CAUTION REFUND - ' . $voucherNo, $voucherNos);
    $postingDescriptionCondition = [
        'or',
        ['and', ['user_id' => 'AUTO-POST'], ['trans_desc' => 'CAUTION MONEY']],
    ];

    if ($refundDescriptions) {
        $postingDescriptionCondition[] = ['trans_desc' => $refundDescriptions];
    }

    return $db->createCommand()
        ->delete($schema . '.fss_fee_transactions', [
            'and',
            ['LIKE', 'progress_code', $regNo . '%', false],
            $postingDescriptionCondition,
        ])
        ->execute();
}

function deleteRefundBatches(\yii\db\Connection $db, string $schema, array $voucherNos): int
{
    if (!$voucherNos || $db->getTableSchema($schema . '.fss_refund_batches', true) === null) {
        return 0;
    }

    return $db->createCommand()
        ->delete($schema . '.fss_refund_batches', ['voucher_no' => $voucherNos])
        ->execute();
}

function deleteCancelledVouchers(\yii\db\Connection $db, string $schema, array $voucherNos, array $requestIds): int
{
    $table = $db->getTableSchema($schema . '.fss_cancelled_vouchers', true);
    if ($table === null) {
        return 0;
    }

    $conditions = ['or'];
    if ($voucherNos) {
        $conditions[] = ['voucher_no' => $voucherNos];
    }
    $conditions[] = ['voucher_no' => null];
    if ($requestIds && in_array('request_id', $table->columnNames, true)) {
        $conditions[] = ['request_id' => $requestIds];
    }

    if (count($conditions) === 1) {
        return 0;
    }

    return $db->createCommand()
        ->delete($schema . '.fss_cancelled_vouchers', $conditions)
        ->execute();
}
