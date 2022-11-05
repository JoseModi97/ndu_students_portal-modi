<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use JetBrains\PhpStorm\ArrayShape;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_intakes".
 *
 * @property int $intake_code
 * @property string $intake_name
 */
class Intake extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_intakes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['intake_name'], 'required'],
            [['intake_name'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['intake_code' => "string", 'intake_name' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'intake_code' => 'Intake Code',
            'intake_name' => 'Intake Name',
        ];
    }
}
