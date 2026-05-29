<?php
/**
 * Step 3: Approve Level 1 (Dean of Students)
 * Simulates the first level of approval for NR605/0001/2022.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Step 3: Approving Level 1 (Dean) for $regNo ---\n";

$official = (new \yii\db\Query())
    ->select(['refund_official_id'])
    ->from('smis.sm_caution_refund_official')
    ->where(['registration_number' => $regNo])
    ->one(Yii::$app->smisDb);

if (!$official) {
    die("ERROR: Official record not found. Did you run Step 2?\n");
}

$level = (new \yii\db\Query())
    ->from('smis.sm_approval_level')
    ->where(['level_order' => 1])
    ->one(Yii::$app->smisDb);

if (!$level) {
    die("ERROR: Approval level 1 not found in smis.sm_approval_level.\n");
}

$transactionSmis = Yii::$app->smisDb->beginTransaction();

try {
    // Insert into approval process
    Yii::$app->smisDb->createCommand()
        ->insert('smis.sm_approval_process', [
            'refund_official_id' => $official['refund_official_id'],
            'level_id' => $level['level_id'],
            'status' => 'APPROVED',
            'approval_date' => date('Y-m-d H:i:s'),
            'comments' => 'Approved by automated script (Dean Level)'
        ])->execute();
    
    echo "Approval recorded for " . $level['level_name'] . ".\n";

    $transactionSmis->commit();
    echo "SUCCESS: Level 1 approval completed.\n";
} catch (\Exception $e) {
    $transactionSmis->rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}
