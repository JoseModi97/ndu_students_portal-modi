<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use JetBrains\PhpStorm\ArrayShape;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.cr_class_groups".
 *
 * @property int $class_code
 * @property string|null $class_description
 */
class ClassGroup extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.cr_class_groups';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['class_code'], 'required'],
            [['class_code'], 'default', 'value' => null],
            [['class_code'], 'integer'],
            [['class_description'], 'string'],
            [['class_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['class_code' => "string", 'class_description' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'class_code' => 'Class Code',
            'class_description' => 'Class Description',
        ];
    }
}
