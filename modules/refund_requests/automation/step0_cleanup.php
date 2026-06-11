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
$portalVoucherNos = $requestIds ? (new \yii\db\Query())
    ->select('voucher_no')
    ->from('smisportal.fss_refund_requests')
    ->where(['request_id' => $requestIds])
    ->andWhere(['not', ['voucher_no' => null]])
    ->column(Yii::$app->db) : [];
$smisVoucherNos = $allSmisRequestIds ? (new \yii\db\Query())
    ->select('voucher_no')
    ->from('smis.fss_refund_requests')
    ->where(['request_id' => $allSmisRequestIds])
    ->andWhere(['not', ['voucher_no' => null]])
    ->column(Yii::$app->smisDb) : [];

$transactionPortal = Yii::$app->db->beginTransaction();
$transactionSmis = Yii::$app->smisDb->beginTransaction();

try {
    $deletedApprovals = 0;
    $deletedSmisApprovals = 0;
    $deletedDisapproved = 0;
    $deletedSmisDisapproved = 0;
    $deletedPortalPostingItems = 0;
    $deletedSmisPostingItems = 0;
    $deletedPortalPostingBatches = 0;
    $deletedSmisPostingBatches = 0;
    $deletedPortalRefundDetails = 0;
    $deletedSmisRefundDetails = 0;
    $deletedPortalFeeTransactions = 0;
    $deletedSmisFeeTransactions = 0;
    $deletedPortal = 0;
    $deletedSmis = 0;

    if ($requestIds) {
        $portalTransIds = postingTransactionIds(Yii::$app->db, 'smisportal', $requestIds);
        if (Yii::$app->db->getTableSchema('smisportal.fss_refund_posting_items', true) !== null) {
            $deletedPortalPostingItems = Yii::$app->db->createCommand()
                ->delete('smisportal.fss_refund_posting_items', ['request_id' => $requestIds])
                ->execute();
        }
        if ($portalTransIds) {
            $deletedPortalFeeTransactions = Yii::$app->db->createCommand()
                ->delete('smisportal.fss_fee_transactions', ['trans_id' => $portalTransIds])
                ->execute();
        }
        if ($portalVoucherNos && Yii::$app->db->getTableSchema('smisportal.fss_refund_posting_batches', true) !== null) {
            $deletedPortalPostingBatches = Yii::$app->db->createCommand()
                ->delete('smisportal.fss_refund_posting_batches', ['voucher_no' => $portalVoucherNos])
                ->execute();
        }
        if ($portalVoucherNos && Yii::$app->db->getTableSchema('smisportal.fss_refund_details', true) !== null) {
            $deletedPortalRefundDetails = Yii::$app->db->createCommand()
                ->delete('smisportal.fss_refund_details', ['pv_no' => $portalVoucherNos])
                ->execute();
        }

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
        $smisTransIds = postingTransactionIds(Yii::$app->smisDb, 'smis', $allSmisRequestIds);
        if (Yii::$app->smisDb->getTableSchema('smis.fss_refund_posting_items', true) !== null) {
            $deletedSmisPostingItems = Yii::$app->smisDb->createCommand()
                ->delete('smis.fss_refund_posting_items', ['request_id' => $allSmisRequestIds])
                ->execute();
        }
        if ($smisTransIds) {
            $deletedSmisFeeTransactions = Yii::$app->smisDb->createCommand()
                ->delete('smis.fss_fee_transactions', ['trans_id' => $smisTransIds])
                ->execute();
        }
        if ($smisVoucherNos && Yii::$app->smisDb->getTableSchema('smis.fss_refund_posting_batches', true) !== null) {
            $deletedSmisPostingBatches = Yii::$app->smisDb->createCommand()
                ->delete('smis.fss_refund_posting_batches', ['voucher_no' => $smisVoucherNos])
                ->execute();
        }
        if ($smisVoucherNos && Yii::$app->smisDb->getTableSchema('smis.fss_refund_details', true) !== null) {
            $deletedSmisRefundDetails = Yii::$app->smisDb->createCommand()
                ->delete('smis.fss_refund_details', ['pv_no' => $smisVoucherNos])
                ->execute();
        }

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
    echo "Deleted $deletedPortalPostingItems posting item records from smisportal.fss_refund_posting_items\n";
    echo "Deleted $deletedSmisPostingItems posting item records from smis.fss_refund_posting_items\n";
    echo "Deleted $deletedPortalPostingBatches posting batch records from smisportal.fss_refund_posting_batches\n";
    echo "Deleted $deletedSmisPostingBatches posting batch records from smis.fss_refund_posting_batches\n";
    echo "Deleted $deletedPortalRefundDetails refund detail records from smisportal.fss_refund_details\n";
    echo "Deleted $deletedSmisRefundDetails refund detail records from smis.fss_refund_details\n";
    echo "Deleted $deletedPortalFeeTransactions posted fee transaction records from smisportal.fss_fee_transactions\n";
    echo "Deleted $deletedSmisFeeTransactions posted fee transaction records from smis.fss_fee_transactions\n";
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

function postingTransactionIds(\yii\db\Connection $db, string $schema, array $requestIds): array
{
    if ($db->getTableSchema($schema . '.fss_refund_posting_items', true) === null) {
        return [];
    }

    $rows = (new \yii\db\Query())
        ->select(['debit_trans_id', 'credit_trans_id'])
        ->from($schema . '.fss_refund_posting_items')
        ->where(['request_id' => $requestIds])
        ->all($db);

    $ids = [];
    foreach ($rows as $row) {
        foreach (['debit_trans_id', 'credit_trans_id'] as $column) {
            if (!empty($row[$column])) {
                $ids[] = (int)$row[$column];
            }
        }
    }

    return array_values(array_unique($ids));
}
