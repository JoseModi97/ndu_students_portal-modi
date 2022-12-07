<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the base model class for table "smisportal.sm_student_id_status".
 *
 * @property integer $id_status_no
 * @property string $status_name
 * @property integer $student_id_serial_no
 *
 * @property StudentId $studentIdSerialNo
 */
class StudentIdStatus extends ActiveRecord
{

    const ID_ACTIVE = 'ACTIVE';
    const ID_LOST = 'LOST';
    const ID_EXPIRED = 'EXPIRED';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'smisportal.sm_student_id_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_status_no', 'status_name', 'student_id_serial_no'], 'required'],
            [['id_status_no', 'student_id_serial_no'], 'integer'],
            [['status_name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_status_no' => 'Id Status No',
            'status_name' => 'Status Name',
            'student_id_serial_no' => 'Student Id Serial No',
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
