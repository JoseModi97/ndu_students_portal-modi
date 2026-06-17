<?php
define('YII_DEBUG', true);
define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "Tracing all transactions in SMIS for $regNo via progress IDs...\n";

$student = (new \yii\db\Query())
    ->select(['student_prog_curriculum_id'])
    ->from('smis.sm_student_programme_curriculum')
    ->where(['registration_number' => $regNo])
    ->one(Yii::$app->smisDb);

if (!$student) {
    echo "Student not found.\n";
    exit;
}

$progressIds = (new \yii\db\Query())
    ->select(['academic_progress_id'])
    ->from('smis.sm_academic_progress')
    ->where(['student_prog_curriculum_id' => $student['student_prog_curriculum_id']])
    ->column(Yii::$app->smisDb);

echo "Academic Progress IDs: " . implode(', ', $progressIds) . "\n";

$transactions = (new \yii\db\Query())
    ->select(['*'])
    ->from('smis.fss_fee_transactions')
    ->where(['academic_progress_id' => $progressIds])
    ->orderBy(['trans_date' => SORT_ASC])
    ->all(Yii::$app->smisDb);

echo "Total transactions found: " . count($transactions) . "\n";
foreach ($transactions as $t) {
    echo sprintf("[%s] %s: %s %8.2f (%s) [ID: %s]\n", $t['trans_date'], $t['trans_type'], str_pad($t['trans_desc'], 30), $t['trans_amount'], $t['progress_code'], $t['academic_progress_id']);
}
