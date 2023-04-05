<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/30/2023
 * @time: 11:38 AM
 */

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_semester_type".
 *
 * @property int $sem_type_id
 * @property string $sem_type
 */
class SemesterType extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_semester_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['semester_type'], 'required'],
            [['semester_type'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'semester_type_id' => 'Semester Type ID',
            'semester_type  ' => 'Semester Type',
        ];
    }
}