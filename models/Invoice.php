<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.fss_invoice".
 *
 * @property string $invoice_id
 * @property string $invoice_desc
 * @property string $invoice_date
 * @property string $user_id
 * @property string $last_update
 * @property string|null $invoice_status
 * @property float $amount
 * @property float $exchange_rate
 * @property bool $sync_status
 * @property int $id
 * @property string $reg_number
 * @property string $semester_id
 */
class Invoice extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.fss_invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['invoice_id', 'invoice_desc', 'invoice_date', 'user_id', 'last_update', 'amount', 'exchange_rate', 'reg_number', 'semester_id'], 'required'],
            [['invoice_date', 'last_update'], 'safe'],
            [['amount', 'exchange_rate'], 'number'],
            [['sync_status'], 'boolean'],
            [['invoice_id'], 'string', 'max' => 100],
            [['invoice_desc'], 'string', 'max' => 150],
            [['user_id', 'invoice_status'], 'string', 'max' => 30],
            [['reg_number'], 'string', 'max' => 50],
            [['semester_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'invoice_id' => 'Invoice ID',
            'invoice_desc' => 'Invoice Desc',
            'invoice_date' => 'Invoice Date',
            'user_id' => 'User ID',
            'last_update' => 'Last Update',
            'invoice_status' => 'Invoice Status',
            'amount' => 'Amount',
            'exchange_rate' => 'Exchange Rate',
            'sync_status' => 'Sync Status',
            'id' => 'ID',
            'reg_number' => 'Reg Number',
            'semester_id' => 'Semester ID',
        ];
    }
}
