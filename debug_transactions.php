<?php
require 'vendor/autoload.php';
require 'vendor/yiisoft/yii2/Yii.php';
$config = require 'config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
$transactions = (new \yii\db\Query())
    ->select('*')
    ->from('smis.fss_fee_transactions')
    ->where(['LIKE', 'progress_code', $regNo . '%', false])
    ->all(Yii::$app->smisDb);

echo json_encode($transactions, JSON_PRETTY_PRINT);
