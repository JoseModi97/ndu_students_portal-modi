<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.fss_invoice_details".
 *
 * @property int $invoice_detail_id
 * @property string|null $invoice_id
 * @property string $trans_date
 * @property float $amount
 * @property string $user_id
 * @property string $invoice_detail_desc
 * @property string|null $charge_type_id
 * @property string|null $trans_code
 * @property bool $sync_status
 * @property string $last_updated
 */
class InvoiceDetail extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.fss_invoice_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['trans_date', 'amount', 'user_id', 'invoice_detail_desc', 'last_updated'], 'required'],
            [['trans_date', 'last_updated'], 'safe'],
            [['amount'], 'number'],
            [['sync_status'], 'boolean'],
            [['invoice_id', 'charge_type_id'], 'string', 'max' => 100],
            [['user_id'], 'string', 'max' => 30],
            [['invoice_detail_desc'], 'string', 'max' => 50],
            [['trans_code'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'invoice_detail_id' => 'Invoice Detail ID',
            'invoice_id' => 'Invoice ID',
            'trans_date' => 'Trans Date',
            'amount' => 'Amount',
            'user_id' => 'User ID',
            'invoice_detail_desc' => 'Invoice Detail Desc',
            'charge_type_id' => 'Charge Type ID',
            'trans_code' => 'Trans Code',
            'sync_status' => 'Sync Status',
            'last_updated' => 'Last Updated',
        ];
    }
}
