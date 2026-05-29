<?php
require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Real Data Check for $regNo ---\n";

// 1. Fee Balance
$transactions = (new \yii\db\Query())
    ->from('smisportal.fss_fee_transactions')
    ->where(['LIKE', 'progress_code', $regNo . '%', false])
    ->all();

$credits = 0;
$debits = 0;
foreach ($transactions as $t) {
    if ($t['trans_type'] === 'CR') $credits += $t['trans_amount'];
    if ($t['trans_type'] === 'DR') $debits += $t['trans_amount'];
}
$balance = $debits - $credits;

echo "Fee Transactions Found: " . count($transactions) . "\n";
echo "Current Balance: " . Yii::$app->formatter->asCurrency($balance) . "\n";

// 2. Academic Status
$status = (new \yii\db\Query())
    ->select(['s.status', 'spc.status_id'])
    ->from('smisportal.sm_student_programme_curriculum spc')
    ->leftJoin('smisportal.sm_student_status s', 'spc.status_id = s.status_id')
    ->where(['spc.registration_number' => $regNo])
    ->one();

echo "Academic Status from DB: " . ($status['status'] ?? 'NOT FOUND') . "\n";
echo "Clearance Status: " . (new \yii\db\Query())
    ->select('clearance_status')
    ->from('smisportal.sm_admitted_student s')
    ->innerJoin('smisportal.sm_student_programme_curriculum spc', 's.adm_refno = spc.adm_refno')
    ->where(['spc.registration_number' => $regNo])
    ->scalar() . "\n";
