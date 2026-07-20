<?php

namespace app\modules\refund_requests\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.fss_bank_branches".
 *
 * @property int $branch_id
 * @property string|null $branch_code
 * @property string|null $branch_name
 * @property string|null $bank_code
 *
 * @property Bank $bank
 */
class BankBranch extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.fss_bank_branches';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['branch_code', 'bank_code'], 'string', 'max' => 40],
            [['branch_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'branch_id' => 'Branch ID',
            'branch_code' => 'Branch Code',
            'branch_name' => 'Branch Name',
            'bank_code' => 'Bank Code',
        ];
    }

    /**
     * Gets query for [[Bank]].
     *
     * @return ActiveQuery
     */
    public function getBank(): ActiveQuery
    {
        return $this->hasOne(Bank::class, ['bank_code' => 'bank_code']);
    }
}
