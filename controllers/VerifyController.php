<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\controllers;

use app\helpers\SmisHelper;
use app\models\User;
use Exception;
use Yii;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class VerifyController extends BaseController
{
    /**
     * @return Response
     * @throws ServerErrorHttpException
     */
    public function actionEmail(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $get = Yii::$app->request->get();
            $email = $get['email'];
            $token = $get['token'];

            $user = User::find()->where(['primary_email' => $email, 'primary_email_salt' => $token])->one();
            if($user){
                $emailType = 'primary';
            }else{
                $user = User::find()->where(['alternative_email' => $email, 'secondary_email_salt' => $token])->one();
                if($user){
                    $emailType = 'secondary';
                }else{
                    // If above email and token don't match any user, the verification parameters are incorrect.
                    throw new Exception('Email address not verified');
                }
            }

            $currentDate = SmisHelper::formatDate('now', 'Y-m-d');
            if($emailType === 'primary'){
                $user->primary_email_verified_date = $currentDate;
            }else{
                $user->secondary_email_verified_date = $currentDate;
            }

            if(!$user->save()) {
                if (!$user->validate()) {
                    $transaction->rollBack();
                    $errorMessage = SmisHelper::getModelErrors($user->getErrors());
                    throw new Exception($errorMessage);
                } else {
                    throw new Exception('Emails not updated.');
                }
            }

            $transaction->commit();
            $this->setFlash('success', 'Emails', 'Email verified successfully.');
            return $this->redirect(['/account/index']);
        }catch (Exception $ex){
           $transaction->rollBack();
           $message = $ex->getMessage();
           if(YII_ENV_DEV){
               $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
           }
            throw new ServerErrorHttpException($message, 500);
        }
    }
}