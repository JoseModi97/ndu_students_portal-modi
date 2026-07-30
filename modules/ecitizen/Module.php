<?php

namespace app\modules\ecitizen;

use app\modules\ecitizen\components\EcitizenLogTarget;
use Yii;
use yii\base\Module as BaseModule;
use yii\db\Connection;
use yii\helpers\FileHelper;

class Module extends BaseModule
{
    public $controllerNamespace = 'app\modules\ecitizen\controllers';

    public string $portalDb = 'db';

    public function init(): void
    {
        parent::init();
        $this->registerLogTarget();
    }

    public function connection(string $id): Connection
    {
        if ($this->has($id)) {
            return $this->get($id);
        }

        return Yii::$app->get($id);
    }

    public function ecitizenParams(): array
    {
        return $this->params;
    }

    /**
     * Registers a dedicated, developer-readable text log for this module
     * (runtime/logs/ecitizen/ecitizen.txt) so every interaction with the
     * eCitizen payment flows - and every error raised while handling one -
     * is captured in one place, independent of the application's global
     * log targets/trace level.
     */
    private function registerLogTarget(): void
    {
        if (!Yii::$app->has('log') || isset(Yii::$app->log->targets['ecitizen'])) {
            return;
        }

        $logDir = Yii::getAlias('@runtime/logs/ecitizen');
        if (!is_dir($logDir)) {
            FileHelper::createDirectory($logDir, 0775);
        }

        Yii::$app->log->targets['ecitizen'] = Yii::createObject([
            'class' => EcitizenLogTarget::class,
            'logFile' => $logDir . '/ecitizen.txt',
            'categories' => ['ecitizen.*'],
            'levels' => ['error', 'warning', 'info'],
            'logVars' => [],
            'maxFileSize' => 20480,
            'maxLogFiles' => 10,
            'exportInterval' => 1,
        ]);
    }
}
