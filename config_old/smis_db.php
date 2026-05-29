<?php
/**
 * Connection settings for the main SMIS database.
 */
require_once __DIR__ . '/db_constants.php';

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=' . SMIS_DB_SERVER . ';port=' . SMIS_DB_PORT . ';dbname=' . SMIS_DB_NAME,
    'username' => SMIS_DB_USER,
    'password' => SMIS_DB_PASS,
    'charset' => 'utf8',
];
