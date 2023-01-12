<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

$db = [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=' . DB_SERVER . ';dbname=' . DB_NAME,
    'username' => DB_USER,
    'password' => DB_PASS,
    'charset' => 'utf8',
];

$dbDev = [];
if (file_exists(__DIR__ . '/db.local.php')) {
    $dbDev = require_once(__DIR__ . '/db.local.php');
}

return yii\helpers\ArrayHelper::merge(
    $db,
    $dbDev
);
