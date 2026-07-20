<?php

namespace app\modules\refund_requests\models;

use yii\db\ActiveRecord;

class StudentStatus extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'smisportal.sm_student_status';
    }
}
