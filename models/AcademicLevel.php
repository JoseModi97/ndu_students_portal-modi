<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 4/17/2023
 * @time: 9:41 PM
 */

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_academic_levels".
 *
 * @property int $academic_level_id
 * @property int $academic_level
 * @property string $academic_level_name
 * @property int|null $academic_level_order
 * @property string $status
 */
class AcademicLevel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_academic_levels';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['academic_level', 'academic_level_name'], 'required'],
            [['academic_level', 'academic_level_order'], 'default', 'value' => null],
            [['academic_level', 'academic_level_order'], 'integer'],
            [['academic_level_name'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'academic_level_id' => 'Academic Level ID',
            'academic_level' => 'Academic Level',
            'academic_level_name' => 'Academic Level Name',
            'academic_level_order' => 'Academic Level Order',
            'status' => 'Status',
        ];
    }
}