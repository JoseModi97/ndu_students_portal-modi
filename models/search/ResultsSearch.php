<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/29/2023
 * @time: 10:01 PM
 */

namespace app\models\search;

use app\models\StudentCourse;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class ResultsSearch extends StudentCourse
{
    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @param array $moreParams
     * @return ActiveDataProvider
     */
    public function search(array $params, array $moreParams = []): ActiveDataProvider
    {
        $regNumber = $moreParams['regNumber'];

        $query = StudentCourse::find()->alias('sc')
            ->select([
                'sc.student_courses_id',
                'sc.mrksheet_id',
                'sc.grade'
            ])
            ->where(['like', 'sc.course_registration_id', $regNumber . '%', false])
            ->joinWith(['programmeCurriculumTimetable pct' => function(ActiveQuery $q){
                $q->select([
                    'pct.timetable_id',
                    'pct.mrksheet_id',
                    'pct.prog_curriculum_course_id',
                    'pct.prog_curriculum_sem_group_id'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['programmeCurriculumTimetable.programmeCurriculumCourse pcc' => function (ActiveQuery $q) {
                $q->select([
                    'pcc.prog_curriculum_course_id',
                    'pcc.course_id'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['programmeCurriculumTimetable.programmeCurriculumCourse.course cse' => function (ActiveQuery $q) {
                $q->select([
                    'cse.course_id',
                    'cse.course_code',
                    'cse.course_name'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['programmeCurriculumTimetable.programmeCurriculumSemesterGroup pcsg' => function(ActiveQuery $q){
                $q->select([
                    'pcsg.prog_curriculum_sem_group_id',
                    'pcsg.prog_curriculum_semester_id',
                    'pcsg.study_centre_group_id'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['programmeCurriculumTimetable.programmeCurriculumSemesterGroup.progCurrSemester ps' => function(ActiveQuery $q){
                $q->select([
                    'ps.prog_curriculum_semester_id',
                    'ps.acad_session_semester_id',
                    'prog_curriculum_id'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['programmeCurriculumTimetable.programmeCurriculumSemesterGroup.progCurrSemester.academicSessionSemester ass' => function(ActiveQuery $q){
                $q->select([
                    'ass.acad_session_semester_id',
                    'ass.acad_session_id',
                    'ass.semester_code',
                    'ass.acad_session_semester_desc'
                ]);
            }], true, 'INNER JOIN')
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 100,
            ],
        ]);

        $this->load($params);

        if(!$this->validate()) {
            return $dataProvider;
        }

//        $query->orderBy(['nc.name_change_id' => SORT_DESC]);

        return $dataProvider;
    }
}