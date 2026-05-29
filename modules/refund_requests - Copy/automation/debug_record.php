<?php
require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
$record = (new \yii\db\Query())
    ->from('smisportal.sm_caution_refund')
    ->where(['registration_number' => $regNo])
    ->one();

$student = (new \yii\db\Query())
    ->select(['adm_refno'])
    ->from('smisportal.sm_student_programme_curriculum')
    ->where(['registration_number' => $regNo])
    ->one();

echo "Target Reg No: $regNo\n";
echo "Student adm_refno: " . ($student['adm_refno'] ?? 'NOT FOUND') . "\n";
echo "Application Record Found: " . ($record ? 'YES' : 'NO') . "\n";
if ($record) {
    print_r($record);
}
