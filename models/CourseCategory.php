<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.cr_course_category".
 *
 * @property int $category_id
 * @property string $category_name
 */
class CourseCategory extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.cr_course_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['category_id', 'category_name'], 'required'],
            [['category_id'], 'default', 'value' => null],
            [['category_id'], 'integer'],
            [['category_name'], 'string'],
            [['category_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'category_id' => 'Category ID',
            'category_name' => 'Category Name',
        ];
    }
}
