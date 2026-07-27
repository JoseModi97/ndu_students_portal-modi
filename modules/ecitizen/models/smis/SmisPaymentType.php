<?php

namespace app\modules\ecitizen\models\smis;

final class SmisPaymentType extends SmisActiveRecord
{
    public static function tableName(): string
    {
        return 'smis.fss_payment_types';
    }

    public static function primaryKey(): array
    {
        return ['payment_type_id'];
    }
}
