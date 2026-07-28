<?php

namespace app\modules\ecitizen\models\smis;

final class SmisFeeTransaction extends SmisActiveRecord
{
    public static function tableName(): string
    {
        return 'smis.fss_fee_transactions';
    }
}
