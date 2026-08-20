<?php

namespace app\modules\ecitizen\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

final class BankAccount extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'smisportal.fss_bank_accounts';
    }

    public static function primaryKey(): array
    {
        return ['brank_account_id'];
    }

    public function getBranch(): ActiveQuery
    {
        return $this->hasOne(BankBranch::class, ['branch_code' => 'branch_code']);
    }
}
