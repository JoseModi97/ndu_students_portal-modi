<?php

namespace app\modules\ecitizen\models;

use yii\db\ActiveRecord;

final class PaymentType extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'smisportal.fss_payment_types';
    }

    public static function primaryKey(): array
    {
        return ['payment_type_id'];
    }
}
