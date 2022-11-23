<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "smisportal.sm_withdrawal_type".
 *
 * @property int $withdrawal_type_id
 * @property string $withrawal_type_name
 */
class SmWithdrawalType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.sm_withdrawal_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['withrawal_type_name'], 'required'],
            [['withrawal_type_name'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'withdrawal_type_id' => 'Withdrawal Type ID',
            'withrawal_type_name' => 'Withrawal Type Name',
        ];
    }

}
