<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

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
    const ID_NEW = 'NEW';
    const ID_REPLACEMENT = 'REPLACEMENT';
    const ID_RENEWAL = 'RENEWAL';

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

    /**
     * @param string $request_type
     * @return array
     */
    public static function loadRequestTypeByName(string $request_type = self::ID_REPLACEMENT): array
    {
        $data = IdRequestType::find()
            ->where(['id_type_desc' => $request_type])
            ->orderBy('request_type_id')
            ->asArray()
            ->all();

        return ArrayHelper::map($data, 'request_type_id', 'id_type_desc');
    }

    public static function loadRequestTypes(): array
    {
        $data = IdRequestType::find()
            ->orderBy('request_type_id')
            ->asArray()
            ->all();

        return ArrayHelper::map($data, 'request_type_id', 'id_type_desc');
    }

}
