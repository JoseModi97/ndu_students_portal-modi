<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/21/2024
 * @time: 11:46 AM
 */

namespace app\controllers;

use app\helpers\SmisHelper;
use app\models\AcademicLevel;
use app\models\AcademicProgress;
use app\models\AcademicSession;
use app\models\AcademicSessionSemester;
use app\models\Invoice;
use app\models\ProgCurrSemester;
use app\models\ProgCurrSemesterGroup;
use app\models\Programmes;
use app\models\StudentProgCurriculum;
use app\services\BillStudent;
use app\services\StudentToBill;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use kartik\mpdf\Pdf;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
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
    public function actionRaiseInvoice(string $marksheets = null): string|Response
    {
        if(SmisHelper::studentHasAvailableSessionToJoin()){
            $this->setFlash('danger', 'Report to session',
                'You must report to a session before raising/accepting this semester\'s invoice');
            return $this->redirect(Yii::$app->homeUrl);
        }

        $regNumber = StudentProgCurriculum::find()->select('registration_number')
            ->where(['adm_refno' => \Yii::$app->user->identity->adm_refno])
            ->asArray()->one()['registration_number'];

        $studentToBill = new StudentToBill($regNumber);

        $partProgressCode = $studentToBill->academicYear . '-SEM' . $studentToBill->semester;
        $invoice = Invoice::find()->where(['like', 'invoice_id', '%' . $partProgressCode . '%', false])->one();

        if ($invoice) {
            $this->setFlash('success', 'Raise Invoice',
                'You have already raised and accepted the invoice for this semester.');
            return $this->redirect(Yii::$app->homeUrl);
        }

        $billStudent = new BillStudent($studentToBill); //dd($billStudent);
        $timetableIds = [];

        // this now passes here since all fees are invoice on semester registration
        $invoiceFor = 'normalFees';
        $payableFees = $billStudent->payableReportingFees();

        $feeItems = $billStudent->detailedFeeItemsToBill($payableFees); //print_r($feeItems); exit;
        $transactions = $billStudent->totalTransactions();

        return $this->render('invoice', [
            'title' => 'smis - invoice',
            'invoiceFor' => $invoiceFor,
            'payableFees' => $payableFees,
            'timetableIds' => $timetableIds,
            'feeItems' => $feeItems,
            'balance' => (int)$transactions['credits'] - (int)$transactions['debits']
        ]);
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function actionAcceptInvoice(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {

            $post = Yii::$app->request->post();

            $payableFess = json_decode($post['payableFees'], true);

            $regNumber = StudentProgCurriculum::find()->select('registration_number')
                ->where(['adm_refno' => Yii::$app->user->identity->adm_refno])
                ->asArray()->one()['registration_number'];

            $billStudent = new BillStudent(new StudentToBill($regNumber));
            $billStudent->bill($payableFess);

            $transaction->commit(); // @todo revert to commit after testing
            $this->setFlash('success', 'Semester session', 'You have reported to a session successfully.');
            return $this->redirect(Yii::$app->homeUrl);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * List all invoices for the logged in student
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionMyInvoices(): string
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

            // Fetch all invoices for this student from smisportal
            $invoices = (new Query())
                ->from('smisportal.fss_invoice')
                ->where(['reg_number' => $regNumber])
                ->orderBy(['invoice_date' => SORT_DESC])
                ->all();

            // Extract academic year and semester from invoice_id
            // e.g. NR605/0001/2022-2023/2024-SEM1 -> 2023/2024, SEM1
            foreach ($invoices as &$invoice) {
                $semesterLabel = '';
                $academicYear = '';
                if (preg_match('/SEM(\d+)$/i', $invoice['invoice_id'], $m)) {
                    $semesterLabel = $m[1];
                }
                // Extract academic year — part between reg number and SEM
                // invoice_id: NR605/0001/2022-2023/2024-SEM1
                $stripped = str_replace($regNumber . '-', '', $invoice['invoice_id']);
                $stripped = preg_replace('/-SEM\d+$/i', '', $stripped);
                $academicYear = $stripped;

                $invoice['semester'] = $semesterLabel;
                $invoice['academic_year'] = $academicYear;
            }
            unset($invoice);

            $currentSessionDetails = $this->currentSessionDetails();

            return $this->render('my-invoices', [
                'name'                  => $name,
                'regNumber'             => $regNumber,
                'invoices'              => $invoices,
                'currentSessionDetails' => $currentSessionDetails,
            ]);

        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Download a single invoice as PDF
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionDownloadInvoice(): string
    {
        try {
            $invoiceId = Yii::$app->request->get('invoice_id');

            if (empty($invoiceId)) {
                throw new ServerErrorHttpException('Invoice ID is required.', 500);
            }

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

            // Fetch the invoice
            $invoice = (new Query())
                ->from('smisportal.fss_invoice')
                ->where(['invoice_id' => $invoiceId, 'reg_number' => $regNumber])
                ->one();

            if (empty($invoice)) {
                throw new ServerErrorHttpException('Invoice not found.', 404);
            }

            // Fetch invoice details
            $details = (new Query())
                ->from('smisportal.fss_invoice_details')
                ->where(['invoice_id' => $invoice['id']])
                ->orderBy(['invoice_detail_id' => SORT_ASC])
                ->all();

            // Extract academic year and semester
            $semesterLabel = '';
            $academicYear = '';
            if (preg_match('/SEM(\d+)$/i', $invoice['invoice_id'], $m)) {
                $semesterLabel = $m[1];
            }
            $stripped = str_replace($regNumber . '-', '', $invoice['invoice_id']);
            $academicYear = preg_replace('/-SEM\d+$/i', '', $stripped);

            $currentSessionDetails = $this->currentSessionDetails();

            $content = $this->renderPartial('download-invoice', [
                'name'                  => $name,
                'regNumber'             => $regNumber,
                'invoice'               => $invoice,
                'details'               => $details,
                'academicYear'          => $academicYear,
                'semesterLabel'         => $semesterLabel,
                'currentSessionDetails' => $currentSessionDetails,
            ]);

            $pdf = new Pdf([
                'filename'    => 'invoice_' . str_replace('/', '_', $invoiceId),
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
                .invoice-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                .invoice-table th { background-color: #003366; color: #ffffff; padding: 5px 4px; font-size: 10px; }
                .invoice-table td { padding: 4px; border: 1px solid #dddddd; font-size: 10px; }
                .invoice-table tr.totals-row td { font-weight: bold; background-color: #eef2ff; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
                .student-info td { padding: 3px 6px; font-size: 11px; }
            ',
                'methods' => [
                    'SetHeader' => ['NATIONAL DEFENCE UNIVERSITY OF KENYA||FEE INVOICE'],
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