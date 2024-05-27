<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/21/2024
 * @time: 11:46 AM
 */

namespace app\controllers;

use app\enums\AdminFee;
use app\models\StudentProgCurriculum;
use app\services\BillStudent;
use app\services\StudentToBill;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
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
    public function actionRaiseInvoice(array $courses = []): string
    {
//        $courses = [
//            // 1st registration
//            [
//                'code' => 'SPH401',
//                'type' => 'FA'
//            ],
//            [
//                'code' => 'SPH402',
//                'type' => 'FA'
//            ],
//            [
//                'code' => 'SPH403',
//                'type' => 'FA'
//            ],
//            [
//                'code' => 'SPH404',
//                'type' => 'FA'
//            ],
//            [
//                'code' => 'SPH405',
//                'type' => 'FA'
//            ],
//            [
//                'code' => 'SPH406',
//                'type' => 'FA'
//            ],
//        ];

        $regNumber = StudentProgCurriculum::find()->select('registration_number')
            ->where(['adm_refno' => \Yii::$app->user->identity->adm_refno])
            ->asArray()->one()['registration_number'];

        $billStudent = new BillStudent(new StudentToBill($regNumber));

        // For now, we only bill semester and (admin + course) registration fees
        // When no course is passed in, we know to raise for semester registration fees
        // Else raise for course registration
        if (empty($courses)) {
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
            $payableFees = $billStudent->payableFees($courses); //dd($payableFees);
        }

        $feeItems = $billStudent->detailedFeeItemsToBill($payableFees);

        $transactions = $billStudent->totalTransactions();

        return $this->render('invoice', [
            'title' => 'smis - invoice',
            'invoiceFor' => $invoiceFor,
            'payableFees' => $payableFees,
            'feeItems' => $feeItems,
            'balance' => $transactions['credits'] - $transactions['debits']
        ]);
    }

    /**
     * @return Response
     */
    public function actionMakePayment(): Response
    {
        try {
            $post = Yii::$app->request->post();
            $payableFess = json_decode($post['payableFees'], true);
            $this->billStudent->bill($payableFess);
            $this->setFlash('success', 'Payment', 'Charges applied successfully.');
            return $this->redirect(Yii::$app->homeUrl);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }
}