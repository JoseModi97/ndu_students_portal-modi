<?php
require 'vendor/autoload.php';
require 'vendor/yiisoft/yii2/Yii.php';
$config = require 'config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';

$requests = (new \yii\db\Query())
    ->select('r.*, spc.registration_number')
    ->from('smisportal.fss_refund_requests r')
    ->innerJoin('smisportal.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id = r.student_prog_curriculum_id')
    ->where(['spc.registration_number' => $regNo])
    ->all();

echo "Portal Requests:\n" . json_encode($requests, JSON_PRETTY_PRINT) . "\n";

$smisRequests = (new \yii\db\Query())
    ->select('r.*, spc.registration_number')
    ->from('smis.fss_refund_requests r')
    ->innerJoin('smis.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id = r.student_prog_curriculum_id')
    ->where(['spc.registration_number' => $regNo])
    ->all(Yii::$app->smisDb);

echo "SMIS Requests:\n" . json_encode($smisRequests, JSON_PRETTY_PRINT) . "\n";
