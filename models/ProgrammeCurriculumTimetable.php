<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.cr_prog_curr_timetable".
 *
 * @property int $timetable_id
 * @property int $prog_curriculum_course_id
 * @property int $prog_curriculum_sem_group_id
 * @property string|null $exam_date
 * @property int|null $exam_venue
 * @property int $exam_mode
 */
class ProgrammeCurriculumTimetable extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.cr_prog_curr_timetable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['timetable_id', 'prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_mode'], 'required'],
            [['timetable_id', 'prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_venue', 'exam_mode'], 'default', 'value' => null],
            [['timetable_id', 'prog_curriculum_course_id', 'prog_curriculum_sem_group_id', 'exam_venue', 'exam_mode'], 'integer'],
            [['exam_date'], 'safe'],
            [['timetable_id'], 'unique'],
            [['exam_mode'], 'exist', 'skipOnError' => true, 'targetClass' => ExamMode::class, 'targetAttribute' => ['exam_mode' => 'exam_mode_id']],
            [['prog_curriculum_sem_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgCurrSemesterGroup::class, 'targetAttribute' => ['prog_curriculum_sem_group_id' => 'prog_curriculum_sem_group_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'timetable_id' => 'Timetable ID',
            'prog_curriculum_course_id' => 'Prog Curriculum Course ID',
            'prog_curriculum_sem_group_id' => 'Prog Curriculum Sem Group ID',
            'exam_date' => 'Exam Date',
            'exam_venue' => 'Exam Venue',
            'exam_mode' => 'Exam Mode',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getExamMode(): ActiveQuery
    {
        return $this->hasOne(ExamMode::class, ['exam_mode_id' => 'exam_mode']);
    }

    /**
     * @return ActiveQuery
     */
    public function getExamVenue(): ActiveQuery
    {
        return $this->hasOne(Room::class, ['room_id' => 'exam_venue']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProgrammeCurriculumSemesterGroup(): ActiveQuery
    {
        return $this->hasOne(ProgCurrSemesterGroup::class, ['prog_curriculum_sem_group_id' => 'prog_curriculum_sem_group_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProgrammeCurriculumCourse(): ActiveQuery
    {
        return $this->hasOne(ProgrammeCurriculumCourse::class, ['prog_curriculum_course_id' => 'prog_curriculum_course_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCourseRegistration(): ActiveQuery
    {
        return $this->hasOne(CourseRegistration::class, ['timetable_id' => 'timetable_id']);
    }
}
