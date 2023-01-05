<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use JetBrains\PhpStorm\ArrayShape;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_student_programme_curriculum".
 *
 * @property int $student_prog_curriculum_id
 * @property int $student_id
 * @property string $registration_number
 * @property int $prog_curriculum_id
 * @property int $student_category_id
 * @property int $adm_refno
 * @property int $status_id
 *
 * @property StudentIdRequest[] $studentIdRequests
 * @property ProgrammeCurriculum $programmeCurriculum
 * @property Student $student
 * @property StudentCategory $studentCategory
 * @property-read AdmittedStudent $admittedStudent
 * @property StudentStatus $studentStatus
 */
class StudentProgramme extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_student_programme_curriculum';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['student_prog_curriculum_id', 'student_id', 'registration_number', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'required'],
            [['student_prog_curriculum_id', 'student_id', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'default', 'value' => null],
            [['student_prog_curriculum_id', 'student_id', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'integer'],
            [['registration_number'], 'string', 'max' => 20],
            [['student_prog_curriculum_id'], 'unique'],
            [['prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgrammeCurriculum::class, 'targetAttribute' => ['prog_curriculum_id' => 'prog_curriculum_id']],
            [['adm_refno'], 'exist', 'skipOnError' => true, 'targetClass' => AdmittedStudent::class, 'targetAttribute' => ['adm_refno' => 'adm_refno']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::class, 'targetAttribute' => ['student_id' => 'student_id']],
            [['student_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentCategory::class, 'targetAttribute' => ['student_category_id' => 'std_category_id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentStatus::class, 'targetAttribute' => ['status_id' => 'status_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['student_prog_curriculum_id' => "string", 'student_id' => "string", 'registration_number' => "string",
        'prog_curriculum_id' => "string", 'student_category_id' => "string", 'adm_refno' => "string", 'status_id' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'student_prog_curriculum_id' => 'Student Prog Curriculum ID',
            'student_id' => 'Student ID',
            'registration_number' => 'Registration Number',
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'student_category_id' => 'Student Category ID',
            'adm_refno' => 'Adm Refno',
            'status_id' => 'Status ID',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAdmittedStudent(): ActiveQuery
    {
        return $this->hasOne(AdmittedStudent::class, ['adm_refno' => 'adm_refno']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStudent(): ActiveQuery
    {
        return $this->hasOne(Student::class, ['student_id' => 'student_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStudentCategory(): ActiveQuery
    {
        return $this->hasOne(StudentCategory::class, ['std_category_id' => 'student_category_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStudentStatus(): ActiveQuery
    {
        return $this->hasOne(StudentStatus::class, ['status_id' => 'status_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProgrammeCurriculum(): ActiveQuery
    {
        return $this->hasOne(ProgrammeCurriculum::class, ['prog_curriculum_id' => 'prog_curriculum_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStudentIdRequests(): ActiveQuery
    {
        return $this->hasMany(StudentIdRequest::class, ['student_prog_curr_id' => 'student_prog_curriculum_id']);
    }
}
