<?php
require 'vendor/autoload.php';
require 'vendor/yiisoft/yii2/Yii.php';
$config = require 'config/console.php';
new yii\console\Application($config);

$schema = 'smis';
$db = Yii::$app->smisDb;

$query = (new \yii\db\Query())
    ->from($schema . '.fss_fee_transactions')
    ->where(['LIKE', 'trans_desc', '%CAUTION MONEY%', false]);

echo "SQL: " . $query->createCommand($db)->rawSql . "\n";
echo "Count: " . $query->count('*', $db) . "\n";

$all = $query->all($db);
echo "Records:\n" . json_encode($all, JSON_PRETTY_PRINT) . "\n";
