<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/29/2023
 * @time: 10:04 PM
 */

namespace app\controllers;

use app\models\search\ResultsSearch;
use app\models\StudentProgCurriculum;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\filters\AccessControl;
use yii\web\ServerErrorHttpException;

class ResultsController extends BaseController
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
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionIndex(): string
    {
        try{
            $regNumber = 'P15/'.Yii::$app->user->identity->adm_refno.'/2023';

            $searchModel = new ResultsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, [
                'regNumber' => $regNumber
            ]);

            $studProgCurriculum = StudentProgCurriculum::find()
                ->select(['student_prog_curriculum_id'])
                ->where(['adm_refno' => Yii::$app->user->identity->adm_refno])
                ->asArray()->one();
            $studProgCurriculumId = $studProgCurriculum['student_prog_curriculum_id'];

            return $this->render('index', [
                'title' => 'results',
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'studProgCurriculumId' => $studProgCurriculumId
            ]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }
}