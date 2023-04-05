<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_programme_curriculum".
 *
 * @property int $prog_curriculum_id
 * @property int $prog_id
 * @property string $prog_curriculum_desc
 * @property int $duration ACADEMIC SESSIONS
 * @property int $pass_mark
 * @property int $annual_semesters
 * @property int|null $max_units_per_semester
 * @property string|null $average_type
 * @property string|null $award_rounding ROUNDOFF, TRUNCATE
 * @property string $start_date
 * @property string|null $end_date
 * @property string $prog_cur_prefix Programme curriculum prefix
 * @property string $date_created
 * @property int $grading_system_id
 * @property string $status
 * @property string|null $approval_date
 *
 * @property Programmes $program
 */
class ProgrammeCurriculum extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_programme_curriculum';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['prog_curriculum_id', 'prog_id', 'prog_curriculum_desc', 'duration', 'pass_mark', 'start_date', 'prog_cur_prefix', 'grading_system_id'], 'required'],
            [['prog_curriculum_id', 'prog_id', 'duration', 'pass_mark', 'annual_semesters', 'max_units_per_semester', 'grading_system_id'], 'default', 'value' => null],
            [['prog_curriculum_id', 'prog_id', 'duration', 'pass_mark', 'annual_semesters', 'max_units_per_semester', 'grading_system_id'], 'integer'],
            [['start_date', 'end_date', 'date_created', 'approval_date'], 'safe'],
            [['prog_curriculum_desc'], 'string', 'max' => 300],
            [['average_type', 'prog_cur_prefix', 'status'], 'string', 'max' => 10],
            [['award_rounding'], 'string', 'max' => 20],
            [['prog_curriculum_id'], 'unique'],
//            [['grading_system_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalExGradingSystem::class, 'targetAttribute' => ['grading_system_id' => 'grading_system_id']],
//            [['prog_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmisportalOrgProgrammes::class, 'targetAttribute' => ['prog_id' => 'prog_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'prog_id' => 'Prog ID',
            'prog_curriculum_desc' => 'Prog Curriculum Desc',
            'duration' => 'ACADEMIC SESSIONS',
            'pass_mark' => 'Pass Mark',
            'annual_semesters' => 'Annual Semesters',
            'max_units_per_semester' => 'Max Units Per Semester',
            'average_type' => 'Average Type',
            'award_rounding' => 'ROUNDOFF, TRUNCATE',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'prog_cur_prefix' => 'Programme curriculum prefix',
            'date_created' => 'Date Created',
            'grading_system_id' => 'Grading System ID',
            'status' => 'Status',
            'approval_date' => 'Approval Date',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProgram(): ActiveQuery
    {
        return $this->hasOne(Programmes::class, ['prog_id' => 'prog_id']);
    }
}
