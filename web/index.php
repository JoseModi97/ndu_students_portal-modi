<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

use yii\helpers\VarDumper;

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

(new yii\web\Application($config))->run();
