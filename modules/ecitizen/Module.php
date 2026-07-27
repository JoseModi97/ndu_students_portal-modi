<?php

namespace app\modules\ecitizen;

use Yii;
use yii\base\Module as BaseModule;
use yii\db\Connection;

class Module extends BaseModule
{
    public $controllerNamespace = 'app\modules\ecitizen\controllers';

    public string $portalDb = 'db';

    public string $smisDb = 'smisDb';

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
}
