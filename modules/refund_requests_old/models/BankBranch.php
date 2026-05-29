<?php

namespace app\modules\caution_refund\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_bank_branch".
 *
 * @property int $branch_id
 * @property int $bank_id
 * @property string $branch_name
 * @property string|null $branch_code
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
        return 'smisportal.sm_bank_branch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bank_id', 'branch_name'], 'required'],
            [['bank_id'], 'default', 'value' => null],
            [['bank_id'], 'integer'],
            [['branch_name'], 'string', 'max' => 100],
            [['branch_code'], 'string', 'max' => 20],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::class, 'targetAttribute' => ['bank_id' => 'bank_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'branch_id' => 'Branch ID',
            'bank_id' => 'Bank ID',
            'branch_name' => 'Branch Name',
            'branch_code' => 'Branch Code',
        ];
    }

    /**
     * Gets query for [[Bank]].
     *
     * @return ActiveQuery
     */
    public function getBank(): ActiveQuery
    {
        return $this->hasOne(Bank::class, ['bank_id' => 'bank_id']);
    }
}
