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
use Throwable;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

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
        try{
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
                'pagination' => false
            ]);

            return $this->render('index', [
                'title' => $this->createPageTitle('course registration'),
                'timetableCoursesProvider' => $timetableCoursesProvider,
                'studentSemesterSessionId' => $studentSemSessProgress['student_semester_session_id']
            ]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Do provisional registration
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
                if($this->isRegistrationConfirmed($course['timetableId'])){
                    continue;
                }

                $courseRegStatus = CourseRegistrationStatus::find()->where(['course_reg_status_name' => 'PROVISIONAL'])
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

                if(!$courseReg->save()){
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
     * @return Response
     */
    public function actionSelectedExamTypes(): Response
    {
        try{
            $timetableIds = Yii::$app->request->get()['timetableIds'];

            // Get the last academic session semester a student joined
            $studentSemSessProgress = $this->getLatestAcademicSessionForAStudent();
            $studentSemesterSessionId = $studentSemSessProgress['student_semester_session_id'];

            $timetableExamTypes = [];
            foreach ($timetableIds as $timetableId){
                $courseReg = CourseRegistration::find()->select(['course_registration_type_id'])->where([
                    'student_semester_session_id' => $studentSemesterSessionId,
                    'timetable_id' => $timetableId
                ])->asArray()->one();

                if(empty($courseReg)){
                    continue;
                }

                $courseRegType = CourseRegistrationType::find()->select(['course_reg_type_code'])
                    ->where(['course_reg_type_id' => $courseReg['course_registration_type_id']])
                    ->asArray()->one();

                $timetableExamTypes[$timetableId] = $courseRegType['course_reg_type_code'];
            }
            return $this->asJson(['success' => true, 'examTypes' => $timetableExamTypes]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * Get confirmed courses
     * @return Response
     */
    public function actionConfirmed(): Response
    {
        try{
            $timetableIds = Yii::$app->request->get()['timetableIds'];
            $confirmedTimetableIds = [];
            foreach ($timetableIds as $timetableId){
                if($this->isRegistrationConfirmed($timetableId)){
                    $confirmedTimetableIds[] = $timetableId;
                }
            }
            return $this->asJson(['success' => true, 'confirmedTimetableIds' => $confirmedTimetableIds]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * Get courses that are not yet confirmed
     * @throws ServerErrorHttpException
     */
    public function actionProvisional(): string
    {
        try{
            // Get the last academic session semester a student joined
            $studentSemSessProgress = $this->getLatestAcademicSessionForAStudent();
            $studentSemesterSessionId = $studentSemSessProgress['student_semester_session_id'];

            $timetableCourses = ProgrammeCurriculumTimetable::find()->alias('tt')
                ->select([
                    'tt.timetable_id',
                    'tt.exam_date',
                    'tt.exam_venue',
                    'tt.exam_mode',
                    'tt.prog_curriculum_course_id'
                ])
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
                ->joinWith(['courseRegistration cr' => function(ActiveQuery $q) {
                    $q->select([
                        'cr.student_course_reg_id',
                        'cr.timetable_id',
                    ]);
                }], true, 'INNER JOIN')
                ->where(['cr.student_semester_session_id' => $studentSemesterSessionId])
                ->joinWith(['courseRegistration.status st' => function(ActiveQuery $q) {
                    $q->select([
                        'st.course_reg_status_id',
                    ]);
                }], true, 'INNER JOIN')
                ->andWhere(['st.course_reg_status_name' => 'PROVISIONAL'])
                ->asArray()
                ->all();

            $timetableCoursesProvider = new ArrayDataProvider([
                'allModels' => $timetableCourses,
                'sort' => false,
                'pagination' => false
            ]);

            return $this->render('confirm', [
                'title' => $this->createPageTitle('confirm course registration'),
                'timetableCoursesProvider' => $timetableCoursesProvider,
                'studentSemesterSessionId' => $studentSemSessProgress['student_semester_session_id']
            ]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Confirm course registration
     * @return Response
     */
    public function actionConfirm(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $timetableIds = Yii::$app->request->post()['timetableIds'];

            // Get the last academic session semester a student joined
            $studentSemSessProgress = $this->getLatestAcademicSessionForAStudent();
            $studentSemesterSessionId = $studentSemSessProgress['student_semester_session_id'];

            $courseRegStatus = CourseRegistrationStatus::find()->select(['course_reg_status_id'])
                ->where(['course_reg_status_name' => 'CONFIRMED'])->asArray()->one();

            foreach ($timetableIds as $timetableId){
                $courseReg = CourseRegistration::find()->where([
                    'student_semester_session_id' => $studentSemesterSessionId,
                    'timetable_id' => $timetableId
                ])->one();

                $courseReg->course_reg_status_id = $courseRegStatus['course_reg_status_id'];
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
                        throw new Exception('Course registration confirmation failed.');
                    }
                }
            }

            $transaction->commit();
            $this->setFlash('success', 'Course registration confirmation',
                'Course registration confirmation done successfully.');

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
     * @return Response
     * @throws Throwable
     */
    public function actionDrop(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $timetableIds = Yii::$app->request->post()['timetableIds'];

            // Get the last academic session semester a student joined
            $studentSemSessProgress = $this->getLatestAcademicSessionForAStudent();
            $studentSemesterSessionId = $studentSemSessProgress['student_semester_session_id'];

            foreach ($timetableIds as $timetableId){
                $courseReg = CourseRegistration::find()->where([
                    'student_semester_session_id' => $studentSemesterSessionId,
                    'timetable_id' => $timetableId
                ])->one();

                if(empty($courseReg) || $this->isRegistrationConfirmed($timetableId)){
                    continue;
                }

                if(!$courseReg->delete()){
                    throw new Exception('Failed to drop courses.');
                }
            }

            $transaction->commit();
            $this->setFlash('success', 'Drop courses', 'Courses dropped successfully.');
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

    /**
     * Check if a course registration is confirmed
     * @param string $timetableId
     * @return bool
     */
    private function isRegistrationConfirmed(string $timetableId): bool
    {
        // Get the last academic session semester a student joined
        $studentSemSessProgress = $this->getLatestAcademicSessionForAStudent();
        $studentSemesterSessionId = $studentSemSessProgress['student_semester_session_id'];

        $courseReg = CourseRegistration::find()->select(['course_reg_status_id'])->where([
            'student_semester_session_id' => $studentSemesterSessionId,
            'timetable_id' => $timetableId
        ])->asArray()->one();

        if(!empty($courseReg)){
            $courseRegStatus = CourseRegistrationStatus::find()->select(['course_reg_status_name'])
                ->where(['course_reg_status_id' => $courseReg['course_reg_status_id']])->asArray()->one();
            $status = $courseRegStatus['course_reg_status_name'];
            if ($status === 'CONFIRMED') {
                return true;
            }
        }

        return false;
    }
}