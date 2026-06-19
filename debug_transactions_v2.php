<?php
require 'vendor/autoload.php';
require 'vendor/yiisoft/yii2/Yii.php';
$config = require 'config/console.php';
new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
$schema = 'smis';
$db = Yii::$app->smisDb;

$postingDescriptionCondition = ['or'];
$postingDescriptionCondition[] = ['LIKE', 'trans_desc', '%CAUTION MONEY%', false];
$postingDescriptionCondition[] = ['LIKE', 'trans_desc', '%CAUTION REFUND%', false];

$query = (new \yii\db\Query())
    ->from($schema . '.fss_fee_transactions')
    ->where([
        'and',
        ['LIKE', 'progress_code', $regNo . '%', false],
        $postingDescriptionCondition,
    ]);

echo "SQL: " . $query->createCommand($db)->rawSql . "\n";
echo "Count: " . $query->count('*', $db) . "\n";

$all = $query->all($db);
echo "Records:\n" . json_encode($all, JSON_PRETTY_PRINT) . "\n";
