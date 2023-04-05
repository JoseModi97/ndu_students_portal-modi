<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_prog_curr_course".
 *
 * @property int $prog_curriculum_course_id
 * @property int $prog_curriculum_id
 * @property int $course_id
 * @property int $credit_factor
 * @property float $credit_hours
 * @property int $level_of_study
 * @property int|null $semester
 * @property int|null $prerequisite
 * @property string $status
 * @property bool|null $has_course_work
 */
class ProgrammeCurriculumCourse extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_prog_curr_course';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['prog_curriculum_course_id', 'prog_curriculum_id', 'course_id', 'credit_hours', 'level_of_study'], 'required'],
            [['prog_curriculum_course_id', 'prog_curriculum_id', 'course_id', 'credit_factor', 'level_of_study', 'semester', 'prerequisite'], 'default', 'value' => null],
            [['prog_curriculum_course_id', 'prog_curriculum_id', 'course_id', 'credit_factor', 'level_of_study', 'semester', 'prerequisite'], 'integer'],
            [['credit_hours'], 'number'],
            [['has_course_work'], 'boolean'],
            [['status'], 'string', 'max' => 10],
            [['prog_curriculum_course_id'], 'unique'],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::class, 'targetAttribute' => ['course_id' => 'course_id']],
            [['prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgrammeCurriculum::class, 'targetAttribute' => ['prog_curriculum_id' => 'prog_curriculum_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'prog_curriculum_course_id' => 'Prog Curriculum Course ID',
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'course_id' => 'Course ID',
            'credit_factor' => 'Credit Factor',
            'credit_hours' => 'Credit Hours',
            'level_of_study' => 'Level Of Study',
            'semester' => 'Semester',
            'prerequisite' => 'Prerequisite',
            'status' => 'Status',
            'has_course_work' => 'Has Course Work',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCourse(): ActiveQuery
    {
        return $this->hasOne(Course::class, ['course_id' => 'course_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProgrammeCurriculum(): ActiveQuery
    {
        return $this->hasOne(ProgrammeCurriculum::class, ['prog_curriculum_id' => 'prog_curriculum_id']);
    }
}
