<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/28/2023
 * @time: 11:29 AM
 */

namespace app\controllers;

use app\helpers\SmisHelper;
use app\models\AcademicLevel;
use app\models\AcademicProgress;
use app\models\AcademicSession;
use app\models\AcademicSessionSemester;
use app\models\CourseRegistration;
use app\models\CourseRegistrationStatus;
use app\models\CourseRegistrationType;
use app\models\Marksheet;
use app\models\ProgCurrSemester;
use app\models\ProgCurrSemesterGroup;
use app\models\ProgrammeCurriculumLectureTimetable;
use app\models\ProgrammeCurriculumTimetable;
use app\models\Programmes;
use app\models\Room;
use app\models\Student;
use app\models\StudentProgCurriculum;
use app\services\BillStudent;
use app\services\StudentToBill;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use kartik\mpdf\Pdf;
use Throwable;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use app\models\FeeTransaction;
use app\models\FeePayment;
use app\models\Invoice;
use app\models\InvoiceDetail;

final class CoursesController extends BaseController
{
    private ?BillStudent $billStudent;

    /**
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function init(): void
    {
        parent::init();

        $regNumber = StudentProgCurriculum::find()->select('registration_number')
            ->where(['adm_refno' => \Yii::$app->user->identity->adm_refno])
            ->asArray()->one()['registration_number'];

        $this->billStudent = new BillStudent(new StudentToBill($regNumber));
    }

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
     * @todo Check for registration deadlines and display date
     */
    public function actionIndex(): Response|string
    {
        try {
            // Get the last academic session semester a student joined
            $studentSemSessProgress = SmisHelper::latestAcademicSessionForAStudent(); //775
//            print_r($studentSemSessProgress); exit;

            /**
             * If semester has ended i.e. a student is trying to register for courses in a semester whose end date is behind
             * the current date, inform them to join an active session first.
             * The admin creates session before placing a student in one.
             */
            $progCurrSemGroupId = $studentSemSessProgress['prog_curriculum_semester_id'];

            $programmeCurriculumSemGroup = ProgCurrSemesterGroup::find()->select(['prog_curriculum_sem_group_id', 'registration_deadline'])
                ->andWhere(['prog_curriculum_sem_group_id' => $progCurrSemGroupId])
                ->asArray()->one();

            if (empty($programmeCurriculumSemGroup)) {
                $this->setFlash('danger', 'Timetable courses', 'Please join an active semester.');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

            // Make sure the student raises/accepts an invoice to bill for sem admin fees before trying to register for courses
            $regNumber = StudentProgCurriculum::find()->select('registration_number')
                ->where(['adm_refno' => \Yii::$app->user->identity->adm_refno])
                ->asArray()->one()['registration_number'];

            $studentToBill = new StudentToBill($regNumber);

            $partProgressCode = $studentToBill->academicYear . '-SEM' . $studentToBill->semester;
            $invoice = Invoice::find()->where(['like', 'invoice_id', '%' . $partProgressCode . '%', false])->one();

            if (!$invoice) {
                $this->setFlash('danger', 'Raise Invoice',
                    'You must raise and accept an invoice for this semster inorder to register for courses');
                return $this->redirect(['/bill/raise-invoice']);
            }

            // Get courses in the timetable in the semester
            $timetableCourses = ProgrammeCurriculumTimetable::find()->alias('tt')
                ->select([
                    'tt.timetable_id',
                    'tt.exam_date',
                    'tt.exam_venue',
                    'tt.exam_mode',
                    'tt.prog_curriculum_course_id'
                ])
                ->where(['tt.prog_curriculum_sem_group_id' => $programmeCurriculumSemGroup['prog_curriculum_sem_group_id']])
                ->joinWith(['examMode em' => function (ActiveQuery $q) {
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
                'studentSemesterSessionId' => $studentSemSessProgress['student_semester_session_id'],
                'currentSessionDetails' => $this->currentSessionDetails(),
                'hasAvailableSessionToJoin' => SmisHelper::studentHasAvailableSessionToJoin(),
                'regDeadline' => $programmeCurriculumSemGroup['registration_deadline']
            ]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**.
     * @return Response
     * @todo Check for registration deadlines and special, retake, supplementary type conditions
     * Retake, there exist
     *
     * Do provisional registration
     */
    public function actionRegister(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (SmisHelper::studentHasAvailableSessionToJoin()) {
                throw new Exception('You must report to your session inorder to register for courses');
            }

            $courses = Yii::$app->request->post('courses', []);
            if (!is_array($courses) || empty($courses)) {
                throw new Exception('No valid courses were submitted for registration.');
            }

            $studentSemSessProgress = SmisHelper::latestAcademicSessionForAStudent();
            $studentSemesterSessionId = $studentSemSessProgress['student_semester_session_id'];
            $progCurriculumSemGroupId = $studentSemSessProgress['prog_curriculum_semester_id'];

            $studentProgCurr = StudentProgCurriculum::find()->select(['student_id'])
                ->where(['adm_refno' => Yii::$app->user->identity->adm_refno])->asArray()->one();

            $student = Student::find()->select(['student_number'])
                ->where(['student_id' => $studentProgCurr['student_id']])->asArray()->one();

            foreach ($courses as $course) {
                $timetableId = $course['timetableId'] ?? null;
                $examType = $course['examType'] ?? null;
                if (!is_scalar($timetableId) || !ctype_digit((string)$timetableId) || !is_string($examType) || $examType === '') {
                    throw new Exception('One or more selected courses have invalid registration details.');
                }

                $timetableId = (int)$timetableId;
                $timetableExists = ProgrammeCurriculumTimetable::find()
                    ->where([
                        'timetable_id' => $timetableId,
                        'prog_curriculum_sem_group_id' => $progCurriculumSemGroupId
                    ])
                    ->exists();
                if (!$timetableExists) {
                    throw new Exception('One or more selected courses are not available in your current session.');
                }

                if ($this->isRegistrationConfirmed($timetableId)) {
                    continue;
                }

                $courseRegStatus = CourseRegistrationStatus::find()->where(['course_reg_status_name' => 'PROVISIONAL'])
                    ->asArray()->one();

                $courseRegType = CourseRegistrationType::find()->where(['course_reg_type_code' => $examType])
                    ->asArray()->one();
                if (empty($courseRegType)) {
                    throw new Exception('The selected exam type is not valid.');
                }

                $courseReg = CourseRegistration::find()->where([
                    'timetable_id' => $timetableId,
                    'student_semester_session_id' => $studentSemesterSessionId
                ])->one();

                if (empty($courseReg)) {
                    $courseReg = new CourseRegistration();
                }

                $courseReg->timetable_id = $timetableId;
                $courseReg->student_semester_session_id = $studentSemesterSessionId;
                $courseReg->course_registration_type_id = $courseRegType['course_reg_type_id'];
                $courseReg->registration_date = SmisHelper::formatDate('now', 'Y-m-d');
                $courseReg->course_reg_status_id = $courseRegStatus['course_reg_status_id'];
                $courseReg->source_ipaddress = '';
                $courseReg->userid = $student['student_number'];
                $courseReg->registration_number = $student['student_number'];
                $courseReg->sync_status = false;

                /**
                 * Assign class group
                 * Table cr_class_groups has the class_code as the pk which is also the group code.
                 * Disable the auto increment on this table, to maintain the correct codes.
                 */
                $lectureTimetable = ProgrammeCurriculumLectureTimetable::find()->select(['lecture_room_id'])
                    ->where(['timetable_id' => $timetableId])->asArray()->one();

                if (empty($lectureTimetable)) {
                    throw new Exception('Teaching timetable for one of the courses selected is not created.
                    Please contact your department for assistance.');
                }

                $room = Room::find()->select(['room_capacity'])->where(['room_id' => $lectureTimetable['lecture_room_id']])
                    ->asArray()->one();
                $roomCapacity = 1000; // default capacity
                if (!empty($room)) {
                    $roomCapacity = $room['room_capacity'];
                }

                $studentsRegisteredCount = CourseRegistration::find()->where(['timetable_id' => $timetableId])
                    ->count();
                $classCode = 1;
                if ($studentsRegisteredCount >= $roomCapacity) {
                    $remainder = fmod($studentsRegisteredCount, $roomCapacity);
                    $fullGroupsCount = ($studentsRegisteredCount - $remainder) / $roomCapacity;
                    $classCode = $fullGroupsCount + 1;
                }

                /**
                 * Check if a teaching timetable for the class group is created.
                 * If there is none, skip registration
                 */
                // @todo uncomment after testing
//                $lectureTimetable = ProgrammeCurriculumLectureTimetable::find()
//                    ->where(['timetable_id' => $timetableId, 'class_code' => $classCode])->count();
//                if (!$lectureTimetable > 0) {
//                    $examTimetable = ProgrammeCurriculumTimetable::find()->select(['prog_curriculum_course_id'])
//                        ->where(['timetable_id' => $timetableId])->asArray()->one();
//
//                    $progCurrCourse = ProgrammeCurriculumCourse::find()->select(['course_id'])
//                        ->where(['prog_curriculum_course_id' => $examTimetable['prog_curriculum_course_id']])->asArray()->one();
//
//                    $course = Course::find()->select(['course_code'])->where(['course_id' => $progCurrCourse['course_id']])->asArray()->one();
//
//                    throw new Exception('Teaching timetable for the course ' . $course['course_code'] . ' and class group ' .
//                        $classCode . ' is not created. Please contact your department for assistance.');
//                }

                $courseReg->class_code = $classCode;

                if (!$courseReg->save()) {
                    if (!$courseReg->validate()) {
                        throw new Exception(SmisHelper::getModelErrors($courseReg->getErrors()));
                    } else {
                        throw new Exception('Course registration failed.');
                    }
                }
            }

            $transaction->commit();
            $this->setFlash('success', 'Course registration', 'Course registration done successfully.');
            return $this->asJson(['success' => true]);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
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
        try {
            if (!array_key_exists('timetableIds', Yii::$app->request->get())) {
                return $this->asJson(['success' => true, 'examTypes' => []]);
            }

            $timetableIds = Yii::$app->request->get()['timetableIds'];

            // Get the last academic session semester a student joined
            $studentSemSessProgress = SmisHelper::latestAcademicSessionForAStudent();
            $studentSemesterSessionId = $studentSemSessProgress['student_semester_session_id'];

            $timetableExamTypes = [];
            foreach ($timetableIds as $timetableId) {
                $courseReg = CourseRegistration::find()->select(['course_registration_type_id'])->where([
                    'student_semester_session_id' => $studentSemesterSessionId,
                    'timetable_id' => $timetableId
                ])->asArray()->one();

                if (empty($courseReg)) {
                    continue;
                }

                $courseRegType = CourseRegistrationType::find()->select(['course_reg_type_code'])
                    ->where(['course_reg_type_id' => $courseReg['course_registration_type_id']])
                    ->asArray()->one();

                $timetableExamTypes[$timetableId] = $courseRegType['course_reg_type_code'];
            }
            return $this->asJson(['success' => true, 'examTypes' => $timetableExamTypes]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
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
        try {
            if (!array_key_exists('timetableIds', Yii::$app->request->get())) {
                return $this->asJson(['success' => true, 'confirmedTimetableIds' => []]);
            }

            $timetableIds = Yii::$app->request->get()['timetableIds'];
            $confirmedTimetableIds = [];
            foreach ($timetableIds as $timetableId) {
                if ($this->isRegistrationConfirmed($timetableId)) {
                    $confirmedTimetableIds[] = $timetableId;
                }
            }
            return $this->asJson(['success' => true, 'confirmedTimetableIds' => $confirmedTimetableIds]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
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
        try {
            // Get the last academic session semester a student joined
            $studentSemSessProgress = SmisHelper::latestAcademicSessionForAStudent();
            $studentSemesterSessionId = $studentSemSessProgress['student_semester_session_id'];

            $timetableCourses = ProgrammeCurriculumTimetable::find()->alias('tt')
                ->select([
                    'tt.timetable_id',
                    'tt.exam_date',
                    'tt.exam_venue',
                    'tt.exam_mode',
                    'tt.prog_curriculum_course_id'
                ])
                ->joinWith(['examMode em' => function (ActiveQuery $q) {
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
                ->joinWith(['courseRegistration cr' => function (ActiveQuery $q) {
                    $q->select([
                        'cr.student_course_reg_id',
                        'cr.timetable_id',
                    ]);
                }], true, 'INNER JOIN')
                ->where(['cr.student_semester_session_id' => $studentSemesterSessionId])
                ->joinWith(['courseRegistration.status st' => function (ActiveQuery $q) {
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
                'studentSemesterSessionId' => $studentSemSessProgress['student_semester_session_id'],
                'currentSessionDetails' => $this->currentSessionDetails()
            ]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Raise invoice course registration
     * @return Response
     */
    public function actionInvoice(): Response
    {
        $post = Yii::$app->request->post(); //dd('invoice');
        $marksheets = '';
        foreach ($post['timetableIds'] as $timetableId) {
            $timetable = ProgrammeCurriculumTimetable::find()
                ->select('mrksheet_id')->where(['timetable_id' => $timetableId])->asArray()->one();
            if ($timetable) {
                $marksheets .= $timetable['mrksheet_id'] . '.';
            }
        }//dd($marksheets);
        return $this->redirect(['/bill/raise-invoice', 'marksheets' => rtrim($marksheets, '.')]);
    }

    /**
     * Confirm and bill for course registration
     * @return Response
     */
    public function actionConfirm(): Response
    {
        $transaction = Yii::$app->db->beginTransaction(); //print_r('confirm'); exit;
        try {
            $post = Yii::$app->request->post();
//            $timetableIds = $post['timetableIds']; // @todo remove when billing
//            $payableFess = json_decode($post['payableFees'], true); // @todo return when billing
            $timetableIds = $post['timetableIds']; // @todo return when billing

//            print_r($timetableIds); exit; // gives me one array element with id 426

            /**
             * Bill admin and course units fees
             */
//            print_r($payableFess);
            $semesterSessionId = SmisHelper::latestAcademicSessionForAStudent()['student_semester_session_id'];
            $courses = (new Query())
                ->select([
                    'pct.timetable_id',
                    'cs.course_code',
                    'crt.course_reg_type_code'
                ])
                ->from('smisportal.cr_prog_curr_timetable pct')
                ->innerJoin('smisportal.org_prog_curr_course pcc', 'pcc.prog_curriculum_course_id=pct.prog_curriculum_course_id')
                ->innerJoin('smisportal.org_courses cs', 'cs.course_id=pcc.course_id')
                ->innerJoin('smisportal.cr_course_registration cr', 'cr.timetable_id=pct.timetable_id')
                ->innerJoin('smisportal.cr_course_reg_type crt', 'crt.course_reg_type_id=cr.course_registration_type_id')
                ->where(['pct.timetable_id' => $timetableIds])
                ->andWhere(['cr.student_semester_session_id' => $semesterSessionId])
                ->all();

//            print_r($timetableIds); exit;

            $coursesToBill = [];
            foreach ($courses as $course) {
                $coursesToBill[] = [
                    'code' => $course['course_code'],
                    'type' => $course['course_reg_type_code']
                ];
            }

//            print_r($coursesToBill); exit;

            $payableFess = $this->billStudent->billCourseRegistration($coursesToBill);

            $this->billStudent->billZeroCourseFees($payableFess);

            // Get the last academic session semester a student joined
            $studentSemSessProgress = SmisHelper::latestAcademicSessionForAStudent();
            $studentSemesterSessionId = $studentSemSessProgress['student_semester_session_id'];

            $courseRegStatus = CourseRegistrationStatus::find()->select(['course_reg_status_id'])
                ->where(['course_reg_status_name' => 'CONFIRMED'])->asArray()->one();


            foreach ($timetableIds as $timetableId) {
                $courseReg = CourseRegistration::find()->where([
                    'student_semester_session_id' => $studentSemesterSessionId,
                    'timetable_id' => $timetableId
                ])->one();

                $courseReg->course_reg_status_id = $courseRegStatus['course_reg_status_id'];
                if ($courseReg->save()) {
                    $marksheet = Marksheet::find()->where(['student_course_reg_id' => $courseReg->student_course_reg_id])->one();

                    if (empty($marksheet)) {
                        $marksheet = new Marksheet();
                    }

                    $marksheet->student_course_reg_id = $courseReg->student_course_reg_id;
                    if (!$marksheet->save()) {
                        if (!$marksheet->validate()) {
                            throw new Exception(SmisHelper::getModelErrors($marksheet->getErrors()));
                        } else {
                            throw new Exception('Course marksheet registration failed.');
                        }
                    }
                } else {
                    if (!$courseReg->validate()) {
                        throw new Exception(SmisHelper::getModelErrors($courseReg->getErrors()));
                    } else {
                        throw new Exception('Course registration confirmation failed.');
                    }
                }
            }

            $transaction->commit();
            $this->setFlash('success', 'Course registration confirmation',
                'Course registration confirmation done successfully.');

            return $this->redirect(['/courses']);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
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
        try {
            $timetableIds = Yii::$app->request->post()['timetableIds'];

            // Get the last academic session semester a student joined
            $studentSemSessProgress = SmisHelper::latestAcademicSessionForAStudent();
            $studentSemesterSessionId = $studentSemSessProgress['student_semester_session_id'];

            foreach ($timetableIds as $timetableId) {
                $courseReg = CourseRegistration::find()->where([
                    'student_semester_session_id' => $studentSemesterSessionId,
                    'timetable_id' => $timetableId
                ])->one();

                if (empty($courseReg) || $this->isRegistrationConfirmed($timetableId)) {
                    continue;
                }

                if (!$courseReg->delete()) {
                    throw new Exception('Failed to drop courses.');
                }
            }

            $transaction->commit();
            $this->setFlash('success', 'Drop courses', 'Courses dropped successfully.');
            return $this->redirect(['/courses']);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionExamCard(): string
    {
        try {
            $name = Yii::$app->user->identity->surname . ' ' . Yii::$app->user->identity->other_names;

            $studentProg = StudentProgCurriculum::find()->select(['registration_number'])
                ->where(['adm_refno' => Yii::$app->user->identity->adm_refno])->asArray()->one();

            // Get the last academic session semester a student joined
            $studentSemSessProgress = SmisHelper::latestAcademicSessionForAStudent();
            $studentSemesterSessionId = $studentSemSessProgress['student_semester_session_id'];

            $courses = ProgrammeCurriculumTimetable::find()->alias('tt')
                ->select([
                    'tt.timetable_id',
                    'tt.exam_date',
                    'tt.exam_venue',
                    'tt.exam_mode',
                    'tt.prog_curriculum_course_id'
                ])
                ->joinWith(['examMode em' => function (ActiveQuery $q) {
                    $q->select([
                        'em.exam_mode_id',
                        'em.exam_mode_name'
                    ]);
                }], true, 'INNER JOIN')
                ->joinWith(['examVenue ev' => function (ActiveQuery $q) {
                    $q->select([
                        'ev.room_id',
                        'ev.room_name',
                        'ev.room_code'
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
                ->joinWith(['courseRegistration cr' => function (ActiveQuery $q) {
                    $q->select([
                        'cr.student_course_reg_id',
                        'cr.timetable_id',
                    ]);
                }], true, 'INNER JOIN')
                ->where(['cr.student_semester_session_id' => $studentSemesterSessionId])
                ->joinWith(['courseRegistration.status st' => function (ActiveQuery $q) {
                    $q->select([
                        'st.course_reg_status_id',
                    ]);
                }], true, 'INNER JOIN')
                ->andWhere(['st.course_reg_status_name' => 'CONFIRMED'])
                ->asArray()
                ->all();

            $content = $this->renderPartial('examCard', [
                'name' => $name,
                'regNumber' => $studentProg['registration_number'],
                'currentSessionDetails' => $this->currentSessionDetails(),
                'courses' => $courses
            ]);

            // setup kartik\mpdf\Pdf component
            $pdf = new Pdf([
                'filename' => 'exam_card',
                // set to use core fonts only
                'mode' => Pdf::MODE_CORE,
                // A4 paper format
                'format' => Pdf::FORMAT_A4,
                // portrait orientation
                'orientation' => Pdf::ORIENT_LANDSCAPE,
                // stream to browser inline
                'destination' => Pdf::DEST_BROWSER,
                // your html content input
                'content' => $content,
                // format content from your own css file if needed or use the
                // enhanced bootstrap css built by Krajee for mPDF formatting
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                // any css to be embedded if required
                'cssInline' => '.kv-heading-1{font-size:18px}',
                // set mPDF properties on the fly
                'options' => ['title' => 'Krajee Report Title'],
                // call mPDF methods on the fly
                'methods' => [
                    'SetHeader' => ['NATIONAL DEFENCE UNIVERSITY OF KENYA||EXAM CARD'],
                    'SetFooter' => ['PRINTED BY ' . $name . ' ON ' . SmisHelper::formatDate('now', 'd-m-Y') . '||'],
                ]
            ]);

            // return the pdf output as per the destination setting
            return $pdf->render();
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    public function actionFeeStatement(): string
    {
        try {
            $name = Yii::$app->user->identity->surname . ' ' . Yii::$app->user->identity->other_names;

            $studentProg = StudentProgCurriculum::find()
                ->select(['registration_number'])
                ->where(['adm_refno' => Yii::$app->user->identity->adm_refno])
                ->asArray()
                ->one();

            if (empty($studentProg)) {
                throw new ServerErrorHttpException('Student programme record not found.', 500);
            }

            $regNumber = $studentProg['registration_number'];

//            print_r([$studentProg, $regNumber]); exit;

            // -------------------------------------------------------
            // 1. Fetch all fee transactions for this student
            // -------------------------------------------------------
            $transactions = FeeTransaction::find()
                ->where(['LIKE', 'progress_code', $regNumber . '%', false])
                ->orderBy(['trans_date' => SORT_ASC, 'trans_id' => SORT_ASC])
                ->asArray()
                ->all();

            $ledgerDb = Yii::$app->db;
            $ledgerSchema = 'smisportal';
            if (empty($transactions)) {
                $ledgerDb = Yii::$app->smisDb;
                $ledgerSchema = 'smis';
                $transactions = (new Query())
                    ->from($ledgerSchema . '.fss_fee_transactions')
                    ->where(['LIKE', 'progress_code', $regNumber . '%', false])
                    ->orderBy(['trans_date' => SORT_ASC, 'trans_id' => SORT_ASC])
                    ->all($ledgerDb);
            }

//            print_r($transactions); exit;

            // -------------------------------------------------------
            // 2. Fetch all invoices for this student
            //    Linked via trans_id FK to fss_fee_transactions
            //    Indexed by trans_id for quick lookup
            // -------------------------------------------------------
            $transIds = array_column($transactions, 'trans_id');
            $invoiceMap = [];
            if (!empty($transIds)) {
                $invoices = (new Query())
                    ->from($ledgerSchema . '.fss_invoice')
                    ->where(['trans_id' => $transIds])
                    ->all($ledgerDb);

                foreach ($invoices as $invoice) {
                    // key by trans_id so we can match to transaction
                    $invoiceMap[$invoice['trans_id']] = $invoice;
                }
            }

            // -------------------------------------------------------
            // 3. Fetch all invoice details for those invoices
            //    Linked via invoice_id (int8 FK to fss_invoice.id)
            //    Indexed by invoice id for quick lookup
            // -------------------------------------------------------
            $invoiceIds = array_column($invoiceMap, 'id');
            $invoiceDetails = [];
            if (!empty($invoiceIds)) {
                $details = (new Query())
                    ->from($ledgerSchema . '.fss_invoice_details')
                    ->where(['invoice_id' => $invoiceIds])
                    ->orderBy(['invoice_id' => SORT_ASC, 'invoice_detail_id' => SORT_ASC])
                    ->all($ledgerDb);

                foreach ($details as $detail) {
                    // key by invoice id (int8)
                    $invoiceDetails[$detail['invoice_id']][] = $detail;
                }
            }

            // -------------------------------------------------------
            // 4. Fetch fee payments indexed by trans_id
            //    Used to get receipt number for CR transactions
            //    that have no matching invoice
            // -------------------------------------------------------
            $paymentMap = [];
            if (!empty($transIds)) {
                $payments = (new Query())
                    ->from($ledgerSchema . '.fss_fee_payments')
                    ->where(['trans_id' => $transIds])
                    ->all($ledgerDb);

                foreach ($payments as $payment) {
                    $paymentMap[$payment['trans_id']] = $payment;
                }
            }

            // -------------------------------------------------------
            // 5. Build unified ledger rows
            // -------------------------------------------------------
            $ledgerRows = [];

            foreach ($transactions as $txn) {
                $dr = null;
                $cr = null;

                if ($txn['trans_type'] === 'DR') {
                    $dr = $txn['trans_amount'];
                } elseif ($txn['trans_type'] === 'CR') {
                    $cr = $txn['trans_amount'];
                }

                // Extract academic year from progress_code
                // progress_code format: NR605/0001/2022-2023/2024
                // academic year is everything after the first hyphen
                $academicYear = '';
                $stripped = str_replace($regNumber . '-', '', trim($txn['progress_code']));
                $progressParts = explode('-', $stripped);
                if (!empty($progressParts)) {
                    $academicYear = implode('-', $progressParts);
                }

                // Match transaction to invoice via trans_id
                $matchedInvoice = $invoiceMap[$txn['trans_id']] ?? null;

                // Get invoice details using invoice.id (int8)
                $matchedDetails = [];
                if ($matchedInvoice) {
                    $matchedDetails = $invoiceDetails[$matchedInvoice['id']] ?? [];
                }

                // Extract semester label from invoice_id string
                // e.g. NR605/0001/2022-2023/2024-SEM1 -> Semester 1
                $semesterLabel = '';
                if ($matchedInvoice) {
                    if (preg_match('/SEM(\d+)$/i', $matchedInvoice['invoice_id'], $m)) {
                        $semesterLabel = 'Semester ' . $m[1];
                    }
                }

                // Determine Trans ID column value:
                // 1. invoice_id (varchar) if invoice matched
                // 2. receipt_number from fee payments if exists
                // 3. blank
                $transIdDisplay = '';
                if ($matchedInvoice) {
                    $transIdDisplay = $matchedInvoice['invoice_id'];
                } elseif (isset($paymentMap[$txn['trans_id']])) {
                    $transIdDisplay = $paymentMap[$txn['trans_id']]['receipt_number']
                        ?? $paymentMap[$txn['trans_id']]['receipt_no']
                        ?? '';
                }

                $ledgerRows[] = [
                    'trans_id_display' => $transIdDisplay,
                    'date'             => $txn['trans_date'],
                    'type'             => $txn['trans_type'],
                    'description'      => $txn['trans_desc'],
                    'dr'               => $dr,
                    'cr'               => $cr,
                    'receipt_status'   => $txn['receipt_status'] ?? '',
                    'academic_year'    => $academicYear,
                    'semester'         => $semesterLabel,
                    'invoice'          => $matchedInvoice,
                    'details'          => $matchedDetails,
                ];
            }

            // -------------------------------------------------------
            // 6. Compute running balance
            // -------------------------------------------------------
            $runningBalance = 0;
            foreach ($ledgerRows as &$row) {
                if ($row['dr'] !== null) {
                    $runningBalance += $row['dr'];
                }
                if ($row['cr'] !== null) {
                    $runningBalance -= $row['cr'];
                }
                $row['balance'] = $runningBalance;
            }
            unset($row);

            $totalDr = array_sum(array_filter(array_column($ledgerRows, 'dr')));
            $totalCr = array_sum(array_filter(array_column($ledgerRows, 'cr')));
            $netBalance = $totalDr - $totalCr;

            $currentSessionDetails = $this->currentSessionDetails();

            $content = $this->renderPartial('feeStatement', [
                'name'                  => $name,
                'regNumber'             => $regNumber,
                'currentSessionDetails' => $currentSessionDetails,
                'ledgerRows'            => $ledgerRows,
                'invoiceDetails'        => $invoiceDetails,
                'totalDr'               => $totalDr,
                'totalCr'               => $totalCr,
                'netBalance'            => $netBalance,
            ]);

            $pdf = new Pdf([
                'filename'    => 'fee_statement_' . str_replace('/', '_', $regNumber),
                'mode'        => Pdf::MODE_CORE,
                'format'      => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'content'     => $content,
                'cssFile'     => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline'   => '
                body { font-size: 11px; font-family: Arial, sans-serif; }
                .header-title { font-size: 14px; font-weight: bold; text-align: center; }
                .sub-title { font-size: 12px; text-align: center; margin-bottom: 4px; }
                .ledger-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                .ledger-table th { background-color: #003366; color: #ffffff; padding: 5px 4px; font-size: 10px; }
                .ledger-table td { padding: 4px; border-bottom: 1px solid #dddddd; font-size: 10px; vertical-align: top; }
                .ledger-table tr.cr-row td { background-color: #f0fff0; }
                .ledger-table tr.dr-row td { background-color: #fff8f0; }
                .balance-credit { color: #006600; }
                .balance-debit  { color: #cc0000; }
                .amount { text-align: right; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
            ',
                'methods' => [
                    'SetHeader' => ['NATIONAL DEFENCE UNIVERSITY OF KENYA||FEE STATEMENT'],
                    'SetFooter' => ['PRINTED BY ' . $name . ' ON ' . SmisHelper::formatDate('now', 'd-m-Y') . '||Page {PAGENO}'],
                ],
            ]);

            return $pdf->render();

        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }
    
    /**
     * Check if a course registration is confirmed
     * @param string $timetableId
     * @return bool
     */
    private function isRegistrationConfirmed(string $timetableId): bool
    {
        // Get the last academic session semester a student joined
        $studentSemSessProgress = SmisHelper::latestAcademicSessionForAStudent();
        $studentSemesterSessionId = $studentSemSessProgress['student_semester_session_id'];

        $courseReg = CourseRegistration::find()->select(['course_reg_status_id'])->where([
            'student_semester_session_id' => $studentSemesterSessionId,
            'timetable_id' => $timetableId
        ])->asArray()->one();

        if (!empty($courseReg)) {
            $courseRegStatus = CourseRegistrationStatus::find()->select(['course_reg_status_name'])
                ->where(['course_reg_status_id' => $courseReg['course_reg_status_id']])->asArray()->one();
            $status = $courseRegStatus['course_reg_status_name'];
            if ($status === 'CONFIRMED') {
                return true;
            }
        }

        return false;
    }

    /**
     * Current session details
     * @return array
     */
    #[ArrayShape(['academicSession' => "mixed", 'programme' => "mixed", 'level' => "mixed", 'semester' => "mixed"])]
    private function currentSessionDetails(): array
    {
        // Get the last academic session semester a student joined
        $studentSemSessProgress = SmisHelper::latestAcademicSessionForAStudent();
        $academicProgressId = $studentSemSessProgress['academic_progress_id']; //2024
        $progCurrSemGroupId = $studentSemSessProgress['prog_curriculum_semester_id']; //775

        $academicProgress = AcademicProgress::findOne($academicProgressId);

        $academicSession = AcademicSession::find()->select(['acad_session_name'])
            ->where(['acad_session_id' => $academicProgress->acad_session_id])->asArray()->one();

        $programme = Programmes::find()->alias('p')
            ->select(['p.prog_full_name'])
            ->innerJoin('smisportal.org_programme_curriculum pc', 'pc.prog_id=p.prog_id')
            ->innerJoin(
                'smisportal.sm_student_programme_curriculum spc',
                'spc.prog_curriculum_id=pc.prog_curriculum_id'
            )
            ->where(['spc.adm_refno' => Yii::$app->user->identity->adm_refno])
            ->asArray()
            ->one();

        $level = AcademicLevel::find()->select(['academic_level'])
            ->where(['academic_level_id' => $academicProgress->academic_level_id])->asArray()->one();

        $progCurrSemGroup = ProgCurrSemesterGroup::find()->select(['prog_curriculum_semester_id'])
            ->where(['prog_curriculum_sem_group_id' => $progCurrSemGroupId])->asArray()->one();

        $progCurrSem = ProgCurrSemester::find()->select(['acad_session_semester_id'])
            ->where(['prog_curriculum_semester_id' => $progCurrSemGroup['prog_curriculum_semester_id']])
            ->asArray()->one();

        $semester = AcademicSessionSemester::find()->select(['semester_code'])
            ->where(['acad_session_semester_id' => $progCurrSem['acad_session_semester_id']])->asArray()->one();

        return [
            'academicSession' => $academicSession['acad_session_name'], //2023/2024
            'programme' => $programme['prog_full_name'], 
            'level' => $level['academic_level'], //second year
            'semester' => $semester['semester_code'] //1
        ];
    }
}
