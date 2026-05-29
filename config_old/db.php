<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
require __DIR__ . '/db_constants.php';

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=' . DB_SERVER . ';dbname=' . DB_NAME,
    'username' => DB_USER,
    'password' => DB_PASS,
    'charset' => 'utf8',
];
