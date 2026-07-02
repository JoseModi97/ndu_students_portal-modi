<?php
/**
 * Step 6: Save Paid Refund Voucher
 * Marks the latest posted, unpaid caution refund batch for NR605/0001/2022 as paid on SMIS.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Step 6: Saving paid refund voucher details for $regNo on SMIS ---\n";

$request = (new \yii\db\Query())
    ->select(['r.request_id', 'r.voucher_no', 'r.refund_status', 'r.approval_status', 'rt.refund_type_name'])
    ->from('smis.fss_refund_requests r')
    ->innerJoin('smis.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id = r.student_prog_curriculum_id')
    ->innerJoin('smis.fss_refund_types rt', 'rt.refund_type_id = r.refund_type')
    ->where(['spc.registration_number' => $regNo])
    ->andWhere('UPPER(rt.refund_type_name) = :refund_type_name', [':refund_type_name' => 'CAUTION'])
    ->andWhere(['not', ['r.voucher_no' => null]])
    ->orderBy(['r.request_id' => SORT_DESC])
    ->one(Yii::$app->smisDb);

if (!$request) {
    die("ERROR: Posted caution refund request not found in SMIS.\n");
}

$voucherNo = (int)$request['voucher_no'];
$requestId = (int)$request['request_id'];

$transaction = Yii::$app->smisDb->beginTransaction();

try {
    $result = savePaidRefundVoucherBatch(Yii::$app->smisDb, 'smis', [$voucherNo]);
    $transaction->commit();

    echo "SUCCESS: Voucher #{$voucherNo} was marked PAID.\n";
    echo "Updated {$result['batches']} batch row(s).\n";
} catch (\Throwable $e) {
    $transaction->rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}

function savePaidRefundVoucherBatch(\yii\db\Connection $db, string $schema, array $voucherNos): array
{
    $voucherNos = array_values(array_unique(array_filter(array_map('intval', $voucherNos))));
    if (!$voucherNos) {
        throw new \RuntimeException('No posted batch was selected for payment update.');
    }

    $batchTable = $db->getTableSchema($schema . '.fss_refund_batches', true);
    if ($batchTable === null) {
        throw new \RuntimeException("{$schema}.fss_refund_batches does not exist.");
    }

    $requestTable = $db->getTableSchema($schema . '.fss_refund_requests', true);
    if ($requestTable === null) {
        throw new \RuntimeException("{$schema}.fss_refund_requests does not exist.");
    }

    $eligibleVoucherNos = (new \yii\db\Query())
        ->select('voucher_no')
        ->from($schema . '.fss_refund_batches')
        ->where([
            'and',
            ['voucher_no' => $voucherNos],
            ['not', ['posted_at' => null]],
            ['date_paid' => null],
        ])
        ->column($db);

    $eligibleVoucherNos = array_values(array_unique(array_map('intval', $eligibleVoucherNos)));
    if (!$eligibleVoucherNos) {
        throw new \RuntimeException('The selected batch is either not posted, already paid, or no longer available for payment update.');
    }

    $paidAt = date('Y-m-d H:i:s');

    $updatedBatches = $db->createCommand()
        ->update($schema . '.fss_refund_batches', [
            'status' => 'PAID',
            'date_paid' => $paidAt,
        ], [
            'and',
            ['voucher_no' => $eligibleVoucherNos],
            ['date_paid' => null],
        ])
        ->execute();

    return [
        'batches' => $updatedBatches,
    ];
}
