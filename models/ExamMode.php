<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.ex_mode".
 *
 * @property int $exam_mode_id
 * @property string $exam_mode_name
 */
class ExamMode extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.ex_mode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['exam_mode_id', 'exam_mode_name'], 'required'],
            [['exam_mode_id'], 'default', 'value' => null],
            [['exam_mode_id'], 'integer'],
            [['exam_mode_name'], 'string', 'max' => 30],
            [['exam_mode_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'exam_mode_id' => 'Exam Mode ID',
            'exam_mode_name' => 'Exam Mode Name',
        ];
    }
}
