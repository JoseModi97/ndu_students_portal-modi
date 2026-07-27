<?php

namespace app\modules\ecitizen\models\smis;

final class SmisAcademicProgress extends SmisActiveRecord
{
    public static function tableName(): string
    {
        return 'smis.sm_academic_progress';
    }
}
