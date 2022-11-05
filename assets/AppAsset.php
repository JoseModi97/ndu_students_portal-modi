<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        /**
         * Third party css
         */
        // Google Font: Source Sans Pro
        'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback',
        // Font Awesome
        'adminlte/plugins/fontawesome-free/css/all.min.css',
        //aos animate
        'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css',
        // iCheck
        'https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css',
        // Datetimepicker
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css',
        // Theme style
        'https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css',
        // Daterange picker
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css',
        // summernote
        'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css',
        // Toastr
        'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css',

        /**
         * Application css
         * Load these at the very bottom of this list
         */
        'css/site.css',
    ];

    public $js = [
        /**
         * Third party js
         */
        // daterangepicker
        'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js',
        //Summernote
        'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote.min.js',
        // Axios
        'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js',
        // Jquery validation
        'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js',
        'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js',
        // Datetimepicker
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js',
        // Aos
        'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js',
        // Toastr
        'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js',
        // AdminLTE App
        'https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js',

        /**
         * Application js
         * Load these at the very bottom of this list
         */
        'js/site.js',
        'js/jquery-validation.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
        'yii\bootstrap5\BootstrapPluginAsset'
    ];
}
