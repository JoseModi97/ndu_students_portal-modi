<?php

namespace app\modules\caution_refund\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "smis.sm_caution_refund_official".
 *
 * @property int $refund_official_id
 * @property string $registration_number
 * @property float|null $amount
 * @property string|null $status
 * @property string|null $created_at
 *
 * @property ApprovalProcess[] $approvalProcesses
 */
class CautionRefundOfficial extends ActiveRecord
{
    /**
     * @return Connection the database connection used by this AR class.
     */
    public static function getDb(): Connection
    {
        return Yii::$app->get('smisDb');
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.sm_caution_refund_official';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['registration_number'], 'required'],
            [['amount'], 'number'],
            [['created_at'], 'safe'],
            [['registration_number', 'status'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'refund_official_id' => 'Refund Official ID',
            'registration_number' => 'Registration Number',
            'amount' => 'Amount',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[ApprovalProcesses]].
     *
     * @return ActiveQuery
     */
    public function getApprovalProcesses(): ActiveQuery
    {
        return $this->hasMany(ApprovalProcess::class, ['refund_official_id' => 'refund_official_id']);
    }
}
