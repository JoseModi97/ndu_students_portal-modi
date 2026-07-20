<?php

namespace app\modules\refund_requests\models;

use yii\db\ActiveRecord;

class AcademicProgress extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'smisportal.sm_academic_progress';
    }
}
