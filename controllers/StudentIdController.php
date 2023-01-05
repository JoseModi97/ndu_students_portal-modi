<?php

namespace app\controllers;

use app\models\IdRequestStatus;
use app\models\IdRequestType;
use app\models\search\StudentIdRequestSearch;
use app\models\search\StudentIdSearch;
use app\models\StudentId;
use app\models\StudentIdRequest;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
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
                        'actions' => ['index', 'view', 'create', 'update-id-status', 'delete'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => false
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all StudentIdRequest models.
     * @return string
     */
    public function actionIndex(): string
    {
        $studentIdSearchModel = new StudentIdSearch();
        $studentIdDataProvider = $studentIdSearchModel->search(Yii::$app->request->queryParams);

        $studentIdRequestModel = new StudentIdRequestSearch();
        $studentIdRequestProvider = $studentIdRequestModel->search(Yii::$app->request->queryParams);


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
     */
    public function actionCreate(): string|Response
    {
        $model = new StudentIdRequest();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        //check if student has an active and valid id
        $hasActiveId = StudentId::hasActiveAndValidId();
        if ($hasActiveId) {
            //return to grid view
            $this->setFlash('danger', 'Active ID', 'You already have an active and current student id, you cannot request for another one');
            return $this->redirect(['index']);
        }

        //preload default values
        $model->request_date = date('Y-m-d');
        $model->status_id = IdRequestStatus::findOne(['status_name' => IdRequestStatus::STATUS_PENDING])->status_id;
        $model->request_type_id = IdRequestType::findOne(['id_type_desc' => IdRequestType::ID_REPLACEMENT])->request_type_id;

        //check if student has enough fee balance
        $hasEnoughFunds = true; //@TODO tie in to fee balance checking

        if ($hasEnoughFunds == false) {
            $this->setFlash('danger', 'Insufficient funds', 'Insufficient funds. Please top up your student account and try again');
            return $this->redirect(['index']);
        }

        return $this->render('new-id-request', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StudentIdRequest model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id): string|Response
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('update-id-request', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing StudentId model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return string|Response
     */
    public function actionUpdateIdStatus(int $id): string|Response
    {
        $model = StudentId::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('update-id-status', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing StudentIdRequest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
