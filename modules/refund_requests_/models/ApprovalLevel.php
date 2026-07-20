<?php

namespace app\modules\refund_requests\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.fss_refund_approval levels".
 *
 * @property int $approval_level_id
 * @property string $approval_order
 * @property string $description
 *
 * @property ApprovalProcess[] $approvalProcesses
 */
class ApprovalLevel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal."fss_refund_approval levels"';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['approval_level_id', 'approval_order', 'description'], 'required'],
            [['approval_level_id'], 'integer'],
            [['approval_order'], 'string', 'max' => 30],
            [['description'], 'string', 'max' => 150],
            [['approval_level_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'approval_level_id' => 'Approval Level ID',
            'approval_order' => 'Approval Order',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[ApprovalProcesses]].
     *
     * @return ActiveQuery
     */
    public function getApprovalProcesses(): ActiveQuery
    {
        return $this->hasMany(ApprovalProcess::class, ['approver_id' => 'approval_level_id']);
    }
}
