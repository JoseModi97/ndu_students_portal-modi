<?php
/**
 * Step 3: Approve Level 1
 * Records the first approval for the current FSS refund request.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Step 3: Approving Level 1 for $regNo ---\n";

$request = (new \yii\db\Query())
    ->select(['r.request_id'])
    ->from('smisportal.fss_refund_requests r')
    ->innerJoin('smisportal.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id = r.student_prog_curriculum_id')
    ->where(['spc.registration_number' => $regNo])
    ->orderBy(['r.request_id' => SORT_DESC])
    ->one();

if (!$request) {
    die("ERROR: Refund request not found. Did you run Step 2?\n");
}

$level = (new \yii\db\Query())
    ->from('smisportal."fss_refund_approval levels"')
    ->where(['approval_level_id' => 1])
    ->one();

if (!$level) {
    die("ERROR: Approval level 1 not found.\n");
}

$transactionPortal = Yii::$app->db->beginTransaction();

try {
    $approver = (new \yii\db\Query())
        ->from('smisportal.fss_refund_approvers')
        ->where(['approval_level_id' => $level['approval_level_id'], 'approver_status' => 'ACTIVE'])
        ->one();

    if (!$approver) {
        $approverId = ((int)Yii::$app->db->createCommand('SELECT COALESCE(MAX(approver_id), 0) FROM smisportal.fss_refund_approvers')->queryScalar()) + 1;
        Yii::$app->db->createCommand()
            ->insert('smisportal.fss_refund_approvers', [
                'approver_id' => $approverId,
                'user_id' => 'AUTO-L1',
                'approval_level_id' => $level['approval_level_id'],
                'approver_status' => 'ACTIVE',
            ])
            ->execute();
    } else {
        $approverId = (int)$approver['approver_id'];
    }

    $approvalProcessId = ((int)Yii::$app->db->createCommand('SELECT COALESCE(MAX(approval_process_id), 0) FROM smisportal.fss_refund_approval_process')->queryScalar()) + 1;

    Yii::$app->db->createCommand()
        ->insert('smisportal.fss_refund_approval_process', [
            'approval_process_id' => $approvalProcessId,
            'request_id' => $request['request_id'],
            'approval_status' => 'APPROVED',
            'remarks' => 'Approved by automated script (Level 1)',
            'approval_date' => date('Y-m-d H:i:s'),
            'approver_id' => $approverId,
        ])
        ->execute();

    $transactionPortal->commit();
    echo "SUCCESS: Level 1 approval recorded for request {$request['request_id']}.\n";
} catch (\Throwable $e) {
    $transactionPortal->rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}
