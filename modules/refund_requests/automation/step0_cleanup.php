<?php
/**
 * Step 0: Cleanup for NR605/0001/2022
 * Deletes existing caution refund records to allow a fresh start.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Step 0: Cleaning up records for $regNo ---\n";

$transactionPortal = Yii::$app->db->beginTransaction();
$transactionSmis = Yii::$app->smisDb->beginTransaction();

try {
    // Delete from SMIS (Official records)
    $deletedSmis = Yii::$app->smisDb->createCommand()
        ->delete('smis.sm_caution_refund_official', ['registration_number' => $regNo])
        ->execute();
    echo "Deleted $deletedSmis records from smis.sm_caution_refund_official\n";

    // Delete from Portal (Student requests)
    $deletedPortal = Yii::$app->db->createCommand()
        ->delete('smisportal.sm_caution_refund', ['registration_number' => $regNo])
        ->execute();
    echo "Deleted $deletedPortal records from smisportal.sm_caution_refund\n";

    $transactionPortal->commit();
    $transactionSmis->commit();
    echo "SUCCESS: Cleanup completed.\n";
} catch (\Exception $e) {
    $transactionPortal->rollBack();
    $transactionSmis->rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}
