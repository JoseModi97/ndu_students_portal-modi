<?php

namespace app\modules\ecitizen\models\smis;

final class SmisBank extends SmisActiveRecord
{
    public static function tableName(): string
    {
        return 'smis.fss_banks';
    }

    public static function primaryKey(): array
    {
        return ['bank_code'];
    }
}
