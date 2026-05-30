<?php

namespace app\modules\refund_requests;

use yii\base\Module as BaseModule;

/**
 * refund_requests module definition class
 */
class Module extends BaseModule
{
    /**
     * @var bool Whether to override/bypass the caution fee and fee balance requirements.
     */
    public $overrideEligibility = true;

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\refund_requests\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * @return \yii\db\Connection
     * @throws \yii\base\InvalidConfigException
     */
    public function getSmisDb()
    {
        $envFile = __DIR__ . '/.env';
        
        if (!file_exists($envFile)) {
            throw new \yii\base\InvalidConfigException("Critical: SMIS configuration file missing in module.");
        }

        $env = parse_ini_file($envFile);
        
        return new \yii\db\Connection([
            'dsn' => "pgsql:host={$env['SMIS_DB_SERVER']};port={$env['SMIS_DB_PORT']};dbname={$env['SMIS_DB_NAME']}",
            'username' => $env['SMIS_DB_USER'],
            'password' => $env['SMIS_DB_PASS'],
            'charset' => 'utf8',
        ]);
    }
}
