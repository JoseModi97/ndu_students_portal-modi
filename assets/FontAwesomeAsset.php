<?php
namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;
use yii\web\YiiAsset;

/**
 * Font Awesome asset bundle.
 *
 * @author Anthony G <agithaka@uonbi.ac.ke>
 * @since 2.0
 */
class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@vendor/components/font-awesome/';
    public $depends = [
        YiiAsset::class,
    ];

    public $cssOptions = array(
        'position' => View::POS_HEAD
    );

    public $css = [
        'css/all.css',
    ];
}
