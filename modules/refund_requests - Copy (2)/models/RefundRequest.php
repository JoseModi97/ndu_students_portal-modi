<?php

namespace app\modules\refund_requests\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.fss_refund_requests".
 *
 * @property int $request_id
 * @property int $student_prog_curriculum_id
 * @property string $mobile_no
 * @property string $email
 * @property string $application_date
 * @property string $refund_status
 * @property string|null $account_no
 * @property string|null $account_name
 * @property int|null $bank_id
 * @property int|null $branch_id
 * @property string $passport_id
 * @property string $declaration_status
 * @property float $amount_requested
 * @property string $approval_status
 * @property int|null $voucher_no
 * @property float|null $amount_approved
 * @property int $refund_type
 * @property string $payment_method
 * @property int $sync_status
 * @property string|null $sync_error
 * @property string|null $last_synced_at
 *
 * @property Bank $bank
 * @property BankBranch $branch
 * @property RefundType $refundType
 * @property StudentProgCurriculum $studentProgCurriculum
 * @property ApprovalProcess[] $approvalProcesses
 */
class RefundRequest extends ActiveRecord
{
    /**
     * @var float|null Maximum allowed amount for this request, set dynamically based on refund type and student records.
     */
    public $max_amount;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.fss_refund_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['request_id', 'student_prog_curriculum_id', 'email', 'application_date', 'passport_id', 'approval_status', 'refund_type'], 'required'],
            [['refund_status'], 'default', 'value' => 'NOT REFUNDED'],
            
            [['amount_requested'], 'required', 'message' => 'Please enter the amount you wish to be refunded.'],
            [['amount_requested'], 'validateAmountLimit'],
            [['payment_method'], 'required', 'message' => 'Please select a payment disbursement method (Bank or M-PESA).'],
            [['declaration_status'], 'required', 'message' => 'You must accept the declaration to proceed.'],
            
            // Conditional validation for Bank
            [['bank_id'], 'required', 'message' => 'Please select your bank from the list.', 'when' => function ($model) {
                return $model->payment_method === 'bank';
            }, 'whenClient' => "function (attribute, value) {
                return $('.payment-option-radio:checked').val() === 'bank';
            }"],
            [['branch_id'], 'required', 'message' => 'Please select your bank branch.', 'when' => function ($model) {
                return $model->payment_method === 'bank';
            }, 'whenClient' => "function (attribute, value) {
                return $('.payment-option-radio:checked').val() === 'bank';
            }"],
            [['account_no'], 'required', 'message' => 'Please provide your bank account number.', 'when' => function ($model) {
                return $model->payment_method === 'bank';
            }, 'whenClient' => "function (attribute, value) {
                return $('.payment-option-radio:checked').val() === 'bank';
            }"],

            // Conditional validation for MPESA
            [['mobile_no'], 'required', 'message' => 'Please provide the M-PESA mobile number.', 'when' => function ($model) {
                return $model->payment_method === 'mpesa';
            }, 'whenClient' => "function (attribute, value) {
                return $('.payment-option-radio:checked').val() === 'mpesa';
            }"],

            [['payment_method'], 'in', 'range' => ['bank', 'mpesa']],
            [['request_id', 'student_prog_curriculum_id', 'bank_id', 'branch_id', 'voucher_no', 'refund_type', 'sync_status'], 'integer'],
            [['application_date', 'last_synced_at'], 'safe'],
            [['amount_requested', 'amount_approved'], 'number', 'message' => 'Please enter a valid numeric amount.'],
            [['mobile_no'], 'string', 'max' => 20],
            [['mobile_no'], 'match', 'pattern' => '/^(07|01)[0-9]{8}$/', 'message' => 'Please enter a valid Kenyan mobile number (e.g., 0712345678).', 'when' => function ($model) {
                return (string)$model->payment_method === 'mpesa';
            }, 'whenClient' => "function (attribute, value) {
                return $('.payment-option-radio:checked').val() === 'mpesa';
            }"],
            [['email', 'passport_id'], 'string', 'max' => 100],
            [['email'], 'email', 'message' => 'Please provide a valid email address.'],
            [['refund_status', 'approval_status'], 'string', 'max' => 50],
            [['account_no'], 'string', 'max' => 50],
            [['account_name'], 'string', 'max' => 120],
            [['declaration_status'], 'string', 'max' => 3],
            [['sync_error'], 'string'],
            [['declaration_status'], 'compare', 'compareValue' => '1', 'message' => 'You must confirm the declaration before submitting your application.'],
            [['request_id'], 'unique'],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::class, 'targetAttribute' => ['bank_id' => 'brank_id']],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => BankBranch::class, 'targetAttribute' => ['branch_id' => 'branch_id']],
            [['refund_type'], 'exist', 'skipOnError' => true, 'targetClass' => RefundType::class, 'targetAttribute' => ['refund_type' => 'refund_type_id']],
            [['student_prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentProgCurriculum::class, 'targetAttribute' => ['student_prog_curriculum_id' => 'student_prog_curriculum_id']],
        ];
    }

    /**
     * Custom validation to ensure requested amount does not exceed the calculated limit.
     * @param string $attribute
     * @param array $params
     */
    public function validateAmountLimit($attribute, $params)
    {
        if ($this->max_amount !== null && $this->amount_requested > $this->max_amount) {
            $maxStr = Yii::$app->formatter->asCurrency($this->max_amount);
            $this->addError($attribute, "The requested amount cannot exceed the estimated refundable amount of {$maxStr}.");
        }
    }

    /**
     * @return bool
     */
    public function beforeValidate(): bool
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        if ($this->payment_method === 'mpesa') {
            $this->bank_id = null;
            $this->branch_id = null;
            $this->account_no = null;
        } elseif ($this->payment_method === 'bank') {
            // Do NOT nullify mobile_no here as it might be needed for contact,
            // but the conditional 'match' rule will skip it.
        }

        return true;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // Always set refund_status to NOT REFUNDED
        $this->refund_status = 'NOT REFUNDED';

        // Reset sync status whenever the record is changed
        $this->sync_status = 0;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'request_id' => 'Request ID',
            'student_prog_curriculum_id' => 'Student Prog Curriculum ID',
            'mobile_no' => 'Mobile No',
            'email' => 'Email',
            'application_date' => 'Application Date',
            'refund_status' => 'Refund Status',
            'account_no' => 'Account No',
            'account_name' => 'Account Name',
            'bank_id' => 'Bank',
            'branch_id' => 'Branch',
            'passport_id' => 'Passport ID',
            'declaration_status' => 'Declaration',
            'amount_requested' => 'Amount Requested',
            'approval_status' => 'Approval Status',
            'voucher_no' => 'Voucher No',
            'amount_approved' => 'Amount Approved',
            'refund_type' => 'Refund Type',
            'payment_method' => 'Payment Option',
            'sync_status' => 'Sync Status',
            'sync_error' => 'Sync Error',
            'last_synced_at' => 'Last Synced At',
        ];
    }

    /**
     * Gets query for [[Bank]].
     *
     * @return ActiveQuery
     */
    public function getBank(): ActiveQuery
    {
        return $this->hasOne(Bank::class, ['brank_id' => 'bank_id']);
    }

    /**
     * Gets query for [[Branch]].
     *
     * @return ActiveQuery
     */
    public function getBranch(): ActiveQuery
    {
        return $this->hasOne(BankBranch::class, ['branch_id' => 'branch_id']);
    }

    /**
     * Gets query for [[RefundType]].
     *
     * @return ActiveQuery
     */
    public function getRefundType(): ActiveQuery
    {
        return $this->hasOne(RefundType::class, ['refund_type_id' => 'refund_type']);
    }

    /**
     * Gets query for [[StudentProgCurriculum]].
     *
     * @return ActiveQuery
     */
    public function getStudentProgCurriculum(): ActiveQuery
    {
        return $this->hasOne(StudentProgCurriculum::class, ['student_prog_curriculum_id' => 'student_prog_curriculum_id']);
    }

    /**
     * Gets query for [[ApprovalProcesses]].
     *
     * @return ActiveQuery
     */
    public function getApprovalProcesses(): ActiveQuery
    {
        return $this->hasMany(ApprovalProcess::class, ['request_id' => 'request_id']);
    }
}
