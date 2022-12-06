<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the base model class for table "smisportal.sm_student_id_request".
 *
 * @property integer $request_id
 * @property integer $request_type_id
 * @property integer $student_prog_curr_id
 * @property string $request_date
 * @property integer $status_id
 * @property integer $receipt_number
 * @property string $source
 *
 * @property IdRequestStatus $status
 * @property IdRequestType $requestType
 * @property StudentProgramme $studentProgCurr
 */
class StudentIdRequest extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_student_id_request';
    }


    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['request_type_id', 'student_prog_curr_id', 'request_date', 'status_id', 'source'], 'required'],
            [['request_type_id', 'student_prog_curr_id', 'status_id', 'receipt_number'], 'integer'],
            [['request_date'], 'safe'],
            [['source'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'request_id' => 'Request ID',
            'request_type_id' => 'Request type',
            'student_prog_curr_id' => 'Current programme',
            'request_date' => 'Request Date',
            'status_id' => 'Request status',
            'receipt_number' => 'Receipt Number',
            'source' => 'Request reason',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(IdRequestStatus::class, ['status_id' => 'status_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRequestType(): ActiveQuery
    {
        return $this->hasOne(IdRequestType::class, ['request_type_id' => 'request_type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStudentProgCurr(): ActiveQuery
    {
        return $this->hasOne(StudentProgramme::class, ['student_prog_curriculum_id' => 'student_prog_curr_id']);
    }
}
