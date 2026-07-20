<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

$debugValue = getenv('YII_DEBUG');
defined('YII_DEBUG') or define(
    'YII_DEBUG',
    $debugValue !== false && filter_var($debugValue, FILTER_VALIDATE_BOOLEAN)
);
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV') ?: 'prod');

