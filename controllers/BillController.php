<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/21/2024
 * @time: 11:46 AM
 */

namespace app\controllers;

use app\enums\AdminFee;
use app\helpers\SmisHelper;
use app\models\StudentProgCurriculum;
use app\services\BillStudent;
use app\services\StudentToBill;
use JetBrains\PhpStorm\ArrayShape;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

final class BillController extends BaseController
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
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionRaiseInvoice(string $marksheets = null): string
    {
        $regNumber = StudentProgCurriculum::find()->select('registration_number')
            ->where(['adm_refno' => \Yii::$app->user->identity->adm_refno])
            ->asArray()->one()['registration_number'];

        $billStudent = new BillStudent(new StudentToBill($regNumber));
        $timetableIds = [];

        // For now, we only bill semester and (admin + course) registration fees
        // When no course is passed in, we know to raise for semester registration fees
        // Else raise for course registration
        if (empty($marksheets)) {
            $invoiceFor = 'semesterRegistration';
            $payableFees = [
                'adminFees' => [
                    'items' => [
                        [
                            'desc' => AdminFee::REGISTRATION_FEES->value,
                            'amount' => 1000 // Amount charged for this fee item
                        ]
                    ],
                    'total' => 1000 // Total amount charged for the admin fees items
                ],
                'total' => 1000 // Grand total
            ];
        } else {
            $invoiceFor = 'courseRegistration';

            $semesterSessionId = SmisHelper::latestAcademicSessionForAStudent()['student_semester_session_id'];
            $marksheetIds = explode('.', $marksheets);

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
                ->where(['cr.student_semester_session_id' => $semesterSessionId])
                ->andWhere(['pct.mrksheet_id' => $marksheetIds])
                ->all();

            $coursesToBill = [];
            foreach ($courses as $course) {
                $timetableIds[] = $course['timetable_id'];
                $coursesToBill[] = [
                    'code' => $course['course_code'],
                    'type' => $course['course_reg_type_code']
                ];
            }

            $payableFees = $billStudent->payableFees($coursesToBill);
        }

        $feeItems = $billStudent->detailedFeeItemsToBill($payableFees);

        $transactions = $billStudent->totalTransactions();

        return $this->render('invoice', [
            'title' => 'smis - invoice',
            'invoiceFor' => $invoiceFor,
            'payableFees' => $payableFees,
            'timetableIds' => $timetableIds,
            'feeItems' => $feeItems,
            'balance' => (!$transactions) ? 0 : $transactions['credits'] - $transactions['debits']
        ]);
    }
}