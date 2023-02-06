<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the base model class for table "smisportal.sm_id_request_status".
 *
 * @property integer $status_id
 * @property string $status_name
 *
 * @property StudentIdRequest[] $smStudentIdRequests
 */
class IdRequestStatus extends ActiveRecord
{
    const STATUS_PENDING = 'PENDING';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_CLOSED = 'CLOSED';

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_id_request_status';
    }


    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['status_id', 'status_name'], 'required'],
            [['status_id'], 'integer'],
            [['status_name'], 'string', 'max' => 30]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'status_id' => 'Status ID',
            'status_name' => 'Request status',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSmStudentIdRequests(): ActiveQuery
    {
        return $this->hasMany(StudentIdRequest::class, ['status_id' => 'status_id']);
    }

    /**
     * @param string $requestStatus
     * @return array
     */
    public static function loadRequestStatusByName(string $requestStatus = self::STATUS_PENDING): array
    {
        $data = IdRequestStatus::find()
            ->where(['status_name' => $requestStatus])
            ->orderBy('status_id')
            ->asArray()
            ->all();
        return ArrayHelper::map($data, 'status_id', 'status_name');
    }
}
