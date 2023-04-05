<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/30/2023
 * @time: 11:48 AM
 */

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_study_centre_group".
 *
 * @property int $study_centre_group_id
 * @property int $study_centre_id
 * @property int $study_group_id
 * @property string $status
 */
class StudyCentreGroup extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_study_centre_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['study_centre_id', 'study_group_id'], 'required'],
            [['study_centre_id', 'study_group_id'], 'default', 'value' => null],
            [['study_centre_id', 'study_group_id'], 'integer'],
            [['status'], 'string', 'max' => 10],
            [['study_centre_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudyCentre::class, 'targetAttribute' => ['study_centre_id' => 'study_centre_id']],
            [['study_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudyGroup::class, 'targetAttribute' => ['study_group_id' => 'study_group_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'study_centre_group_id' => 'Study Centre Group ID',
            'study_centre_id' => 'Study Centre ID',
            'study_group_id' => 'Study Group ID',
            'status' => 'Status',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCenter(): ActiveQuery
    {
        return $this->hasOne(StudyCentre::class, ['study_centre_id' => 'study_centre_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGroup(): ActiveQuery
    {
        return $this->hasOne(StudyGroup::class, ['study_group_id' => 'study_group_id']);
    }
}