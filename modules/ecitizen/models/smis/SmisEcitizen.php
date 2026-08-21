<?php

namespace app\modules\ecitizen\models\smis;

final class SmisEcitizen extends SmisActiveRecord
{
    public static function tableName(): string
    {
        return 'smis.ecitizen';
    }

    public static function primaryKey(): array
    {
        return ['payment_id'];
    }
}
