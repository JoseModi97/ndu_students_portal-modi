<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/30/2023
 * @time: 11:35 AM
 */

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_academic_session".
 *
 * @property int $acad_session_id
 * @property string $acad_session_name
 * @property string|null $acad_session_desc
 * @property string $start_date
 * @property string $end_date
 */
class AcademicSession extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_academic_session';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['acad_session_name', 'start_date', 'end_date'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['acad_session_name'], 'string', 'max' => 50],
            [['acad_session_desc'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'acad_session_id' => 'Acad Session ID',
            'acad_session_name' => 'Acad Session Name',
            'acad_session_desc' => 'Acad Session Desc',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
    }
}