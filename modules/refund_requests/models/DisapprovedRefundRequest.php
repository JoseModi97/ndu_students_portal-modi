<?php

namespace app\modules\refund_requests\models;

use yii\db\ActiveRecord;

class DisapprovedRefundRequest extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'smisportal.fss_refund_requests_disapproved';
    }
}
