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
            [['request_id', 'student_prog_curriculum_id', 'mobile_no', 'email', 'application_date', 'refund_status', 'passport_id', 'declaration_status', 'amount_requested', 'approval_status', 'refund_type', 'payment_method'], 'required'],
            [['payment_method'], 'in', 'range' => ['bank', 'mpesa']],
            [['payment_method'], 'validatePaymentDetails'],
            [['request_id', 'student_prog_curriculum_id', 'bank_id', 'branch_id', 'voucher_no', 'refund_type', 'sync_status'], 'integer'],
            [['application_date', 'last_synced_at'], 'safe'],
            [['amount_requested', 'amount_approved'], 'number'],
            [['mobile_no'], 'string', 'max' => 20],
            [['email', 'passport_id'], 'string', 'max' => 100],
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
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // Reset sync status whenever the record is changed
        $this->sync_status = 0;

        return true;
    }

    public function validatePaymentDetails($attribute, $params): void
    {
        if ($this->payment_method === 'bank') {
            if (empty($this->bank_id)) {
                $this->addError('bank_id', 'Please select a bank.');
            }

            if (empty($this->branch_id)) {
                $this->addError('branch_id', 'Please select a branch.');
            }

            if (trim((string)$this->account_no) === '') {
                $this->addError('account_no', 'Please enter the bank account number.');
            }
        }

        if ($this->payment_method === 'mpesa' && trim((string)$this->mobile_no) === '') {
            $this->addError('mobile_no', 'Please enter the M-PESA mobile number.');
        }
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
