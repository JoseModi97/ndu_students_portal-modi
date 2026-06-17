<?php
define('YII_DEBUG', true);
define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

echo "Searching for any CR transactions of 5000 in SMIS...\n";

$transactions = (new \yii\db\Query())
    ->select(['*'])
    ->from('smis.fss_fee_transactions')
    ->where(['trans_amount' => 5000, 'trans_type' => 'CR'])
    ->all(Yii::$app->smisDb);

print_r($transactions);
