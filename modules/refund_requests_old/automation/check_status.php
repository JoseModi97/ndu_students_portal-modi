<?php
require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
$student = (new \yii\db\Query())
    ->select(['s.clearance_status', 's.surname', 's.other_names'])
    ->from('smisportal.sm_admitted_student s')
    ->innerJoin('smisportal.sm_student_programme_curriculum spc', 's.adm_refno = spc.adm_refno')
    ->where(['spc.registration_number' => $regNo])
    ->one();

if ($student) {
    echo "Student: " . $student['surname'] . " " . $student['other_names'] . "\n";
    echo "Clearance Status: " . ($student['clearance_status'] ?: 'NULL (Not Cleared)') . "\n";
} else {
    echo "Student not found.\n";
}
