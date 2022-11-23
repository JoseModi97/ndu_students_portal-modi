<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "smisportal.sm_withdrawal_request".
 *
 * @property int $withdrawal_request_id
 * @property int $withdrawal_type_id
 * @property string $request_date
 * @property string $reason
 * @property string $approval_status
 * @property int $student_id
 */
class SmWithdrawalRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.sm_withdrawal_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'withdrawal_type_id', 'request_date', 'reason',  'student_id'], 'required'],
            [['withdrawal_type_id', 'student_id'], 'default', 'value' => null],
            [['withdrawal_request_id', 'withdrawal_type_id', 'student_id'], 'integer'],
            [['request_date'], 'safe'],
            [['approval_status'], 'string'],
            [['reason'], 'string', 'max' => 250],
            [['withdrawal_request_id'], 'unique'],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::class, 'targetAttribute' => ['student_id' => 'student_id']],
            [['withdrawal_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmWithdrawalType::class, 'targetAttribute' => ['withdrawal_type_id' => 'withdrawal_type_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'withdrawal_request_id' => 'Withdrawal Request ID',
            'withdrawal_type_id' => 'Withdrawal Type',
            'request_date' => 'Request Date',
            'reason' => 'Reason',
            'approval_status' => 'Approval Status',
            'student_id' => 'Student ID',
        ];
    }
    public function getSmWithdrawalType()
    {
        return $this->hasOne(SmWithdrawalType::className(), ['withdrawal_type_id' => 'withdrawal_type_id']);
    }
}
