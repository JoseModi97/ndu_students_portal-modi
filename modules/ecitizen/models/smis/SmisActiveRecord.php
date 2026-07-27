<?php

namespace app\modules\ecitizen\models\smis;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * Base class for models bound to the standalone `smis` database (the
 * `smisDb` app component), kept separate from the `smisportal` database
 * that the rest of the eCitizen module writes to.
 */
abstract class SmisActiveRecord extends ActiveRecord
{
    public static function getDb(): Connection
    {
        return Yii::$app->get('smisDb');
    }
}
