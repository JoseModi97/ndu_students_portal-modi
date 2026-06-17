<?php
define('YII_DEBUG', true);
define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

$spcId = 210;
echo "Searching smis.fss_fee_payments for student_prog_curriculum_id $spcId...\n";

$payments = (new \yii\db\Query())
    ->select(['*'])
    ->from('smis.fss_fee_payments')
    ->where(['student_prog_curriculum_id' => $spcId])
    ->all(Yii::$app->smisDb);

print_r($payments);
