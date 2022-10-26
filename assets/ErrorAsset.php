<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\assets;

use yii\web\AssetBundle;

class ErrorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://fonts.googleapis.com/css?family=Poiret+One',
        'css/error.css'
    ];
}