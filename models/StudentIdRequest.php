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
            [['request_type_id', 'student_prog_curr_id', 'status_id'], 'integer'],
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

    /**
     * @return bool
     */
    public static function hasOpenIdRequest(): bool
    {
        $idRequest = self::find()
            ->joinWith(['studentProgCurr', 'status'])
            ->where(['adm_refno' => \Yii::$app->user->identity->adm_refno])
            ->andWhere(['<>', 'status_name', IdRequestStatus::STATUS_CLOSED])
            ->asArray()
            ->one();

        return $idRequest != null;
    }

}
