<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/28/2023
 * @time: 11:29 AM
 */

namespace app\controllers;

use app\helpers\SmisHelper;
use app\models\CourseRegistration;
use app\models\CourseRegistrationStatus;
use app\models\CourseRegistrationType;
use app\models\Marksheet;
use app\models\ProgCurrSemester;
use app\models\ProgCurrSemesterGroup;
use app\models\ProgrammeCurriculumTimetable;
use app\models\Student;
use app\models\StudentProgCurriculum;
use app\models\StudentSemesterSessionProgress;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\web\Response;

class CoursesController extends BaseController
{
    /**
     * Configure controller behaviours
     * @return array[]
     */
    #[ArrayShape(['access' => "array"])]
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function actionIndex(): string
    {
        // Get the last academic session semester a student joined
        $studentSemSessProgress = $this->getLatestAcademicSessionForAStudent();
        $academicSessionSemesterId = $studentSemSessProgress['acad_session_semester_id'];

        // Check if the student's semester is ongoing. i.e. the current date is within the semester's start and end dates.
        $progCurrSemester = ProgCurrSemester::find()->select(['prog_curriculum_semester_id'])
            ->where(['acad_session_semester_id' => $academicSessionSemesterId])->asArray()->one();

        $currentDate = SmisHelper::formatDate('now', 'Y-m-d');

        $programmeCurriculumSemGroup = ProgCurrSemesterGroup::find()->select(['prog_curriculum_sem_group_id'])
            ->where(['prog_curriculum_semester_id' => $progCurrSemester['prog_curriculum_semester_id']])
            ->andWhere(['<=', 'start_date', $currentDate])
            ->andWhere(['>=', 'end_date', $currentDate])
            ->asArray()->one();

        /**
         * If semester has ended i.e. a student is trying to register for courses in a semester whose end date is behind
         * the current date, inform them to join an active session first.
         * Session are created by the admin before placing a student in one.
         */
        if(empty($programmeCurriculumSemGroup)){
            /**
             * @todo redirect back
             */
            echo 'Join active session';
        }

        $timetableCourses = ProgrammeCurriculumTimetable::find()->alias('tt')
            ->select([
                'tt.timetable_id',
                'tt.exam_date',
                'tt.exam_venue',
                'tt.exam_mode',
                'tt.prog_curriculum_course_id'
            ])
            ->where(['tt.prog_curriculum_sem_group_id' => $programmeCurriculumSemGroup['prog_curriculum_sem_group_id']])
            ->joinWith(['examMode em' => function(ActiveQuery $q) {
                $q->select([
                    'em.exam_mode_id',
                    'em.exam_mode_name'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['programmeCurriculumCourse pcc' => function (ActiveQuery $q) {
                $q->select([
                    'pcc.prog_curriculum_course_id',
                    'pcc.course_id'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['programmeCurriculumCourse.course cse' => function (ActiveQuery $q) {
                $q->select([
                    'cse.course_id',
                    'cse.course_code',
                    'cse.course_name'
                ]);
            }], true, 'INNER JOIN')
            ->asArray()
            ->all();

        $timetableCoursesProvider = new ArrayDataProvider([
            'allModels' => $timetableCourses,
            'sort' => false,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'title' => $this->createPageTitle('course registration'),
            'timetableCoursesProvider' => $timetableCoursesProvider,
            'studentSemesterSessionId' => $studentSemSessProgress['student_semester_session_id']
        ]);
    }

    /**
     * @return Response
     */
    public function actionRegister(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $courses = $post['courses'];

            $studentSemSessProgress = $this->getLatestAcademicSessionForAStudent();
            $studentSemesterSessionId = $studentSemSessProgress['student_semester_session_id'];

            $studentProgCurr = StudentProgCurriculum::find()->select(['student_id'])
                ->where(['adm_refno' => Yii::$app->user->identity->adm_refno])->asArray()->one();

            $student = Student::find()->select(['student_number'])
                ->where(['student_id' => $studentProgCurr['student_id']])->asArray()->one();

            foreach ($courses as $course){
                $courseRegStatus = CourseRegistrationStatus::find()->where(['course_reg_status_name' => 'ACTIVE'])
                    ->asArray()->one();

                $courseRegType = CourseRegistrationType::find()->where(['course_reg_type_code' => $course['examType']])
                    ->asArray()->one();

                $courseReg = CourseRegistration::find()->where([
                    'timetable_id' => $course['timetableId'],
                    'student_semester_session_id' => $studentSemesterSessionId
                ])->one();

                if(empty($courseReg)){
                    $courseReg = new CourseRegistration();
                }

                $courseReg->timetable_id = $course['timetableId'];
                $courseReg->student_semester_session_id = $studentSemesterSessionId;
                $courseReg->course_registration_type_id = $courseRegType['course_reg_type_id'];
                $courseReg->registration_date = SmisHelper::formatDate('now', 'Y-m-d');
                $courseReg->course_reg_status_id = $courseRegStatus['course_reg_status_id'];
                $courseReg->source_ipaddress = '';
                $courseReg->userid = $student['student_number'];

                if($courseReg->save()){
                    $marksheet = Marksheet::find()->where(['student_course_reg_id' => $courseReg->student_course_reg_id])->one();

                    if(empty($marksheet)){
                        $marksheet = new Marksheet();
                    }

                    $marksheet->student_course_reg_id = $courseReg->student_course_reg_id;
                    if(!$marksheet->save()){
                        if(!$marksheet->validate()){
                            throw new Exception(SmisHelper::getModelErrors($marksheet->getErrors()));
                        }else{
                            throw new Exception('Course marksheet registration failed.');
                        }
                    }
                }else{
                    if(!$courseReg->validate()){
                        throw new Exception(SmisHelper::getModelErrors($courseReg->getErrors()));
                    }else{
                        throw new Exception('Course registration failed.');
                    }
                }
            }

            $transaction->commit();
            $this->setFlash('success', 'Course registration', 'Course registration done successfully.');
            return $this->redirect(['/courses']);
        }catch (Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * Get the last academic session semester a student joined
     * @return array|ActiveRecord|null
     */
    private function getLatestAcademicSessionForAStudent(): array|ActiveRecord|null
    {
        $admRefNo = Yii::$app->user->identity->adm_refno;
        $studentProgCurr = StudentProgCurriculum::find()->select(['student_prog_curriculum_id'])
            ->where(['adm_refno' => $admRefNo])->asArray()->one();

        // Get the last academic session semester a student joined
        return StudentSemesterSessionProgress::find()->alias('sp')
            ->select([
                'sp.student_semester_session_id',
                'sp.academic_progress_id',
                'sp.acad_session_semester_id'
            ])
            ->joinWith(['academicProgress ap' => function (ActiveQuery $q) {
                $q->select(['ap.academic_progress_id']);
            }], true, 'INNER JOIN')
            ->where(['ap.student_prog_curriculum_id' => $studentProgCurr['student_prog_curriculum_id']])
            ->orderBy(['sp.student_semester_session_id' => SORT_DESC])
            ->asArray()
            ->one();
    }
}