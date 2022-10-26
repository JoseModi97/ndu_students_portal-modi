<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Yii comes with some js assets under vendor/yiisoft/yii2/assets
 * Combine and minify these files
 */
class AllYiiAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/all-yii.min.js'
    ];
    public $depends = [
        'yii\bootstrap5\BootstrapPluginAsset'
    ];
}