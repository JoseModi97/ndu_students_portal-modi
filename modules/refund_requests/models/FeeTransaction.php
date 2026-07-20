<?php

namespace app\modules\refund_requests\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class FeeTransaction extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'smisportal.fss_fee_transactions';
    }

    public function getInvoice(): ActiveQuery
    {
        return $this->hasOne(Invoice::class, ['trans_id' => 'trans_id']);
    }
}
