<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/10/2023
 * @time: 12:19 PM
 */

namespace app\controllers;

use app\helpers\SmisHelper;
use app\models\StudentProgCurriculum;
use app\models\StudentSemesterSessionProgress;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class SemesterSessionProgressController extends BaseController
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
     * @return Response
     * @throws ServerErrorHttpException
     */
    public function actionJoinSession(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $studentSemSessProgress = SmisHelper::studentHasAvailableSessionToJoin();
            if (!empty($studentSemSessProgress)) {
                $semSessProgressId = $studentSemSessProgress['student_semester_session_id'];
                $studentSemSessProgress = StudentSemesterSessionProgress::findOne($semSessProgressId);
                $studentSemSessProgress->registration_date = SmisHelper::formatDate('now', 'Y-m-d');
                $studentSemSessProgress->reporting_sync_status = false;
                if (!$studentSemSessProgress->save()) {
                    if (!$studentSemSessProgress->validate()) {
                        $errorMessage = SmisHelper::getModelErrors($studentSemSessProgress->getErrors());
                        throw new Exception($errorMessage);
                    } else {
                        throw new Exception('Student semester session progress was not saved.');
                    }
                }
                $transaction->commit();
                $this->setFlash('success', 'Semester session', 'You have reported to a session successfully.');
            }else{
                $this->setFlash('danger', 'Semester session', 'No active session was found for you to join.');
            }
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        } catch (Exception $ex) {
            $transaction->rollBack();
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }
}