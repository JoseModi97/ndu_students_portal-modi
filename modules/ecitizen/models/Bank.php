<?php

namespace app\modules\ecitizen\models;

use yii\db\ActiveRecord;

final class Bank extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'smisportal.fss_banks';
    }

    public static function primaryKey(): array
    {
        return ['bank_code'];
    }
}
