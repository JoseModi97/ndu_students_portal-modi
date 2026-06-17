<?php
define('YII_DEBUG', true);
define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

$userId = '5293';
echo "Searching all transactions in SMIS for user_id $userId...\n";

$transactions = (new \yii\db\Query())
    ->select(['*'])
    ->from('smis.fss_fee_transactions')
    ->where(['user_id' => $userId])
    ->orderBy(['trans_date' => SORT_ASC])
    ->all(Yii::$app->smisDb);

echo "Total transactions found: " . count($transactions) . "\n";
foreach ($transactions as $t) {
    echo sprintf("[%s] %s: %s %8.2f (%s) [APID: %s]\n", $t['trans_date'], $t['trans_type'], str_pad($t['trans_desc'], 30), $t['trans_amount'], $t['progress_code'], $t['academic_progress_id']);
}
