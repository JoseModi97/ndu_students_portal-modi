<?php

namespace app\modules\caution_refund\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_bank".
 *
 * @property int $bank_id
 * @property string $bank_name
 * @property string|null $bank_code
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
        return 'smisportal.sm_bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bank_name'], 'required'],
            [['bank_name'], 'string', 'max' => 100],
            [['bank_code'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'bank_id' => 'Bank ID',
            'bank_name' => 'Bank Name',
            'bank_code' => 'Bank Code',
        ];
    }

    /**
     * Gets query for [[BankBranches]].
     *
     * @return ActiveQuery
     */
    public function getBankBranches(): ActiveQuery
    {
        return $this->hasMany(BankBranch::class, ['bank_id' => 'bank_id']);
    }
}
