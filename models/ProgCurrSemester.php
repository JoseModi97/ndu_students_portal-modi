<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/30/2023
 * @time: 11:41 AM
 */

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_prog_curr_semester".
 *
 * @property int $prog_curriculum_semester_id
 * @property int $prog_curriculum_id
 * @property int $acad_session_semester_id
 * @property int|null $semester_type_id teaching, supplementary
 *
 * @property AcademicSessionSemester $acadSessionSemester
 * @property ProgrammeCurriculum $progCurriculum
 * @property SemesterType $semesterType
 */
class ProgCurrSemester extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_prog_curr_semester';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['prog_curriculum_id', 'acad_session_semester_id'], 'required'],
            [['prog_curriculum_id', 'acad_session_semester_id', 'semester_type_id'], 'default', 'value' => null],
            [['prog_curriculum_id', 'acad_session_semester_id', 'semester_type_id'], 'integer'],
            [['acad_session_semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcademicSessionSemester::class, 'targetAttribute' => ['acad_session_semester_id' => 'acad_session_semester_id']],
            [['prog_curriculum_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgrammeCurriculum::class, 'targetAttribute' => ['prog_curriculum_id' => 'prog_curriculum_id']],
            [['semester_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SemesterType::class, 'targetAttribute' => ['semester_type_id' => 'sem_type_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'prog_curriculum_semester_id' => 'Prog Curriculum Semester ID',
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'acad_session_semester_id' => 'Acad Session Semester ID',
            'semester_type_id' => 'Semester Type ID',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAcademicSessionSemester(): ActiveQuery
    {
        return $this->hasOne(AcademicSessionSemester::class, ['acad_session_semester_id' => 'acad_session_semester_id']);
    }

    /**
     * Gets query for [[ProgCurriculum]].
     *
     * @return ActiveQuery
     */
    public function getProgCurriculum(): ActiveQuery
    {
        return $this->hasOne(ProgrammeCurriculum::class, ['prog_curriculum_id' => 'prog_curriculum_id']);
    }

    /**
     * Gets query for [[SemesterType]].
     *
     * @return ActiveQuery
     */
    public function getSemesterType(): ActiveQuery
    {
        return $this->hasOne(SemesterType::class, ['sem_type_id' => 'semester_type_id']);
    }
}