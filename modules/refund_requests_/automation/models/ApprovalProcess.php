<?php

namespace app\modules\refund_requests\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.fss_refund_approval_process".
 *
 * @property int $approval_process_id
 * @property int $request_id
 * @property string $approval_status
 * @property string|null $remarks
 * @property string $approval_date
 * @property int $approver_id
 *
 * @property RefundApprover $approver
 * @property RefundRequest $request
 */
class ApprovalProcess extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.fss_refund_approval_process';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['approval_process_id', 'request_id', 'approval_status', 'approval_date', 'approver_id'], 'required'],
            [['approval_process_id', 'request_id', 'approver_id'], 'integer'],
            [['approval_date'], 'safe'],
            [['approval_status'], 'string', 'max' => 30],
            [['remarks'], 'string', 'max' => 150],
            [['approval_process_id'], 'unique'],
            [['approver_id'], 'exist', 'skipOnError' => true, 'targetClass' => RefundApprover::class, 'targetAttribute' => ['approver_id' => 'approver_id']],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => RefundRequest::class, 'targetAttribute' => ['request_id' => 'request_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'approval_process_id' => 'Approval Process ID',
            'request_id' => 'Request ID',
            'approval_status' => 'Approval Status',
            'remarks' => 'Remarks',
            'approval_date' => 'Approval Date',
            'approver_id' => 'Approver ID',
        ];
    }

    /**
     * Gets query for [[Approver]].
     *
     * @return ActiveQuery
     */
    public function getApprover(): ActiveQuery
    {
        return $this->hasOne(RefundApprover::class, ['approver_id' => 'approver_id']);
    }

    /**
     * Gets query for [[Request]].
     *
     * @return ActiveQuery
     */
    public function getRequest(): ActiveQuery
    {
        return $this->hasOne(RefundRequest::class, ['request_id' => 'request_id']);
    }
}
