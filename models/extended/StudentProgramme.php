<?php

namespace app\models\extended;

use app\models\StudentStatus;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class StudentProgramme extends \app\models\StudentProgramme
{

    /**
     * @return array
     */
    public static function loadActiveProgramme(): array
    {

        $data = self::find()
            ->joinWith('programmeCurriculum.program')
            ->joinWith(['studentStatus' => function (ActiveQuery $query) {
                return $query->andWhere(['=', StudentStatus::tableName() . '.status', 'ACTIVE']);
            }])
            ->where(['adm_refno' => \Yii::$app->user->id])
            ->asArray()
            ->all();

        return ArrayHelper::map($data, 'student_prog_curriculum_id', 'programmeCurriculum.program.prog_full_name');

    }

    public static function loadStudentProgrammes(): array
    {
        $data = self::find()
            ->select('student_prog_curriculum_id')
            ->where(['adm_refno' => \Yii::$app->user->id])
            ->asArray()
            ->all();

        return ArrayHelper::getColumn($data, 'student_prog_curriculum_id');
    }
}
