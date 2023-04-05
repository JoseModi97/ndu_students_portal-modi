<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/30/2023
 * @time: 11:37 AM
 */

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_semester_code".
 *
 * @property int $semester_code
 * @property string $semster_name
 */
class SemesterCode extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_semester_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['semester_code', 'semster_name'], 'required'],
            [['semester_code'], 'default', 'value' => null],
            [['semester_code'], 'integer'],
            [['semster_name'], 'string', 'max' => 30],
            [['semester_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'semester_code' => 'Semester Code',
            'semster_name' => 'Semster Name',
        ];
    }
}