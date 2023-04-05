<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/30/2023
 * @time: 10:56 AM
 */

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_prog_curr_semester_group".
 *
 * @property int $prog_curriculum_sem_group_id
 * @property int $prog_curriculum_semester_id
 * @property int $study_centre_group_id
 * @property string $start_date
 * @property string|null $end_date
 * @property string|null $registration_deadline
 * @property string|null $display_date
 * @property int $programme_level
 * @property string $status
 */
class ProgCurrSemesterGroup extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_prog_curr_semester_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['prog_curriculum_semester_id', 'study_centre_group_id', 'start_date', 'programme_level'], 'required'],
            [['prog_curriculum_semester_id', 'study_centre_group_id', 'programme_level'], 'default', 'value' => null],
            [['prog_curriculum_semester_id', 'study_centre_group_id', 'programme_level'], 'integer'],
            [['start_date', 'end_date', 'registration_deadline', 'display_date'], 'safe'],
            [['status'], 'string', 'max' => 20],
            [['prog_curriculum_semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgCurrSemester::class, 'targetAttribute' => ['prog_curriculum_semester_id' => 'prog_curriculum_semester_id']],
            [['programme_level'], 'exist', 'skipOnError' => true, 'targetClass' => ProgrammeLevel::class, 'targetAttribute' => ['programme_level' => 'programme_level_id']],
            [['study_centre_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudyCentreGroup::class, 'targetAttribute' => ['study_centre_group_id' => 'study_centre_group_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'prog_curriculum_sem_group_id' => 'Prog Curriculum Sem Group ID',
            'prog_curriculum_semester_id' => 'Prog Curriculum Semester ID',
            'study_centre_group_id' => 'Study Centre Group ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'registration_deadline' => 'Registration Deadline',
            'display_date' => 'Display Date',
            'programme_level' => 'Programme Level',
            'status' => 'Status',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getStudyCentreGroup(): ActiveQuery
    {
        return $this->hasOne(StudyCentreGroup::class, ['study_centre_group_id' => 'study_centre_group_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProgCurrSemester(): ActiveQuery
    {
        return $this->hasOne(ProgCurrSemester::class, ['prog_curriculum_semester_id' => 'prog_curriculum_semester_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProgrammeLevel(): ActiveQuery
    {
        return $this->hasOne(ProgrammeLevel::class, ['programme_level_id' => 'programme_level']);
    }
}