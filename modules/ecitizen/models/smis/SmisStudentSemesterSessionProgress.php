<?php

namespace app\modules\ecitizen\models\smis;

final class SmisStudentSemesterSessionProgress extends SmisActiveRecord
{
    public static function tableName(): string
    {
        return 'smis.sm_student_sem_session_progress';
    }
}
