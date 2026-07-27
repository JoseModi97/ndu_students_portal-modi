<?php

namespace app\modules\ecitizen\models\smis;

use yii\db\ActiveQuery;

final class SmisBankBranch extends SmisActiveRecord
{
    public static function tableName(): string
    {
        return 'smis.fss_bank_branches';
    }

    public static function primaryKey(): array
    {
        return ['branch_code'];
    }

    public function getBank(): ActiveQuery
    {
        return $this->hasOne(SmisBank::class, ['bank_code' => 'bank_code']);
    }
}
