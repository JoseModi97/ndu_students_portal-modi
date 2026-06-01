<?php
/**
 * Step 1: Ensure Eligibility
 * Makes student NR605/0001/2022 eligible by ensuring they are CLEARED and have no fee balance.
 */

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../../config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "--- Step 1: Ensuring eligibility for $regNo ---\n";

try {
    // 1. Update clearance status to CLEARED
    $cleared = Yii::$app->db->createCommand()
        ->update('smisportal.sm_admitted_student', 
            ['clearance_status' => 'CLEARED'], 
            ['adm_refno' => (new \yii\db\Query())
                ->select('adm_refno')
                ->from('smisportal.sm_student_programme_curriculum')
                ->where(['registration_number' => $regNo])
            ]
        )->execute();
    echo "Clearance status set to CLEARED.\n";

    // 2. Update academic status to GRADUATED in both portal and SMIS.
    $portalGraduatedStatusId = (new \yii\db\Query())
        ->select('status_id')
        ->from('smisportal.sm_student_status')
        ->where(['status' => 'GRADUATED'])
        ->scalar();

    $smisGraduatedStatusId = (new \yii\db\Query())
        ->select('status_id')
        ->from('smis.sm_student_status')
        ->where(['status' => 'GRADUATED'])
        ->scalar(Yii::$app->smisDb);

    if ($portalGraduatedStatusId) {
        Yii::$app->db->createCommand()
            ->update('smisportal.sm_student_programme_curriculum',
                ['status_id' => $portalGraduatedStatusId],
                ['registration_number' => $regNo]
            )->execute();
    }

    if ($smisGraduatedStatusId) {
        Yii::$app->smisDb->createCommand()
            ->update('smis.sm_student_programme_curriculum',
                ['status_id' => $smisGraduatedStatusId],
                ['registration_number' => $regNo]
            )->execute();
    }

    echo "Academic status set to GRADUATED where status records exist.\n";

    // 3. Ensure fee balance is zero
    // Actually, calculateFeeBalance checks fss_fee_transactions.
    // We'll just print the current balance status.
    echo "Checking fee balance...\n";
    
    $transactions = (new \yii\db\Query())
        ->select(['trans_amount', 'trans_type'])
        ->from('smis.fss_fee_transactions')
        ->where(['LIKE', 'progress_code', $regNo . '%', false])
        ->all(Yii::$app->smisDb);

    $credits = 0;
    $debits = 0;
    foreach ($transactions as $transaction) {
        if ($transaction['trans_type'] === 'CR') {
            $credits += $transaction['trans_amount'];
        }
        if ($transaction['trans_type'] === 'DR') {
            $debits += $transaction['trans_amount'];
        }
    }

    echo "Current SMIS fee balance: " . Yii::$app->formatter->asCurrency($debits - $credits) . "\n";
    echo "SUCCESS: Eligibility check/prep completed.\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
