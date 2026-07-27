<?php

namespace app\modules\ecitizen\models\smis;

use yii\db\ActiveQuery;

final class SmisBankAccount extends SmisActiveRecord
{
    public static function tableName(): string
    {
        return 'smis.fss_bank_accounts';
    }

    public static function primaryKey(): array
    {
        return ['brank_account_id'];
    }

    public function getBranch(): ActiveQuery
    {
        return $this->hasOne(SmisBankBranch::class, ['branch_code' => 'branch_code']);
    }
}
