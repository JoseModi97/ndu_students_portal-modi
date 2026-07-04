<?php
/**
 * Step 0: Cleanup for NR605/0001/2022
 * Deletes existing FSS refund records to allow a fresh start.
 */

$root = __DIR__;
while (!is_file($root . '/vendor/autoload.php')) {
    $parent = dirname($root);
    if ($parent === $root) {
        throw new RuntimeException('Could not locate project root from automation script.');
    }
    $root = $parent;
}

require $root . '/vendor/autoload.php';
require $root . '/vendor/yiisoft/yii2/Yii.php';

$config = require $root . '/config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Step 0: Cleaning up FSS refund records for $regNo ---\n";

$requestIds = requestIds(Yii::$app->db, 'smisportal', $regNo);
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
$portalVoucherNos = uniqueInts(array_merge(
    voucherNos(Yii::$app->db, 'smisportal', $requestIds),
    postingVoucherNos(Yii::$app->db, 'smisportal', $regNo),
    automationBatchVoucherNos(Yii::$app->db, 'smisportal'),
    orphanBatchVoucherNos(Yii::$app->db, 'smisportal')
));
$smisVoucherNos = uniqueInts(array_merge(
    voucherNos(Yii::$app->smisDb, 'smis', $allSmisRequestIds),
    postingVoucherNos(Yii::$app->smisDb, 'smis', $regNo),
    automationBatchVoucherNos(Yii::$app->smisDb, 'smis'),
    orphanBatchVoucherNos(Yii::$app->smisDb, 'smis')
));

$transactionPortal = Yii::$app->db->beginTransaction();
$transactionSmis = Yii::$app->smisDb->beginTransaction();

try {
    $deletedDisapproved = deleteDisapproved(Yii::$app->db, 'smisportal', $requestIds);
    $deletedSmisDisapproved = deleteDisapproved(Yii::$app->smisDb, 'smis', $allSmisRequestIds);
    $deletedApprovals = deleteApprovals(Yii::$app->db, 'smisportal', $requestIds);
    $deletedSmisApprovals = deleteApprovals(Yii::$app->smisDb, 'smis', $allSmisRequestIds);
    $deletedPortalFeeTransactions = deletePostingFeeTransactions(Yii::$app->db, 'smisportal', $portalVoucherNos, $regNo);
    $deletedSmisFeeTransactions = deletePostingFeeTransactions(Yii::$app->smisDb, 'smis', $smisVoucherNos, $regNo);
    $deletedPortalDuplicateCaution = deleteDuplicateCautionMoney(Yii::$app->db, 'smisportal', $regNo);
    $deletedDuplicateCaution = deleteDuplicateCautionMoney(Yii::$app->smisDb, 'smis', $regNo);

    $deletedPortal = $requestIds
        ? Yii::$app->db->createCommand()->delete('smisportal.fss_refund_requests', ['request_id' => $requestIds])->execute()
        : 0;

    $deletedSmis = $allSmisRequestIds
        ? Yii::$app->smisDb->createCommand()->delete('smis.fss_refund_requests', ['request_id' => $allSmisRequestIds])->execute()
        : 0;

    if ($studentProgCurriculumId) {
        $deletedSmis += Yii::$app->smisDb->createCommand()
            ->delete('smis.fss_refund_requests', ['student_prog_curriculum_id' => $studentProgCurriculumId])
            ->execute();
    }

    $deletedPortalCancelledVouchers = deleteCancelledVouchers(Yii::$app->db, 'smisportal', $portalVoucherNos);
    $deletedSmisCancelledVouchers = deleteCancelledVouchers(Yii::$app->smisDb, 'smis', $smisVoucherNos);

    $deletedPortalBatches = deleteRefundBatches(Yii::$app->db, 'smisportal', $portalVoucherNos);
    $deletedSmisBatches = deleteRefundBatches(Yii::$app->smisDb, 'smis', $smisVoucherNos);

    echo "Deleted $deletedDisapproved disapproved records from smisportal.fss_refund_requests_disapproved\n";
    echo "Deleted $deletedSmisDisapproved disapproved records from smis.fss_refund_requests_disapproved\n";
    echo "Deleted $deletedApprovals approval records from smisportal.fss_refund_approval_process\n";
    echo "Deleted $deletedSmisApprovals approval records from smis.fss_refund_approval_process\n";
    echo "Deleted $deletedPortalFeeTransactions posted fee transaction records from smisportal.fss_fee_transactions\n";
    echo "Deleted $deletedSmisFeeTransactions posted fee transaction records from smis.fss_fee_transactions\n";
    echo "Deleted {$deletedPortalDuplicateCaution['fee_transactions']} duplicate caution fee transaction records from smisportal.fss_fee_transactions\n";
    echo "Deleted {$deletedPortalDuplicateCaution['invoice_details']} duplicate caution invoice detail records from smisportal.fss_invoice_details\n";
    echo "Deleted {$deletedDuplicateCaution['fee_transactions']} duplicate caution fee transaction records from smis.fss_fee_transactions\n";
    echo "Deleted {$deletedDuplicateCaution['invoice_details']} duplicate caution invoice detail records from smis.fss_invoice_details\n";
    echo "Deleted $deletedPortal records from smisportal.fss_refund_requests\n";
    echo "Deleted $deletedSmis records from smis.fss_refund_requests\n";
    echo "Deleted $deletedPortalCancelledVouchers cancelled voucher records from smisportal.fss_cancelled_vouchers\n";
    echo "Deleted $deletedSmisCancelledVouchers cancelled voucher records from smis.fss_cancelled_vouchers\n";
    echo "Deleted $deletedPortalBatches refund batch records from smisportal.fss_refund_batches\n";
    echo "Deleted $deletedSmisBatches refund batch records from smis.fss_refund_batches\n";

    $transactionPortal->commit();
    $transactionSmis->commit();
    echo "SUCCESS: Cleanup completed.\n";
} catch (\Throwable $e) {
    $transactionPortal->rollBack();
    $transactionSmis->rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}

function requestIds(\yii\db\Connection $db, string $schema, string $regNo): array
{
    return (new \yii\db\Query())
        ->select('r.request_id')
        ->from($schema . '.fss_refund_requests r')
        ->innerJoin($schema . '.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id = r.student_prog_curriculum_id')
        ->where(['spc.registration_number' => $regNo])
        ->column($db);
}

function voucherNos(\yii\db\Connection $db, string $schema, array $requestIds): array
{
    if (!$requestIds) {
        return [];
    }

    return (new \yii\db\Query())
        ->select('voucher_no')
        ->from($schema . '.fss_refund_requests')
        ->where(['request_id' => $requestIds])
        ->andWhere(['not', ['voucher_no' => null]])
        ->column($db);
}

function postingVoucherNos(\yii\db\Connection $db, string $schema, string $regNo): array
{
    if ($db->getTableSchema($schema . '.fss_fee_transactions', true) === null) {
        return [];
    }

    $descriptions = (new \yii\db\Query())
        ->select('trans_desc')
        ->from($schema . '.fss_fee_transactions')
        ->where(['LIKE', 'progress_code', $regNo . '%', false])
        ->andWhere(['LIKE', 'trans_desc', 'CAUTION REFUND - ', false])
        ->column($db);

    $voucherNos = [];
    foreach ($descriptions as $description) {
        if (preg_match('/CAUTION REFUND -\s*(\d+)/i', (string)$description, $matches)) {
            $voucherNos[] = (int)$matches[1];
        }
    }

    return uniqueInts($voucherNos);
}

function automationBatchVoucherNos(\yii\db\Connection $db, string $schema): array
{
    if ($db->getTableSchema($schema . '.fss_refund_batches', true) === null) {
        return [];
    }

    return uniqueInts((new \yii\db\Query())
        ->select('voucher_no')
        ->from($schema . '.fss_refund_batches')
        ->where(['posted_by' => 'AUTO-POST'])
        ->column($db));
}

function orphanBatchVoucherNos(\yii\db\Connection $db, string $schema): array
{
    if (
        $db->getTableSchema($schema . '.fss_refund_batches', true) === null
        || $db->getTableSchema($schema . '.fss_refund_requests', true) === null
    ) {
        return [];
    }

    return uniqueInts((new \yii\db\Query())
        ->select('b.voucher_no')
        ->from($schema . '.fss_refund_batches b')
        ->leftJoin($schema . '.fss_refund_requests r', 'r.voucher_no = b.voucher_no')
        ->where(['r.voucher_no' => null])
        ->column($db));
}

function uniqueInts(array $values): array
{
    $ints = [];
    foreach ($values as $value) {
        if ($value === null || $value === '') {
            continue;
        }

        $ints[(int)$value] = (int)$value;
    }

    sort($ints);
    return array_values($ints);
}

function deleteDisapproved(\yii\db\Connection $db, string $schema, array $requestIds): int
{
    if (!$requestIds || $db->getTableSchema($schema . '.fss_refund_requests_disapproved', true) === null) {
        return 0;
    }

    return $db->createCommand()
        ->delete($schema . '.fss_refund_requests_disapproved', ['request_id' => $requestIds])
        ->execute();
}

function deleteApprovals(\yii\db\Connection $db, string $schema, array $requestIds): int
{
    if (!$requestIds) {
        return 0;
    }

    return $db->createCommand()
        ->delete($schema . '.fss_refund_approval_process', ['request_id' => $requestIds])
        ->execute();
}

function deletePostingFeeTransactions(\yii\db\Connection $db, string $schema, array $voucherNos, string $regNo): int
{
    if ($db->getTableSchema($schema . '.fss_fee_transactions', true) === null) {
        return 0;
    }

    $refundDescriptions = array_map(static fn(int|string $voucherNo): string => 'CAUTION REFUND - ' . $voucherNo, $voucherNos);
    $postingDescriptionCondition = [
        'or',
        [
            'and',
            ['user_id' => 'AUTO-POST'],
            ['trans_desc' => ['CAUTION MONEY', ' CAUTION MONEY']],
            ['not', ['trans_type' => 'DR']],
        ],
        ['LIKE', 'trans_desc', 'CAUTION REFUND%', false],
        ['LIKE', 'trans_desc', 'Caution Refund%', false],
        ['LIKE', 'trans_desc', 'Caution Money - Cancelled%', false],
    ];

    if ($refundDescriptions) {
        $postingDescriptionCondition[] = ['trans_desc' => $refundDescriptions];
    }

    return $db->createCommand()
        ->delete($schema . '.fss_fee_transactions', [
            'and',
            ['LIKE', 'progress_code', $regNo . '%', false],
            $postingDescriptionCondition,
        ])
        ->execute();
}

function deleteDuplicateCautionMoney(\yii\db\Connection $db, string $schema, string $regNo): array
{
    if ($db->getTableSchema($schema . '.fss_fee_transactions', true) === null) {
        return ['fee_transactions' => 0, 'invoice_details' => 0];
    }

    $studentFilter = studentProgressFilter($regNo);
    $entries = (new \yii\db\Query())
        ->select([
            'source' => new \yii\db\Expression("'fee_transaction'"),
            'trans_id' => 'ft.trans_id',
            'invoice_detail_id' => new \yii\db\Expression('NULL'),
            'trans_date' => 'ft.trans_date',
            'created_order' => 'ft.trans_id',
        ])
        ->from($schema . '.fss_fee_transactions ft')
        ->where(['ft.trans_type' => 'DR'])
        ->andWhere(new \yii\db\Expression('UPPER(TRIM(ft.trans_desc)) = :description', [
            ':description' => 'CAUTION MONEY',
        ]))
        ->andWhere($studentFilter)
        ->all($db);

    if (
        $db->getTableSchema($schema . '.fss_invoice', true) !== null
        && $db->getTableSchema($schema . '.fss_invoice_details', true) !== null
    ) {
        $entries = array_merge($entries, (new \yii\db\Query())
            ->select([
                'source' => new \yii\db\Expression("'invoice_detail'"),
                'trans_id' => 'ft.trans_id',
                'invoice_detail_id' => 'fid.invoice_detail_id',
                'trans_date' => 'fid.trans_date',
                'created_order' => 'fid.invoice_detail_id',
            ])
            ->from($schema . '.fss_invoice_details fid')
            ->innerJoin($schema . '.fss_invoice fi', 'fi.id = fid.invoice_id')
            ->innerJoin($schema . '.fss_fee_transactions ft', 'ft.trans_id = fi.trans_id')
            ->where(['ft.trans_type' => 'DR'])
            ->andWhere($studentFilter)
            ->andWhere(new \yii\db\Expression('UPPER(TRIM(ft.trans_desc)) <> :description'))
            ->andWhere(new \yii\db\Expression('UPPER(TRIM(fid.invoice_detail_desc)) = :description'))
            ->addParams([':description' => 'CAUTION MONEY'])
            ->all($db));
    }

    usort($entries, static function (array $a, array $b): int {
        $dateCompare = strcmp((string)$a['trans_date'], (string)$b['trans_date']);
        if ($dateCompare !== 0) {
            return $dateCompare;
        }

        return (int)$a['created_order'] <=> (int)$b['created_order'];
    });

    $duplicateEntries = array_slice($entries, 1);
    $duplicateTransactionIds = [];
    $duplicateInvoiceDetailIds = [];

    foreach ($duplicateEntries as $entry) {
        if ($entry['source'] === 'fee_transaction') {
            $duplicateTransactionIds[] = (int)$entry['trans_id'];
        } elseif ($entry['source'] === 'invoice_detail') {
            $duplicateInvoiceDetailIds[] = (int)$entry['invoice_detail_id'];
        }
    }

    $deletedFeeTransactions = $duplicateTransactionIds
        ? $db->createCommand()
            ->delete($schema . '.fss_fee_transactions', ['trans_id' => array_values(array_unique($duplicateTransactionIds))])
            ->execute()
        : 0;

    $deletedInvoiceDetails = $duplicateInvoiceDetailIds
        ? $db->createCommand()
            ->delete($schema . '.fss_invoice_details', ['invoice_detail_id' => array_values(array_unique($duplicateInvoiceDetailIds))])
            ->execute()
        : 0;

    return [
        'fee_transactions' => $deletedFeeTransactions,
        'invoice_details' => $deletedInvoiceDetails,
    ];
}

function studentProgressFilter(string $regNo): array
{
    $filter = ['or'];
    foreach (registrationNumberVariants($regNo) as $variant) {
        $filter[] = ['LIKE', 'ft.progress_code', $variant . '%', false];
    }

    if (count($filter) === 1) {
        $filter[] = ['ft.progress_code' => '__NO_REGISTRATION_NUMBER_MATCH__'];
    }

    return $filter;
}

function registrationNumberVariants(string $regNo): array
{
    $trimmed = trim($regNo);
    $variants = [
        $trimmed,
        str_replace('/', '-', $trimmed),
        str_replace('-', '/', $trimmed),
    ];

    return array_values(array_unique(array_filter($variants, static fn(string $variant): bool => $variant !== '')));
}

function deleteRefundBatches(\yii\db\Connection $db, string $schema, array $voucherNos): int
{
    if (!$voucherNos || $db->getTableSchema($schema . '.fss_refund_batches', true) === null) {
        return 0;
    }

    return $db->createCommand()
        ->delete($schema . '.fss_refund_batches', ['voucher_no' => $voucherNos])
        ->execute();
}

function deleteCancelledVouchers(\yii\db\Connection $db, string $schema, array $voucherNos): int
{
    if (!$voucherNos || $db->getTableSchema($schema . '.fss_cancelled_vouchers', true) === null) {
        return 0;
    }

    return $db->createCommand()
        ->delete($schema . '.fss_cancelled_vouchers', ['voucher_no' => $voucherNos])
        ->execute();
}
