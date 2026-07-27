<?php

namespace app\modules\ecitizen\models\smis;

final class SmisFeePayment extends SmisActiveRecord
{
    public static function tableName(): string
    {
        return 'smis.fss_fee_payments';
    }
}
