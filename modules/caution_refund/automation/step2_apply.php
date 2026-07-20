<?php
/**
 * Step 2: Submit Application
 * Simulates a student submitting the caution refund form for NR605/0001/2022.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Step 2: Submitting application for $regNo ---\n";

$student = (new \yii\db\Query())
    ->select(['spc.adm_refno', 'spc.registration_number'])
    ->from('smisportal.sm_student_programme_curriculum spc')
    ->where(['spc.registration_number' => $regNo])
    ->one();

if (!$student) {
    die("ERROR: Student not found.\n");
}

$transactionPortal = Yii::$app->db->beginTransaction();
$transactionSmis = Yii::$app->smisDb->beginTransaction();

try {
    // 1. Insert into smisportal.sm_caution_refund
    Yii::$app->db->createCommand()
        ->insert('smisportal.sm_caution_refund', [
            'student_id' => $student['adm_refno'],
            'registration_number' => $regNo,
            'refund_type' => 'STANDARD',
            'refund_amount' => 5000.00,
            'status' => 'PENDING',
            'application_date' => date('Y-m-d H:i:s'),
            'remarks' => 'Automated test application'
        ])->execute();
    echo "Local portal request created.\n";

    // 2. Insert into smis.sm_caution_refund_official
    Yii::$app->smisDb->createCommand()
        ->insert('smis.sm_caution_refund_official', [
            'registration_number' => $regNo,
            'amount' => 5000.00,
            'status' => 'PENDING'
        ])->execute();
    echo "Official SMIS record created.\n";

    $transactionPortal->commit();
    $transactionSmis->commit();
    echo "SUCCESS: Application submitted.\n";
} catch (\Exception $e) {
    $transactionPortal->rollBack();
    $transactionSmis->rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}
