<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.fss_prog_curr_charges".
 *
 * @property int $charge_type_id
 * @property int|null $prog_curr_id
 * @property float|null $amount_charged
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int|null $fee_code
 * @property int|null $acad_session_id
 * @property int|null $billing_frequency_id
 * @property int|null $currency_id
 * @property int|null $level_of_study
 * @property int|null $semester
 */
class ProgCurrCharge extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.fss_prog_curr_charges';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['charge_type_id'], 'required'],
            [['charge_type_id', 'prog_curr_id', 'fee_code', 'acad_session_id', 'billing_frequency_id', 'currency_id', 'level_of_study', 'semester'], 'default', 'value' => null],
            [['charge_type_id', 'prog_curr_id', 'fee_code', 'acad_session_id', 'billing_frequency_id', 'currency_id', 'level_of_study', 'semester'], 'integer'],
            [['amount_charged'], 'number'],
            [['start_date', 'end_date'], 'safe'],
            [['charge_type_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'charge_type_id' => 'Charge Type ID',
            'prog_curr_id' => 'Prog Curr ID',
            'amount_charged' => 'Amount Charged',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'fee_code' => 'Fee Code',
            'acad_session_id' => 'Acad Session ID',
            'billing_frequency_id' => 'Billing Frequency ID',
            'currency_id' => 'Currency ID',
            'level_of_study' => 'Level Of Study',
            'semester' => 'Semester',
        ];
    }
}
