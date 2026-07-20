<?php

namespace app\modules\refund_requests\models;

use yii\db\ActiveRecord;

class InvoiceDetail extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'smisportal.fss_invoice_details';
    }
}
