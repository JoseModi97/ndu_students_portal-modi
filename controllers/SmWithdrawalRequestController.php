<?php

namespace app\controllers;
use Yii;
use app\models\SmWithdrawalRequest;
use app\models\search\SmWithdrawalRequestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SmWithdrawalRequestController implements the CRUD actions for SmWithdrawalRequest model.
 */
class SmWithdrawalRequestController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all SmWithdrawalRequest models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SmWithdrawalRequestSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        //student ID
        $std_id=142; // replace this with the code below when student registration is sorted
       // $std_id=Yii::$app->user->getId();
        $dataProvider->query->where(['student_id'=> $std_id]);


        //check if there is a pending request to hide create button

        $pendingRequest = SmWithdrawalRequest::find()
            ->where(['student_id' => $std_id])
            ->andwhere(['approval_status'=>'PENDING'])
            ->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pendingRequest' => $pendingRequest,
        ]);
    }

    /**
     * Displays a single SmWithdrawalRequest model.
     * @param int $withdrawal_request_id Withdrawal Request ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($withdrawal_request_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($withdrawal_request_id),
        ]);
    }

    /**
     * Creates a new SmWithdrawalRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SmWithdrawalRequest();

        if ($this->request->isPost) {

            if ($model->load($this->request->post()) && $model->save()) {

                return $this->redirect(['view', 'withdrawal_request_id' => $model->withdrawal_request_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SmWithdrawalRequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $withdrawal_request_id Withdrawal Request ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($withdrawal_request_id)
    {
        $model = $this->findModel($withdrawal_request_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'withdrawal_request_id' => $model->withdrawal_request_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SmWithdrawalRequest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $withdrawal_request_id Withdrawal Request ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($withdrawal_request_id)
    {
        $this->findModel($withdrawal_request_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SmWithdrawalRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $withdrawal_request_id Withdrawal Request ID
     * @return SmWithdrawalRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($withdrawal_request_id)
    {
        if (($model = SmWithdrawalRequest::findOne(['withdrawal_request_id' => $withdrawal_request_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
