<?php
define('YII_DEBUG', true);
define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

$regNo = 'NR605/0001/2022';
echo "Searching smis.fss_banking_slips for reg_number $regNo...\n";

$slips = (new \yii\db\Query())
    ->select(['*'])
    ->from('smis.fss_banking_slips')
    ->where(['reg_number' => $regNo])
    ->all(Yii::$app->smisDb);

print_r($slips);
