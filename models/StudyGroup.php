<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/30/2023
 * @time: 11:47 AM
 */

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_study_group".
 *
 * @property int $study_group_id
 * @property string $study_group_name
 * @property string $status
 */
class StudyGroup extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_study_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['study_group_name'], 'required'],
            [['study_group_name'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'study_group_id' => 'Study Group ID',
            'study_group_name' => 'Study Group Name',
            'status' => 'Status',
        ];
    }
}
