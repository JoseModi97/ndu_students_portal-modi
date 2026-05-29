<?php

namespace app\modules\caution_refund\models;

use app\models\StudentProgCurriculum;

/**
 * This is the model class for table "smisportal.sm_admitted_student".
 * 
 * @property-read string|null $registration_number
 */
class User extends \app\models\User
{
    /**
     * @return string|null
     */
    public function getRegistration_number(): ?string
    {
        $studentProgramme = StudentProgCurriculum::find()
            ->select('registration_number')
            ->where(['adm_refno' => $this->adm_refno])
            ->asArray()
            ->one();

        return $studentProgramme['registration_number'] ?? null;
    }
}
