<?php

namespace app\modules\ecitizen\models\smis;

final class SmisBankingSlip extends SmisActiveRecord
{
    public static function tableName(): string
    {
        return 'smis.fss_banking_slips';
    }

    public static function primaryKey(): array
    {
        return ['trans_id'];
    }
}
