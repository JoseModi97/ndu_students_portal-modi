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

foreach ([[Yii::$app->db, 'smisportal', 'Portal'], [Yii::$app->smisDb, 'smis', 'SMIS']] as [$db, $schema, $label]) {
    $request = (new \yii\db\Query())
        ->select(['r.request_id', 'r.approval_status', 'r.refund_status', 'r.voucher_no', 'r.amount_requested', 'r.amount_approved', 'rt.refund_type_name'])
        ->from($schema . '.fss_refund_requests r')
        ->innerJoin($schema . '.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id = r.student_prog_curriculum_id')
        ->leftJoin($schema . '.fss_refund_types rt', 'rt.refund_type_id = r.refund_type')
        ->where(['spc.registration_number' => $regNo])
        ->orderBy(['r.request_id' => SORT_DESC])
        ->one($db);

    if ($request) {
        echo "\n{$label} Latest Request ID: {$request['request_id']}\n";
        echo "Refund Type: " . ($request['refund_type_name'] ?: 'UNKNOWN') . "\n";
        echo "Approval Status: {$request['approval_status']}\n";
        echo "Refund Status: {$request['refund_status']}\n";
        echo "Voucher No: " . ($request['voucher_no'] ?: 'NULL') . "\n";
        echo "Amount Requested: {$request['amount_requested']}\n";
        echo "Amount Approved: " . ($request['amount_approved'] ?: 'NULL') . "\n";

        if (!empty($request['voucher_no']) && $db->getTableSchema($schema . '.fss_refund_batches', true) !== null) {
            $batch = (new \yii\db\Query())
                ->select(['voucher_no', 'posted_by', 'posted_at', 'status', 'date_paid'])
                ->from($schema . '.fss_refund_batches')
                ->where(['voucher_no' => $request['voucher_no']])
                ->one($db);

            if ($batch) {
                echo "Batch Status: " . ($batch['status'] ?: 'NULL') . "\n";
                echo "Date Paid: " . ($batch['date_paid'] ?: 'NULL') . "\n";
            }
        }
    } else {
        echo "\n{$label} latest refund request: NOT FOUND\n";
    }
}
