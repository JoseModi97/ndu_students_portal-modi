<?php
/**
 * Step 4: Final Approve
 * Approves all remaining FSS refund workflow levels and marks the request approved.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Step 4: Finalizing all approvals for $regNo ---\n";

$request = (new \yii\db\Query())
    ->select(['r.request_id'])
    ->from('smisportal.fss_refund_requests r')
    ->innerJoin('smisportal.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id = r.student_prog_curriculum_id')
    ->where(['spc.registration_number' => $regNo])
    ->orderBy(['r.request_id' => SORT_DESC])
    ->one();

if (!$request) {
    die("ERROR: Refund request not found.\n");
}

$requestId = (int)$request['request_id'];

$approvedLevelIds = (new \yii\db\Query())
    ->select('a.approval_level_id')
    ->from('smisportal.fss_refund_approval_process p')
    ->innerJoin('smisportal.fss_refund_approvers a', 'a.approver_id = p.approver_id')
    ->where(['p.request_id' => $requestId, 'p.approval_status' => 'APPROVED'])
    ->column();

$remainingLevels = (new \yii\db\Query())
    ->from('smisportal."fss_refund_approval levels"')
    ->andFilterWhere(['not in', 'approval_level_id', $approvedLevelIds])
    ->orderBy(['approval_level_id' => SORT_ASC])
    ->all();

$transactionPortal = Yii::$app->db->beginTransaction();
$transactionSmis = Yii::$app->smisDb->beginTransaction();

try {
    foreach ($remainingLevels as $level) {
        $approver = (new \yii\db\Query())
            ->from('smisportal.fss_refund_approvers')
            ->where(['approval_level_id' => $level['approval_level_id'], 'approver_status' => 'ACTIVE'])
            ->one();

        if (!$approver) {
            $approverId = ((int)Yii::$app->db->createCommand('SELECT COALESCE(MAX(approver_id), 0) FROM smisportal.fss_refund_approvers')->queryScalar()) + 1;
            Yii::$app->db->createCommand()
                ->insert('smisportal.fss_refund_approvers', [
                    'approver_id' => $approverId,
                    'user_id' => 'AUTO-L' . $level['approval_level_id'],
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
                'request_id' => $requestId,
                'approval_status' => 'APPROVED',
                'remarks' => 'Final batch approval',
                'approval_date' => date('Y-m-d H:i:s'),
                'approver_id' => $approverId,
            ])
            ->execute();

        echo "Approved: " . $level['description'] . "\n";
    }

    Yii::$app->db->createCommand()
        ->update('smisportal.fss_refund_requests', [
            'refund_status' => 'APPROVED',
            'approval_status' => 'APPROVED',
            'amount_approved' => new \yii\db\Expression('amount_requested'),
        ], ['request_id' => $requestId])
        ->execute();

    Yii::$app->smisDb->createCommand()
        ->update('smis.fss_refund_requests', [
            'refund_status' => 'APPROVED',
            'approval_status' => 'APPROVED',
            'amount_approved' => new \yii\db\Expression('amount_requested'),
        ], ['request_id' => $requestId])
        ->execute();

    $transactionPortal->commit();
    $transactionSmis->commit();
    echo "SUCCESS: Process finalized and request $requestId set to APPROVED.\n";
} catch (\Throwable $e) {
    $transactionPortal->rollBack();
    $transactionSmis->rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}
