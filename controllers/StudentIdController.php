<?php

namespace app\controllers;

use app\models\IdRequestStatus;
use app\models\IdRequestType;
use app\models\search\StudentIdRequestSearch;
use app\models\search\StudentIdSearch;
use app\models\StudentId;
use app\models\StudentIdDetail;
use app\models\StudentIdRequest;
use app\models\StudentIdStatus;
use Yii;
use yii\db\Exception;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\ConflictHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * StudentIdController implements the CRUD actions for StudentIdRequest model.
 */
class StudentIdController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'new-id', 'report-lost-id', 'print-id'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => false
                    ]
                ]
            ]
        ];
    }

    public function beforeAction($action): bool
    {
        if (parent::beforeAction($action)) {
            if ($action->id == 'index') {
                if (Yii::$app->user->identity->admission_status === parent::PRE_REGISTERED_STATUS) {
                    $this->redirect(['/home']);
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Lists all StudentIdRequest models.
     * @return string
     */
    public function actionIndex(): string
    {
        $studentIdSearchModel = new StudentIdSearch();
        $studentIdDataProvider = $studentIdSearchModel->activeStudentRecord(Yii::$app->request->queryParams);

        $statusIds = IdRequestStatus::getStatusIds();

        $studentIdRequestModel = new StudentIdRequestSearch();
        $studentIdRequestProvider = $studentIdRequestModel->activeStudentRequests(Yii::$app->request->queryParams, $statusIds);


        $this->view->title = 'My Student ID Page';
        return $this->render('index', [
            'idRequestSearchModel' => $studentIdRequestModel,
            'idRequestProvider' => $studentIdRequestProvider,
            'studentIdSearchModel' => $studentIdSearchModel,
            'studentIdProvider' => $studentIdDataProvider,
        ]);
    }

    /**
     * Creates a new StudentIdRequest model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|Response
     * @throws ConflictHttpException
     */
    public function actionNewId(): string|Response
    {
        // Create a new instance of StudentIdRequest
        $model = new StudentIdRequest();

        // Load POST data into the model and save if valid
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        // Check if student has an active and valid ID
        if (StudentId::hasActiveAndValidId()) {
            Yii::$app->session->setFlash('error', "You already have an active and current student ID, you cannot request for another one");
        }

        if (StudentIdRequest::hasOpenIdRequest()) {
            throw new ConflictHttpException('You already have a pending ID request');
        }

        // Preload default values for the model
        $model->request_date = date('Y-m-d H:i:s');
        $model->status_id = IdRequestStatus::findOne(['status_name' => IdRequestStatus::STATUS_PENDING])->status_id;
        $model->request_type_id = IdRequestType::findOne(['id_type_desc' => IdRequestType::ID_REPLACEMENT])->request_type_id;

        // Check if the student has enough funds (replace with actual fee balance checking logic)
        $hasEnoughFunds = true; //TODO replace with actual fee balance checking logic

        if (!$hasEnoughFunds) {
            Yii::$app->session->setFlash('error', "Insufficient funds. Please top up your student account and try again");
        } else {
            // Set the view title and render the corresponding view
            $this->view->title = 'New ID Replacement request';
            return $this->render('new-id-request', [
                'model' => $model,
            ]);
        }


        // Redirect to the index page
        return $this->redirect(['index']);
    }


    /**
     * Report an id as lost.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionReportLostId(int $id): string|Response
    {
        $model = StudentId::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }


        $transaction = Yii::$app->db->beginTransaction();
        $model->id_status = StudentIdStatus::ID_LOST;

        //insert to id status table
        $idDetail = StudentIdDetail::findOne(['student_id_serial_no' => $id]);
        $studentIdDetail = $idDetail == null ? new StudentIdDetail() : $idDetail;
        $studentIdDetail->student_id_serial_no = $model->student_id_serial_no;
        $studentIdDetail->student_id_status = $model->id_status;
        $studentIdDetail->remarks = 'ID reported as lost';
        $studentIdDetail->status_date = new Expression('NOW()');

        $model->validate();
        $studentIdDetail->validate();
        if ($model->save() && $studentIdDetail->save()) {

            Yii::$app->session->setFlash('success', "Successfully reported id as lost");
            $transaction->commit();
        } else {

            $errors = $this->mergeModelErrors([$model, $studentIdDetail]);
            foreach ($errors as $error) {
                Yii::$app->session->setFlash('error', "Unable to flag ID as lost, please try again ---> {$error[0]}");
            }
            $transaction->rollBack();
        }

        return $this->redirect(['index']);
    }


    /**
     * @param $id
     * @return false|string
     * @throws Exception
     */
    public function actionPrintId($id): bool|string
    {
        $this->layout = 'id-layout';
        $model = StudentId::findOne($id);

        $requestStatus = StudentIdStatus::ID_ACTIVE;
        $idRequest = StudentIdRequest::findOneByCurrProgId(
            currProgId: $model->student_prog_curr_id,
            statusName: $requestStatus);

        $this->view->title = 'Print ID card';
        return $this->render('print-single', [
            'model' => $model,
            'idRequest' => $idRequest
        ]);

    }

    /**
     * Finds the StudentIdRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StudentIdRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): StudentIdRequest
    {
        if (($model = StudentIdRequest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
