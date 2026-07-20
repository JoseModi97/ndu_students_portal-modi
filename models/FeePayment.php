<?php

namespace app\models;

use Yii;
use app\models\StudentProgrammeCurriculum;

/**
 * This is the model class for table "smis.fss_fee_payments".
 *
 * @property int $fee_paymt_id
 * @property string $receipt_no
 * @property string $trans_date
 * @property float $trans_amount
 * @property string|null $pay_mode
 * @property int $collection_point_id
 * @property string|null $user_id
 * @property string|null $entry_date
 * @property int $trans_id
 * @property string|null $academic_session
 * @property string|null $authorized_by
 * @property string|null $authorized_date
 * @property string|null $receipt_status
 * @property float|null $exchange_rate
 * @property int|null $student_prog_curriculum_id
 */
class FeePayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.fss_fee_payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['receipt_no', 'trans_date', 'trans_amount', 'collection_point_id', 'trans_id'], 'required'],
            [['collection_point_id', 'trans_id', 'student_prog_curriculum_id'], 'default', 'value' => null],
            [['fee_paymt_id', 'collection_point_id', 'trans_id', 'student_prog_curriculum_id','pay_mode'], 'integer'],
            [['trans_date', 'entry_date', 'authorized_date'], 'safe'],
            [['trans_amount', 'exchange_rate'], 'number'],
            [['receipt_no', 'user_id', 'authorized_by'], 'string', 'max' => 30],
            [['academic_session', 'receipt_status'], 'string', 'max' => 15],
            [['fee_paymt_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fee_paymt_id' => 'Fee Paymt ID',
            'receipt_no' => 'Receipt No',
            'trans_date' => 'Trans Date',
            'trans_amount' => 'Trans Amount',
            'pay_mode' => 'Pay Mode',
            'collection_point_id' => 'Collection Point ID',
            'user_id' => 'User ID',
            'entry_date' => 'Entry Date',
            'trans_id' => 'Trans ID',
            'academic_session' => 'Academic Session',
            'authorized_by' => 'Authorized By',
            'authorized_date' => 'Authorized Date',
            'receipt_status' => 'Receipt Status',
            'exchange_rate' => 'Exchange Rate',
            'student_prog_curriculum_id' => 'Student Prog Curriculum ID',
        ];
    }
}
