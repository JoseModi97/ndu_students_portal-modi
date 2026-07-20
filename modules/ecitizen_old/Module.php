<?php

namespace app\modules\ecitizen;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Module as BaseModule;
use yii\db\Connection;

class Module extends BaseModule
{
    public $controllerNamespace = 'app\modules\ecitizen\controllers';

    public string $portalDb = 'db';

    public string $smisDb = 'smisDb';

    public function connection(string $id): Connection
    {
        if ($id === $this->smisDb) {
            return $this->getSmisDb();
        }

        if ($this->has($id)) {
            return $this->get($id);
        }

        return Yii::$app->get($id);
    }

    public function ecitizenParams(): array
    {
        return $this->params;
    }

    public function getSmisDb(): Connection
    {
        $envFile = __DIR__ . '/.env';
        if (!is_file($envFile)) {
            throw new InvalidConfigException('Critical: SMIS configuration file missing in eCitizen module.');
        }

        $env = parse_ini_file($envFile);
        foreach (['SMIS_DB_SERVER', 'SMIS_DB_PORT', 'SMIS_DB_NAME', 'SMIS_DB_USER', 'SMIS_DB_PASS'] as $key) {
            if (empty($env[$key])) {
                throw new InvalidConfigException("Critical: {$key} is missing in eCitizen SMIS configuration.");
            }
        }

        return new Connection([
            'dsn' => "pgsql:host={$env['SMIS_DB_SERVER']};port={$env['SMIS_DB_PORT']};dbname={$env['SMIS_DB_NAME']}",
            'username' => $env['SMIS_DB_USER'],
            'password' => $env['SMIS_DB_PASS'],
            'charset' => 'utf8',
        ]);
    }
}
