<?php
/**
 * Step 4: Final Approve (All remaining levels)
 * Iteratively approves all remaining levels for NR605/0001/2022 and sets final status.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Step 4: Finalizing all approvals for $regNo ---\n";

$official = (new \yii\db\Query())
    ->select(['refund_official_id'])
    ->from('smis.sm_caution_refund_official')
    ->where(['registration_number' => $regNo])
    ->one(Yii::$app->smisDb);

if (!$official) {
    die("ERROR: Official record not found.\n");
}

$officialId = $official['refund_official_id'];

// Get levels not yet approved
$existingApprovals = (new \yii\db\Query())
    ->select('level_id')
    ->from('smis.sm_approval_process')
    ->where(['refund_official_id' => $officialId])
    ->column(Yii::$app->smisDb);

$remainingLevels = (new \yii\db\Query())
    ->from('smis.sm_approval_level')
    ->where(['NOT IN', 'level_id', $existingApprovals])
    ->orderBy(['level_order' => SORT_ASC])
    ->all(Yii::$app->smisDb);

$transactionPortal = Yii::$app->db->beginTransaction();
$transactionSmis = Yii::$app->smisDb->beginTransaction();

try {
    foreach ($remainingLevels as $level) {
        Yii::$app->smisDb->createCommand()
            ->insert('smis.sm_approval_process', [
                'refund_official_id' => $officialId,
                'level_id' => $level['level_id'],
                'status' => 'APPROVED',
                'approval_date' => date('Y-m-d H:i:s'),
                'comments' => 'Final batch approval'
            ])->execute();
        echo "Approved: " . $level['level_name'] . "\n";
    }

    // Update final status in both tables
    Yii::$app->db->createCommand()
        ->update('smisportal.sm_caution_refund', ['status' => 'APPROVED'], ['registration_number' => $regNo])
        ->execute();
    
    Yii::$app->smisDb->createCommand()
        ->update('smis.sm_caution_refund_official', ['status' => 'APPROVED'], ['refund_official_id' => $officialId])
        ->execute();

    $transactionPortal->commit();
    $transactionSmis->commit();
    echo "SUCCESS: Process finalized and request set to APPROVED.\n";
} catch (\Exception $e) {
    $transactionPortal->rollBack();
    $transactionSmis->rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}
