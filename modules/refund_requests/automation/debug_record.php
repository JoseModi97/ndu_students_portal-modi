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

    foreach ([[Yii::$app->db, 'smisportal'], [Yii::$app->smisDb, 'smis']] as [$db, $schema]) {
        $schemaRecord = (new \yii\db\Query())
            ->from($schema . '.fss_refund_requests')
            ->where(['request_id' => $record['request_id']])
            ->one($db);

        if ($schemaRecord && !empty($schemaRecord['voucher_no']) && $db->getTableSchema($schema . '.fss_refund_batches', true) !== null) {
            $batch = (new \yii\db\Query())
                ->from($schema . '.fss_refund_batches')
                ->where(['voucher_no' => $schemaRecord['voucher_no']])
                ->one($db);

            echo "\n{$schema}.fss_refund_batches row:\n";
            print_r($batch ?: []);
            if ($batch) {
                echo "{$schema}.fss_refund_batches payment status: "
                    . ($batch['status'] ?: 'NULL')
                    . ', date_paid='
                    . ($batch['date_paid'] ?: 'NULL')
                    . "\n";
            }
        } else {
            echo "\n{$schema}.fss_refund_batches: no voucher for this request\n";
        }

        if ($schema === 'smis' && !empty($schemaRecord['voucher_no'])) {
            $postingTransactions = (new \yii\db\Query())
                ->from($schema . '.fss_fee_transactions')
                ->where(['trans_desc' => [' CAUTION MONEY', 'CAUTION REFUND - ' . $schemaRecord['voucher_no']]])
                ->orderBy(['trans_id' => SORT_DESC])
                ->all($db);

            echo "\n{$schema}.fss_fee_transactions posting rows: " . count($postingTransactions) . "\n";
            if ($postingTransactions) {
                print_r($postingTransactions);
            }
        }

        if ($db->getTableSchema($schema . '.fss_cancelled_vouchers', true) !== null) {
            $cancelledQuery = (new \yii\db\Query())
                ->from($schema . '.fss_cancelled_vouchers')
                ->orderBy(['cancelled_vid' => SORT_DESC]);
            $cancelledTable = $db->getTableSchema($schema . '.fss_cancelled_vouchers', true);
            if ($cancelledTable !== null && in_array('request_id', $cancelledTable->columnNames, true)) {
                $cancelledQuery->where(['request_id' => $record['request_id']]);
            } elseif (!empty($schemaRecord['voucher_no'])) {
                $cancelledQuery->where(['voucher_no' => $schemaRecord['voucher_no']]);
            } else {
                $cancelledQuery->where('1=0');
            }

            $cancelledRows = $cancelledQuery->all($db);
            echo "\n{$schema}.fss_cancelled_vouchers rows: " . count($cancelledRows) . "\n";
            if ($cancelledRows) {
                print_r($cancelledRows);
            }
        }

        if ($db->getTableSchema($schema . '.fss_refund_requests_disapproved', true) === null) {
            echo "\n{$schema}.fss_refund_requests_disapproved: table not found\n";
            continue;
        }

        $disapprovedRows = (new \yii\db\Query())
            ->select(['d.*', 'a.user_id', 'a.approval_level_id'])
            ->from($schema . '.fss_refund_requests_disapproved d')
            ->leftJoin($schema . '.fss_refund_approvers a', 'a.approver_id = d.approver_id')
            ->where(['d.request_id' => $record['request_id']])
            ->orderBy(['d.approval_date' => SORT_DESC, 'd.disapproved_refund_id' => SORT_DESC])
            ->all($db);

        echo "\n{$schema}.fss_refund_requests_disapproved rows: " . count($disapprovedRows) . "\n";
        if ($disapprovedRows) {
            print_r($disapprovedRows);
        }
    }
}
