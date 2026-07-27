<?php

namespace app\modules\ecitizen\models\smis;

final class SmisStudent extends SmisActiveRecord
{
    public static function tableName(): string
    {
        return 'smis.sm_student';
    }
}
