<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

use yii\base\InvalidConfigException;
use yii\helpers\VarDumper;
use yii\web\Application;

require __DIR__ . '/../config/constants.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

/**
 * Dump and die
 * @param $v $v [explicit description]
 * @return void
 */
function dd($v): void
{
    if(YII_ENV_DEV) {
        VarDumper::dump($v, 10, true);
        exit();
    }
}

$config = require __DIR__ . '/../config/web.php';

try {
    (new Application($config))->run();
} catch (InvalidConfigException $e) {
    $errorMessage = $e->getMessage();
    ob_start();
    require __DIR__ . '/../views/layouts/appInitError.php';
    echo ob_get_clean();
}
