<?php

namespace app\modules\ecitizen\models\smis;

final class SmisReceiptCounter extends SmisActiveRecord
{
    public static function tableName(): string
    {
        return 'smis.fss_receipt_counter';
    }
}
