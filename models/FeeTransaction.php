<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.fss_fee_transactions".
 *
 * @property string $trans_id
 * @property int $academic_progress_id
 * @property string $trans_date
 * @property string $trans_type
 * @property float $trans_amount
 * @property string|null $trans_desc
 * @property string $user_id
 * @property string|null $receipt_status
 * @property float|null $exchange_rate
 * @property string $progress_code
 * @property bool $sync_status
 * @property int $fee_trans_id
 */
class FeeTransaction extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.fss_fee_transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['trans_id', 'academic_progress_id', 'trans_date', 'trans_type', 'trans_amount', 'user_id', 'progress_code'], 'required'],
            [['academic_progress_id'], 'default', 'value' => null],
            [['academic_progress_id'], 'integer'],
            [['trans_date'], 'safe'],
            [['trans_amount', 'exchange_rate'], 'number'],
            [['sync_status'], 'boolean'],
            [['trans_id', 'trans_desc'], 'string', 'max' => 150],
            [['trans_type'], 'string', 'max' => 25],
            [['user_id'], 'string', 'max' => 30],
            [['receipt_status'], 'string', 'max' => 15],
            [['progress_code'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'trans_id' => 'Trans ID',
            'academic_progress_id' => 'Academic Progress ID',
            'trans_date' => 'Trans Date',
            'trans_type' => 'Trans Type',
            'trans_amount' => 'Trans Amount',
            'trans_desc' => 'Trans Desc',
            'user_id' => 'User ID',
            'receipt_status' => 'Receipt Status',
            'exchange_rate' => 'Exchange Rate',
            'progress_code' => 'Progress Code',
            'sync_status' => 'Sync Status',
            'fee_trans_id' => 'Fee Trans ID',
        ];
    }
}
