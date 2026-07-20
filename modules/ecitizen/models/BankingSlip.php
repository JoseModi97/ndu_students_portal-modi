<?php

namespace app\modules\ecitizen\models;

use yii\db\ActiveRecord;

final class BankingSlip extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'smisportal.fss_banking_slips';
    }

    public static function primaryKey(): array
    {
        return ['trans_id'];
    }
}
