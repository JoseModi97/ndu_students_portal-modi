<?php

namespace app\models;

use app\models\StudentId;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the base model class for table "smisportal.sm_student_id_details".
 *
 * @property integer $stud_id_det_id
 * @property integer $student_id_serial_no
 * @property string $student_id_status
 * @property string $remarks
 * @property string $status_date
 *
 * @property StudentId $studentIdSerialNo
 */
class StudentIdDetail extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'smisportal.sm_student_id_details';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stud_id_det_id', 'student_id_serial_no', 'student_id_status', 'remarks', 'status_date'], 'required'],
            [['stud_id_det_id', 'student_id_serial_no'], 'integer'],
            [['student_id_status', 'remarks'], 'string'],
            [['status_date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'stud_id_det_id' => 'Stud Id Det ID',
            'student_id_serial_no' => 'Student Id Serial No',
            'student_id_status' => 'Student Id Status',
            'remarks' => 'Remarks',
            'status_date' => 'Status Date',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getStudentIdSerialNo(): ActiveQuery
    {
        return $this->hasOne(StudentId::class, ['student_id_serial_no' => 'student_id_serial_no']);
    }
}
