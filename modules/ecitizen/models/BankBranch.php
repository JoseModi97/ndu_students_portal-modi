<?php

namespace app\modules\ecitizen\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

final class BankBranch extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'smisportal.fss_bank_branches';
    }

    public static function primaryKey(): array
    {
        return ['branch_code'];
    }

    public function getBank(): ActiveQuery
    {
        return $this->hasOne(Bank::class, ['bank_code' => 'bank_code']);
    }
}
