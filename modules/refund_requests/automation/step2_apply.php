<?php
/**
 * Step 2: Submit Refund Application
 * Creates a valid fss_refund_requests application for NR605/0001/2022.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
$amount = 5000.00;
$paymentOption = $argv[1] ?? 'bank';

echo "--- Step 2: Submitting $paymentOption refund application for $regNo ---\n";

if (!in_array($paymentOption, ['bank', 'mpesa'], true)) {
    die("ERROR: Payment option must be 'bank' or 'mpesa'.\n");
}

$student = (new \yii\db\Query())
    ->select([
        'spc.student_prog_curriculum_id',
        'spc.registration_number',
        's.primary_email',
        's.primary_phone_no',
        's.passport_no',
        's.national_id',
        's.surname',
        's.other_names',
    ])
    ->from('smisportal.sm_student_programme_curriculum spc')
    ->leftJoin('smisportal.sm_admitted_student s', 's.adm_refno = spc.adm_refno')
    ->where(['spc.registration_number' => $regNo])
    ->one();

if (!$student) {
    die("ERROR: Student curriculum record not found.\n");
}

$refundTypeId = (int)(new \yii\db\Query())
    ->select('refund_type_id')
    ->from('smisportal.fss_refund_types')
    ->where(['refund_type_name' => 'STANDARD', 'refund_type_status' => true])
    ->scalar();

if (!$refundTypeId) {
    die("ERROR: STANDARD refund type not found.\n");
}

$bankId = null;
$branchId = null;
$accountNo = null;

if ($paymentOption === 'bank') {
    $bank = (new \yii\db\Query())
        ->from('smisportal.fss_banks')
        ->where(['status' => 1])
        ->orderBy(['brank_id' => SORT_ASC])
        ->one();

    if (!$bank) {
        die("ERROR: No active bank found for bank payment option.\n");
    }

    $branch = (new \yii\db\Query())
        ->from('smisportal.fss_bank_branches')
        ->where(['bank_code' => $bank['bank_code']])
        ->orderBy(['branch_id' => SORT_ASC])
        ->one();

    if (!$branch) {
        die("ERROR: No branch found for bank {$bank['bank_name']}.\n");
    }

    $bankId = (int)$bank['brank_id'];
    $branchId = (int)$branch['branch_id'];
    $accountNo = '1234567890';
}

$requestId = ((int)Yii::$app->db->createCommand('SELECT COALESCE(MAX(request_id), 0) FROM smisportal.fss_refund_requests')->queryScalar()) + 1;
$mobileNo = $student['primary_phone_no'] ?: '0700000000';
$email = $student['primary_email'] ?: 'student@example.test';
$passportId = $student['passport_no'] ?: ($student['national_id'] ?: 'N/A');
$accountName = trim(($student['surname'] ?? '') . ' ' . ($student['other_names'] ?? '')) ?: $regNo;

$row = [
    'request_id' => $requestId,
    'student_prog_curriculum_id' => $student['student_prog_curriculum_id'],
    'mobile_no' => $mobileNo,
    'email' => $email,
    'application_date' => date('Y-m-d H:i:s'),
    'refund_status' => 'PENDING',
    'account_no' => $accountNo,
    'account_name' => $accountName,
    'bank_id' => $bankId,
    'branch_id' => $branchId,
    'passport_id' => $passportId,
    'declaration_status' => '1',
    'amount_requested' => $amount,
    'approval_status' => 'PENDING',
    'refund_type' => $refundTypeId,
    'payment_method' => $paymentOption,
];

$transactionPortal = Yii::$app->db->beginTransaction();
$transactionSmis = Yii::$app->smisDb->beginTransaction();

try {
    Yii::$app->db->createCommand()
        ->insert('smisportal.fss_refund_requests', $row)
        ->execute();
    echo "Portal fss_refund_requests record created. Request ID: $requestId\n";

    Yii::$app->smisDb->createCommand()
        ->insert('smis.fss_refund_requests', $row)
        ->execute();
    echo "SMIS fss_refund_requests record created.\n";

    $transactionPortal->commit();
    $transactionSmis->commit();
    echo "SUCCESS: Application submitted.\n";
} catch (\Throwable $e) {
    $transactionPortal->rollBack();
    $transactionSmis->rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}
