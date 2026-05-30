<?php
define('YII_DEBUG', true);
define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

try {
    $progCurrId = 256;
    echo "Checking all charges for prog_curr_id: $progCurrId\n";
    $charges = (new \yii\db\Query())
        ->select(['pcc.*', 'fi.fee_description'])
        ->from('smis.fss_prog_curr_charges pcc')
        ->innerJoin('smis.fss_fee_items fi', 'pcc.fee_code = fi.fee_code')
        ->where(['pcc.prog_curr_id' => $progCurrId])
        ->all(Yii::$app->smisDb);
    
    print_r($charges);
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
