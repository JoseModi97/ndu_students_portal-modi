<?php

namespace app\modules\refund_requests\models;

use yii\db\ActiveRecord;

class CancelledVoucher extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'smisportal.fss_cancelled_vouchers';
    }
}
