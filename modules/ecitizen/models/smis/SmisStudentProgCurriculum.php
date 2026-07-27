<?php

namespace app\modules\ecitizen\models\smis;

final class SmisStudentProgCurriculum extends SmisActiveRecord
{
    public static function tableName(): string
    {
        return 'smis.sm_student_programme_curriculum';
    }
}
