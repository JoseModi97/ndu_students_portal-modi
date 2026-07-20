<?php
/**
 * Compatibility SMIS connection for legacy code that still uses Yii::$app->smisDb.
 *
 * SMIS credentials are intentionally kept out of config/db_constants.php. This file
 * reads module-local .env files, matching the refund_requests/ecitizen pattern.
 */

$envFiles = [
    __DIR__ . '/../modules/refund_requests/.env',
    __DIR__ . '/../modules/ecitizen/.env',
];

$env = null;
foreach ($envFiles as $envFile) {
    if (is_file($envFile)) {
        $env = parse_ini_file($envFile);
        break;
    }
}

if ($env === null) {
    throw new \yii\base\InvalidConfigException('Critical: SMIS configuration file missing in module .env files.');
}

foreach (['SMIS_DB_SERVER', 'SMIS_DB_PORT', 'SMIS_DB_NAME', 'SMIS_DB_USER', 'SMIS_DB_PASS'] as $key) {
    if (empty($env[$key])) {
        throw new \yii\base\InvalidConfigException("Critical: {$key} is missing in SMIS module configuration.");
    }
}

return [
    'class' => 'yii\db\Connection',
    'dsn' => "pgsql:host={$env['SMIS_DB_SERVER']};port={$env['SMIS_DB_PORT']};dbname={$env['SMIS_DB_NAME']}",
    'username' => $env['SMIS_DB_USER'],
    'password' => $env['SMIS_DB_PASS'],
    'charset' => 'utf8',
];
