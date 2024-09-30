<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.fss_fee_items".
 *
 * @property int $fee_code
 * @property string|null $fee_description
 * @property int|null $priority
 * @property string|null $naration
 * @property string|null $fee_type
 * @property string|null $publish
 * @property string|null $fee_code_alias
 */
class FeeItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.fss_fee_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fee_code'], 'required'],
            [['fee_code', 'priority'], 'default', 'value' => null],
            [['fee_code', 'priority'], 'integer'],
            [['fee_description', 'fee_code_alias'], 'string', 'max' => 50],
            [['naration'], 'string', 'max' => 150],
            [['fee_type'], 'string', 'max' => 15],
            [['publish'], 'string', 'max' => 10],
            [['fee_code_alias'], 'unique'],
            [['fee_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fee_code' => 'Fee Code',
            'fee_description' => 'Fee Description',
            'priority' => 'Priority',
            'naration' => 'Naration',
            'fee_type' => 'Fee Type',
            'publish' => 'Publish',
            'fee_code_alias' => 'Fee Code Alias',
        ];
    }
}
