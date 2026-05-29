<?php
require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';

$record = (new \yii\db\Query())
    ->select(['r.*', 'b.bank_name', 'bb.branch_name', 'rt.refund_type_name'])
    ->from('smisportal.fss_refund_requests r')
    ->innerJoin('smisportal.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id = r.student_prog_curriculum_id')
    ->leftJoin('smisportal.fss_banks b', 'b.brank_id = r.bank_id')
    ->leftJoin('smisportal.fss_bank_branches bb', 'bb.branch_id = r.branch_id')
    ->leftJoin('smisportal.fss_refund_types rt', 'rt.refund_type_id = r.refund_type')
    ->where(['spc.registration_number' => $regNo])
    ->orderBy(['r.request_id' => SORT_DESC])
    ->one();

$student = (new \yii\db\Query())
    ->select(['student_prog_curriculum_id'])
    ->from('smisportal.sm_student_programme_curriculum')
    ->where(['registration_number' => $regNo])
    ->one();

echo "Target Reg No: $regNo\n";
echo "Student curriculum ID: " . ($student['student_prog_curriculum_id'] ?? 'NOT FOUND') . "\n";
echo "Application Record Found: " . ($record ? 'YES' : 'NO') . "\n";

if ($record) {
    print_r($record);
}
