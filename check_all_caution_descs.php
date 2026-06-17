<?php
define('YII_DEBUG', true);
define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

echo "Checking all unique caution-like descriptions in transactions...\n";
$descs = (new \yii\db\Query())
    ->select(['trans_desc', 'COUNT(*) as count'])
    ->from('smis.fss_fee_transactions')
    ->where(['ILIKE', 'trans_desc', '%CAUTION%'])
    ->groupBy('trans_desc')
    ->all(Yii::$app->smisDb);

print_r($descs);
