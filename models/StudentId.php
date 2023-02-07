<?php

namespace app\models;

use app\controllers\BaseController;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the base model class for table "smisportal.sm_student_id".
 *
 * @property integer $student_id_serial_no
 * @property integer $student_prog_curr_id
 * @property string $issuance_date
 * @property string $valid_from
 * @property string $valid_to
 * @property integer $barcode
 * @property string $id_status
 *
 * @property StudentProgramme $studentProgCurr
 * @property StudentIdDetail[] $studentIdDetails
 * @property StudentIdStatus[] $studentIdStatuses
 */
class StudentId extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'smisportal.sm_student_id';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id_serial_no', 'student_prog_curr_id', 'issuance_date', 'valid_from', 'valid_to', 'barcode', 'id_status'], 'required'],
            [['student_id_serial_no', 'student_prog_curr_id', 'barcode'], 'integer'],
            [['issuance_date', 'valid_from', 'valid_to'], 'safe'],
            [['id_status'], 'string', 'max' => 15]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'student_id_serial_no' => 'Serial No',
            'student_prog_curr_id' => 'Programme',
            'issuance_date' => 'Issuance Date',
            'valid_from' => 'Valid From',
            'valid_to' => 'Valid To',
            'barcode' => 'Barcode',
            'id_status' => 'ID status',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getStudentProgCurr(): ActiveQuery
    {
        return $this->hasOne(StudentProgramme::class, ['student_prog_curriculum_id' => 'student_prog_curr_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStudentIdDetails(): ActiveQuery
    {
        return $this->hasMany(StudentIdDetail::class, ['student_id_serial_no' => 'student_id_serial_no']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStudentIdStatuses(): ActiveQuery
    {
        return $this->hasMany(StudentIdStatus::class, ['student_id_serial_no' => 'student_id_serial_no']);
    }

    /**
     * This function check if the student has an active id that is still valid
     *
     * @return bool
     */
    public static function hasActiveAndValidId(): bool
    {
        $currentProgramme = StudentProgramme::find()
            ->joinWith(['studentStatus'])
            ->where(['adm_refno' => Yii::$app->user->identity->adm_refno])
            ->andWhere(['status' => 'CURRENT'])
            ->one();

        if ($currentProgramme == null) {
            return false;
        }

        $idDetails = self::find()
            ->select('id_status')
            ->where(['>=', 'valid_to', date('Y-m-d')])
            ->andWhere(['id_status' => StudentIdStatus::ID_ACTIVE])
            ->andWhere(['student_prog_curr_id' => $currentProgramme->student_prog_curriculum_id])
            ->count();

        return ($idDetails > 0);
    }
}
