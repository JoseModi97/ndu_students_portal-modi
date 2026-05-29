<?php

namespace app\modules\refund_requests\models;

use Yii;
use yii\db\Connection;

/**
 * This is the model class for table "smis.fss_refund_requests".
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
 */
class RefundRequestOfficial extends \yii\db\ActiveRecord
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
        return 'smis.fss_refund_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['account_no', 'account_name', 'bank_id', 'branch_id', 'voucher_no', 'amount_approved'], 'default', 'value' => null],
            [['request_id', 'student_prog_curriculum_id', 'mobile_no', 'email', 'application_date', 'refund_status', 'passport_id', 'declaration_status', 'amount_requested', 'approval_status', 'refund_type'], 'required'],
            [['request_id', 'student_prog_curriculum_id', 'bank_id', 'branch_id', 'voucher_no', 'refund_type'], 'integer'],
            [['application_date'], 'safe'],
            [['amount_requested', 'amount_approved'], 'number'],
            [['mobile_no'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 100],
            [['refund_status', 'passport_id'], 'string', 'max' => 30],
            [['account_no', 'approval_status'], 'string', 'max' => 50],
            [['account_name'], 'string', 'max' => 120],
            [['declaration_status'], 'string', 'max' => 3],
            [['declaration_status'], 'compare', 'compareValue' => '1', 'message' => 'Declaration must be confirmed before syncing the application.'],
            [['request_id'], 'unique'],
        ];
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
            'bank_id' => 'Bank ID',
            'branch_id' => 'Branch ID',
            'passport_id' => 'Passport ID',
            'declaration_status' => 'Declaration Status',
            'amount_requested' => 'Amount Requested',
            'approval_status' => 'Approval Status',
            'voucher_no' => 'Voucher No',
            'amount_approved' => 'Amount Approved',
            'refund_type' => 'Refund Type',
        ];
    }
}
