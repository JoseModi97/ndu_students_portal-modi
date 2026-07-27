<?php

namespace app\modules\ecitizen\models\smis;

final class SmisAcademicSession extends SmisActiveRecord
{
    public static function tableName(): string
    {
        return 'smis.org_academic_session';
    }
}
