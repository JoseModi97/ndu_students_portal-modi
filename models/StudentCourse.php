<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.ex_student_courses".
 *
 * @property string $course_registration_id
 * @property string $progress_code
 * @property string $course_id
 * @property string $examtype_code
 * @property float|null $final_mark
 * @property string|null $grade
 * @property string|null $pay_per_course
 * @property string|null $result_status
 * @property string|null $last_update
 * @property string|null $userid
 * @property string|null $group_code
 * @property string|null $lock_status
 * @property int|null $level_of_study
 * @property int|null $final
 * @property string|null $mrksheet_id
 * @property float|null $course_mark
 * @property float|null $exam_mark
 * @property string|null $remarks
 * @property int|null $publish_status
 * @property int $student_courses_id
 */
class StudentCourse extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.ex_student_courses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['course_registration_id', 'progress_code', 'course_id', 'examtype_code', 'student_courses_id'], 'required'],
            [['final_mark', 'course_mark', 'exam_mark'], 'number'],
            [['last_update'], 'safe'],
            [['level_of_study', 'final', 'publish_status', 'student_courses_id'], 'default', 'value' => null],
            [['level_of_study', 'final', 'publish_status', 'student_courses_id'], 'integer'],
            [['course_registration_id'], 'string', 'max' => 100],
            [['progress_code'], 'string', 'max' => 50],
            [['course_id', 'result_status', 'userid'], 'string', 'max' => 20],
            [['examtype_code'], 'string', 'max' => 10],
            [['grade', 'lock_status'], 'string', 'max' => 8],
            [['pay_per_course'], 'string', 'max' => 1],
            [['group_code'], 'string', 'max' => 15],
            [['mrksheet_id'], 'string', 'max' => 60],
            [['remarks'], 'string', 'max' => 240],
            [['student_courses_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'course_registration_id' => 'Course Registration ID',
            'progress_code' => 'Progress Code',
            'course_id' => 'Course ID',
            'examtype_code' => 'Examtype Code',
            'final_mark' => 'Final Mark',
            'grade' => 'Grade',
            'pay_per_course' => 'Pay Per Course',
            'result_status' => 'Result Status',
            'last_update' => 'Last Update',
            'userid' => 'Userid',
            'group_code' => 'Group Code',
            'lock_status' => 'Lock Status',
            'level_of_study' => 'Level Of Study',
            'final' => 'Final',
            'mrksheet_id' => 'Mrksheet ID',
            'course_mark' => 'Course Mark',
            'exam_mark' => 'Exam Mark',
            'remarks' => 'Remarks',
            'publish_status' => 'Publish Status',
            'student_courses_id' => 'Student Courses ID',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProgrammeCurriculumTimetable(): ActiveQuery
    {
        return $this->hasOne(ProgrammeCurriculumTimetable::class, ['mrksheet_id' => 'mrksheet_id']);
    }
}
