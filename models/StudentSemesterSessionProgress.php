<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_student_sem_session_progress".
 *
 * @property int $student_semester_session_id
 * @property int|null $semester_progress
 * @property string|null $registration_date
 * @property int $academic_progress_id
 * @property int $sem_progress_number The student semester progression..ie 1,2,3
 * @property int|null $billable
 * @property string $promotion_status
 * @property int $rep_status_id
 * @property int $prom_status_id
 * @property bool|null $reporting_sync_status
 */
class StudentSemesterSessionProgress extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_student_sem_session_progress';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['student_semester_session_id', 'academic_progress_id', 'sem_progress_number', 'promotion_status', 'rep_status_id', 'prom_status_id'], 'required'],
            [['student_semester_session_id', 'semester_progress', 'academic_progress_id', 'sem_progress_number', 'billable', 'rep_status_id', 'prom_status_id', 'acad_session_semester_id'], 'default', 'value' => null],
            [['student_semester_session_id', 'semester_progress', 'academic_progress_id', 'sem_progress_number', 'billable', 'rep_status_id', 'prom_status_id', 'acad_session_semester_id'], 'integer'],
            [['registration_date'], 'safe'],
            [['reporting_sync_status'], 'boolean'],
            [['promotion_status'], 'string', 'max' => 20],
            [['student_semester_session_id'], 'unique'],
            [['academic_progress_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicProgress::class, 'targetAttribute' => ['academic_progress_id' => 'academic_progress_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'student_semester_session_id' => 'Student Semester Session ID',
            'semester_progress' => 'Semester Progress',
            'registration_date' => 'Registration Date',
            'academic_progress_id' => 'Academic Progress ID',
            'sem_progress_number' => 'Sem Progress Number',
            'billable' => 'Billable',
            'promotion_status' => 'Promotion Status',
            'rep_status_id' => 'Rep Status ID',
            'prom_status_id' => 'Prom Status ID',
            'reporting_sync_status' => 'Reporting Sync Status',
            'acad_session_semester_id' => 'Acad Session Semester ID',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAcademicProgress(): ActiveQuery
    {
        return $this->hasOne(AcademicProgress::class, ['academic_progress_id' => 'academic_progress_id']);
    }
}
