<?php

namespace app\modules\refund_requests\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.fss_refund_approvers".
 *
 * @property int $approver_id
 * @property string $user_id
 * @property int $approval_level_id
 * @property string $approver_status
 *
 * @property ApprovalLevel $approvalLevel
 * @property ApprovalProcess[] $approvalProcesses
 */
class RefundApprover extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.fss_refund_approvers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['approver_id', 'user_id', 'approval_level_id', 'approver_status'], 'required'],
            [['approver_id', 'approval_level_id'], 'integer'],
            [['approver_status'], 'string'],
            [['user_id'], 'string', 'max' => 30],
            [['approver_id'], 'unique'],
            [['approval_level_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApprovalLevel::class, 'targetAttribute' => ['approval_level_id' => 'approval_level_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'approver_id' => 'Approver ID',
            'user_id' => 'User ID',
            'approval_level_id' => 'Approval Level ID',
            'approver_status' => 'Approver Status',
        ];
    }

    /**
     * Gets query for [[ApprovalLevel]].
     *
     * @return ActiveQuery
     */
    public function getApprovalLevel(): ActiveQuery
    {
        return $this->hasOne(ApprovalLevel::class, ['approval_level_id' => 'approval_level_id']);
    }

    /**
     * Gets query for [[ApprovalProcesses]].
     *
     * @return ActiveQuery
     */
    public function getApprovalProcesses(): ActiveQuery
    {
        return $this->hasMany(ApprovalProcess::class, ['approver_id' => 'approver_id']);
    }
}
