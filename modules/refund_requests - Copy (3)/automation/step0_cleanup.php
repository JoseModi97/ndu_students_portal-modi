<?php
/**
 * Step 0: Cleanup for NR605/0001/2022
 * Deletes existing FSS refund records to allow a fresh start.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Step 0: Cleaning up FSS refund records for $regNo ---\n";

$requestIds = (new \yii\db\Query())
    ->select('r.request_id')
    ->from('smisportal.fss_refund_requests r')
    ->innerJoin('smisportal.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id = r.student_prog_curriculum_id')
    ->where(['spc.registration_number' => $regNo])
    ->column();

$studentProgCurriculumId = (new \yii\db\Query())
    ->select('student_prog_curriculum_id')
    ->from('smisportal.sm_student_programme_curriculum')
    ->where(['registration_number' => $regNo])
    ->scalar();

$smisRequestIds = [];
if ($studentProgCurriculumId) {
    $smisRequestIds = (new \yii\db\Query())
        ->select('request_id')
        ->from('smis.fss_refund_requests')
        ->where(['student_prog_curriculum_id' => $studentProgCurriculumId])
        ->column(Yii::$app->smisDb);
}

$allSmisRequestIds = array_values(array_unique(array_merge($requestIds, $smisRequestIds)));

$transactionPortal = Yii::$app->db->beginTransaction();
$transactionSmis = Yii::$app->smisDb->beginTransaction();

try {
    $deletedApprovals = 0;
    $deletedSmisApprovals = 0;
    $deletedDisapproved = 0;
    $deletedSmisDisapproved = 0;
    $deletedPortal = 0;
    $deletedSmis = 0;

    if ($requestIds) {
        if (Yii::$app->db->getTableSchema('smisportal.fss_refund_requests_disapproved', true) !== null) {
            $deletedDisapproved = Yii::$app->db->createCommand()
                ->delete('smisportal.fss_refund_requests_disapproved', ['request_id' => $requestIds])
                ->execute();
        }

        $deletedApprovals = Yii::$app->db->createCommand()
            ->delete('smisportal.fss_refund_approval_process', ['request_id' => $requestIds])
            ->execute();

        $deletedPortal = Yii::$app->db->createCommand()
            ->delete('smisportal.fss_refund_requests', ['request_id' => $requestIds])
            ->execute();
    }

    if ($allSmisRequestIds) {
        if (Yii::$app->smisDb->getTableSchema('smis.fss_refund_requests_disapproved', true) !== null) {
            $deletedSmisDisapproved = Yii::$app->smisDb->createCommand()
                ->delete('smis.fss_refund_requests_disapproved', ['request_id' => $allSmisRequestIds])
                ->execute();
        }

        $deletedSmisApprovals = Yii::$app->smisDb->createCommand()
            ->delete('smis.fss_refund_approval_process', ['request_id' => $allSmisRequestIds])
            ->execute();

        $deletedSmis += Yii::$app->smisDb->createCommand()
            ->delete('smis.fss_refund_requests', ['request_id' => $allSmisRequestIds])
            ->execute();
    }

    if ($studentProgCurriculumId) {
        $deletedSmis += Yii::$app->smisDb->createCommand()
            ->delete('smis.fss_refund_requests', ['student_prog_curriculum_id' => $studentProgCurriculumId])
            ->execute();
    }

    echo "Deleted $deletedDisapproved disapproved records from smisportal.fss_refund_requests_disapproved\n";
    echo "Deleted $deletedSmisDisapproved disapproved records from smis.fss_refund_requests_disapproved\n";
    echo "Deleted $deletedApprovals approval records from smisportal.fss_refund_approval_process\n";
    echo "Deleted $deletedSmisApprovals approval records from smis.fss_refund_approval_process\n";
    echo "Deleted $deletedPortal records from smisportal.fss_refund_requests\n";
    echo "Deleted $deletedSmis records from smis.fss_refund_requests\n";

    $transactionPortal->commit();
    $transactionSmis->commit();
    echo "SUCCESS: Cleanup completed.\n";
} catch (\Throwable $e) {
    $transactionPortal->rollBack();
    $transactionSmis->rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}
