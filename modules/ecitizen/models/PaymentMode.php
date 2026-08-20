<?php

namespace app\modules\ecitizen\models;

use yii\db\ActiveRecord;

final class PaymentMode extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'smisportal.fss_payment_modes';
    }

    public static function primaryKey(): array
    {
        return ['payment_mode_id'];
    }
}
