<?php

namespace app\modules\refund_requests\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Invoice extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'smisportal.fss_invoice';
    }

    public function getDetails(): ActiveQuery
    {
        return $this->hasMany(InvoiceDetail::class, ['invoice_id' => 'id']);
    }
}
