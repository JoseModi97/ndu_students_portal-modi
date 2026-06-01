<?php
/**
 * Step 4: Final Approve
 * Approves only the final FSS refund workflow level after previous levels are approved.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Step 4: Final approval for $regNo ---\n";

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

$levels = (new \yii\db\Query())
    ->from('smisportal."fss_refund_approval levels"')
    ->orderBy(['approval_level_id' => SORT_ASC])
    ->all();

if (!$levels) {
    die("ERROR: No approval levels configured.\n");
}

$finalLevel = end($levels);
$finalLevelId = (int)$finalLevel['approval_level_id'];
$previousLevels = array_filter($levels, static function (array $level) use ($finalLevelId): bool {
    return (int)$level['approval_level_id'] < $finalLevelId;
});

$decision = promptDecision((int)$finalLevelId);
$remarks = $decision === 'REJECTED'
    ? promptComment()
    : 'Final level approval';

$transactionPortal = Yii::$app->db->beginTransaction();
$transactionSmis = Yii::$app->smisDb->beginTransaction();

try {
    ensureSmisRequest($requestId);

    ensurePreviousLevelsApproved(Yii::$app->db, 'smisportal', $requestId, $previousLevels);
    ensurePreviousLevelsApproved(Yii::$app->smisDb, 'smis', $requestId, $previousLevels);

    insertDecision(Yii::$app->db, 'smisportal', $requestId, $finalLevelId, $decision, $remarks);
    insertDecision(Yii::$app->smisDb, 'smis', $requestId, $finalLevelId, $decision, $remarks);

    if ($decision === 'APPROVED') {
        markRequestApproved(Yii::$app->db, 'smisportal', $requestId);
        markRequestApproved(Yii::$app->smisDb, 'smis', $requestId);
    } else {
        markRequestRejected(Yii::$app->db, 'smisportal', $requestId);
        markRequestRejected(Yii::$app->smisDb, 'smis', $requestId);
    }

    $transactionPortal->commit();
    $transactionSmis->commit();
    echo "Recorded final level {$decision}: " . $finalLevel['description'] . "\n";
    echo "SUCCESS: Request $requestId set to {$decision} in Portal and SMIS.\n";
} catch (\Throwable $e) {
    $transactionPortal->rollBack();
    $transactionSmis->rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}

function promptDecision(int $levelId): string
{
    while (true) {
        echo "Decision for Level {$levelId}? Type 'approve' or 'reject': ";
        $answer = strtolower(trim((string)fgets(STDIN)));

        if (in_array($answer, ['approve', 'approved'], true)) {
            return 'APPROVED';
        }

        if (in_array($answer, ['reject', 'rejected'], true)) {
            return 'REJECTED';
        }

        echo "Invalid decision. Please type approve or reject.\n";
    }
}

function promptComment(): string
{
    while (true) {
        echo "Enter rejection comment: ";
        $comment = trim((string)fgets(STDIN));

        if ($comment !== '') {
            return substr($comment, 0, 150);
        }

        echo "A rejection comment is required.\n";
    }
}

function ensurePreviousLevelsApproved(\yii\db\Connection $db, string $schema, int $requestId, array $previousLevels): void
{
    foreach ($previousLevels as $level) {
        $levelId = (int)$level['approval_level_id'];

        $approved = (new \yii\db\Query())
            ->from($schema . '.fss_refund_approval_process p')
            ->innerJoin($schema . '.fss_refund_approvers a', 'a.approver_id = p.approver_id')
            ->where([
                'p.request_id' => $requestId,
                'a.approval_level_id' => $levelId,
                'p.approval_status' => 'APPROVED',
            ])
            ->exists($db);

        if (!$approved) {
            throw new \RuntimeException("Cannot finalize request {$requestId}: level {$levelId} approval is missing in {$schema}.");
        }
    }
}

function ensureSmisRequest(int $requestId): void
{
    $exists = (new \yii\db\Query())
        ->from('smis.fss_refund_requests')
        ->where(['request_id' => $requestId])
        ->exists(Yii::$app->smisDb);

    if ($exists) {
        return;
    }

    $portalRequest = (new \yii\db\Query())
        ->from('smisportal.fss_refund_requests')
        ->where(['request_id' => $requestId])
        ->one(Yii::$app->db);

    if (!$portalRequest) {
        throw new \RuntimeException("Portal request {$requestId} not found.");
    }

    unset($portalRequest['payment_method'], $portalRequest['sync_status'], $portalRequest['sync_error'], $portalRequest['last_synced_at']);

    Yii::$app->smisDb->createCommand()
        ->insert('smis.fss_refund_requests', $portalRequest)
        ->execute();
}

function insertDecision(\yii\db\Connection $db, string $schema, int $requestId, int $levelId, string $decision, string $remarks): void
{
    $approver = (new \yii\db\Query())
        ->from($schema . '.fss_refund_approvers')
        ->where(['approval_level_id' => $levelId, 'approver_status' => 'ACTIVE'])
        ->one($db);

    if (!$approver) {
        $approverId = ((int)$db->createCommand("SELECT COALESCE(MAX(approver_id), 0) FROM {$schema}.fss_refund_approvers")->queryScalar()) + 1;
        $db->createCommand()
            ->insert($schema . '.fss_refund_approvers', [
                'approver_id' => $approverId,
                'user_id' => 'AUTO-L' . $levelId,
                'approval_level_id' => $levelId,
                'approver_status' => 'ACTIVE',
            ])
            ->execute();
    } else {
        $approverId = (int)$approver['approver_id'];
    }

    $exists = (new \yii\db\Query())
        ->from($schema . '.fss_refund_approval_process p')
        ->innerJoin($schema . '.fss_refund_approvers a', 'a.approver_id = p.approver_id')
        ->where([
            'p.request_id' => $requestId,
            'a.approval_level_id' => $levelId,
        ])
        ->exists($db);

    if ($exists) {
        throw new \RuntimeException("Request {$requestId} already has a level {$levelId} decision in {$schema}.");
    }

    $approvalProcessId = ((int)$db->createCommand("SELECT COALESCE(MAX(approval_process_id), 0) FROM {$schema}.fss_refund_approval_process")->queryScalar()) + 1;

    $db->createCommand()
        ->insert($schema . '.fss_refund_approval_process', [
            'approval_process_id' => $approvalProcessId,
            'request_id' => $requestId,
            'approval_status' => $decision,
            'remarks' => $remarks,
            'approval_date' => date('Y-m-d H:i:s'),
            'approver_id' => $approverId,
        ])
        ->execute();
}

function markRequestApproved(\yii\db\Connection $db, string $schema, int $requestId): void
{
    $attributes = [
        'approval_status' => 'APPROVED',
        'amount_approved' => new \yii\db\Expression('amount_requested'),
    ];

    if ($schema === 'smisportal') {
        $attributes['sync_status'] = 0;
    }

    $db->createCommand()
        ->update($schema . '.fss_refund_requests', $attributes, ['request_id' => $requestId])
        ->execute();
}

function markRequestRejected(\yii\db\Connection $db, string $schema, int $requestId): void
{
    $attributes = ['approval_status' => 'REJECTED'];
    if ($schema === 'smisportal') {
        $attributes['sync_status'] = 0;
    }

    $db->createCommand()
        ->update($schema . '.fss_refund_requests', $attributes, ['request_id' => $requestId])
        ->execute();
}
