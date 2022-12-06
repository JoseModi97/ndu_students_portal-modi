<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the base model class for table "smisportal.sm_id_request_type".
 *
 * @property integer $request_type_id
 * @property string $id_type_desc
 *
 * @property-read ActiveQuery $studentIdRequests
 * @property StudentIdRequest[] $smStudentIdRequests
 */
class IdRequestType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_id_request_type';
    }


    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['request_type_id'], 'required'],
            [['request_type_id'], 'integer'],
            [['id_type_desc'], 'string', 'max' => 30]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'request_type_id' => 'Request Type ID',
            'id_type_desc' => 'Request type',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getStudentIdRequests(): ActiveQuery
    {
        return $this->hasMany(StudentIdRequest::class, ['request_type_id' => 'request_type_id']);
    }
}
