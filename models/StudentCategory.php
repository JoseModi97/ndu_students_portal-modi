<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use JetBrains\PhpStorm\ArrayShape;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_student_category".
 *
 * @property int $std_category_id
 * @property string $std_category_name
 */
class StudentCategory extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_student_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['std_category_name'], 'required'],
            [['std_category_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['std_category_id' => "string", 'std_category_name' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'std_category_id' => 'Std Category ID',
            'std_category_name' => 'Std Category Name',
        ];
    }
}
