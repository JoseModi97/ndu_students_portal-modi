<?php

namespace app\models;

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
            [['student_id_serial_no', 'student_prog_curr_id', 'issuance_date', 'valid_from', 'valid_to', 'barcode'], 'required'],
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
            'id_status' => 'Status',
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
}
