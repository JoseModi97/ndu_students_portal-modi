<?php

namespace app\modules\caution_refund\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_caution_refund".
 *
 * @property int $refund_id
 * @property int $student_id
 * @property string $registration_number
 * @property int|null $bank_branch_id
 * @property string|null $account_number
 * @property string|null $mobile_number
 * @property float|null $refund_amount
 * @property string|null $status
 * @property string|null $refund_type
 * @property string|null $application_date
 * @property string|null $remarks
 *
 * @property BankBranch $bankBranch
 */
class CautionRefund extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_caution_refund';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['student_id', 'registration_number', 'refund_type', 'refund_amount'], 'required'],
            
            // Conditional validation for STANDARD mode
            [['bank_branch_id', 'account_number'], 'required', 'when' => function($model) {
                return $model->refund_type === 'STANDARD';
            }, 'whenClient' => "function (attribute, value) {
                return $('input[name=\"CautionRefund[refund_type]\"]:checked').val() === 'STANDARD';
            }"],

            // Conditional validation for CHSS mode
            [['mobile_number'], 'required', 'when' => function($model) {
                return $model->refund_type === 'CHSS';
            }, 'whenClient' => "function (attribute, value) {
                return $('input[name=\"CautionRefund[refund_type]\"]:checked').val() === 'CHSS';
            }"],

            [['student_id', 'bank_branch_id'], 'default', 'value' => null],
            [['student_id', 'bank_branch_id'], 'integer'],
            [['refund_amount'], 'number', 'min' => 1],
            [['application_date'], 'safe'],
            [['remarks'], 'string'],
            [['registration_number', 'mobile_number', 'status', 'refund_type'], 'string', 'max' => 20],
            [['account_number'], 'string', 'max' => 50],
            [['bank_branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => BankBranch::class, 'targetAttribute' => ['bank_branch_id' => 'branch_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'refund_id' => 'Refund ID',
            'student_id' => 'Student ID',
            'registration_number' => 'Registration Number',
            'bank_branch_id' => 'Bank Branch',
            'account_number' => 'Account Number',
            'mobile_number' => 'Mobile Number',
            'refund_amount' => 'Refund Amount',
            'status' => 'Status',
            'refund_type' => 'Refund Type',
            'application_date' => 'Application Date',
            'remarks' => 'Remarks',
        ];
    }

    /**
     * Gets query for [[BankBranch]].
     *
     * @return ActiveQuery
     */
    public function getBankBranch(): ActiveQuery
    {
        return $this->hasOne(BankBranch::class, ['branch_id' => 'bank_branch_id']);
    }
}
