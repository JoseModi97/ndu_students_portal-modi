<?php

namespace app\modules\refund_requests\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.fss_banks".
 *
 * @property int $brank_id
 * @property string|null $bank_code
 * @property string|null $bank_name
 * @property int|null $status
 *
 * @property BankBranch[] $bankBranches
 */
class Bank extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.fss_banks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['bank_code'], 'string', 'max' => 40],
            [['bank_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'brank_id' => 'Bank ID',
            'bank_code' => 'Bank Code',
            'bank_name' => 'Bank Name',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[BankBranches]].
     *
     * @return ActiveQuery
     */
    public function getBankBranches(): ActiveQuery
    {
        return $this->hasMany(BankBranch::class, ['bank_code' => 'bank_code']);
    }
}
