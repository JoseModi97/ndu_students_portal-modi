<?php

namespace app\modules\refund_requests\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.fss_refund_types".
 *
 * @property int $refund_type_id
 * @property string $refund_type_name
 * @property bool $refund_type_status
 *
 * @property RefundRequest[] $refundRequests
 */
class RefundType extends ActiveRecord
{
    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->refund_type_name === 'STANDARD' ? 'Caution Refund' : $this->refund_type_name;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.fss_refund_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['refund_type_id', 'refund_type_name', 'refund_type_status'], 'required'],
            [['refund_type_id'], 'integer'],
            [['refund_type_status'], 'boolean'],
            [['refund_type_name'], 'string'],
            [['refund_type_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'refund_type_id' => 'Refund Type ID',
            'refund_type_name' => 'Refund Type Name',
            'refund_type_status' => 'Refund Type Status',
        ];
    }

    /**
     * Gets query for [[RefundRequests]].
     *
     * @return ActiveQuery
     */
    public function getRefundRequests(): ActiveQuery
    {
        return $this->hasMany(RefundRequest::class, ['refund_type' => 'refund_type_id']);
    }
}
