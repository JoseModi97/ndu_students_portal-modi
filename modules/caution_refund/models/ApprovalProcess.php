<?php

namespace app\modules\caution_refund\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "smis.sm_approval_process".
 *
 * @property int $process_id
 * @property int $refund_official_id
 * @property int $level_id
 * @property string $status
 * @property string|null $comments
 * @property int|null $approver_id
 * @property string|null $approval_date
 *
 * @property ApprovalLevel $level
 * @property CautionRefundOfficial $refundOfficial
 */
class ApprovalProcess extends ActiveRecord
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
        return 'smis.sm_approval_process';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['refund_official_id', 'level_id', 'status'], 'required'],
            [['refund_official_id', 'level_id', 'approver_id'], 'default', 'value' => null],
            [['refund_official_id', 'level_id', 'approver_id'], 'integer'],
            [['comments'], 'string'],
            [['approval_date'], 'safe'],
            [['status'], 'string', 'max' => 20],
            [['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApprovalLevel::class, 'targetAttribute' => ['level_id' => 'level_id']],
            [['refund_official_id'], 'exist', 'skipOnError' => true, 'targetClass' => CautionRefundOfficial::class, 'targetAttribute' => ['refund_official_id' => 'refund_official_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'process_id' => 'Process ID',
            'refund_official_id' => 'Refund Official ID',
            'level_id' => 'Level ID',
            'status' => 'Status',
            'comments' => 'Comments',
            'approver_id' => 'Approver ID',
            'approval_date' => 'Approval Date',
        ];
    }

    /**
     * Gets query for [[Level]].
     *
     * @return ActiveQuery
     */
    public function getLevel(): ActiveQuery
    {
        return $this->hasOne(ApprovalLevel::class, ['level_id' => 'level_id']);
    }

    /**
     * Gets query for [[RefundOfficial]].
     *
     * @return ActiveQuery
     */
    public function getRefundOfficial(): ActiveQuery
    {
        return $this->hasOne(CautionRefundOfficial::class, ['refund_official_id' => 'refund_official_id']);
    }
}
