<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\controllers;

use app\helpers\SmisHelper;
use app\models\User;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

class AccountController extends BaseController
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
     */
    public function actionIndex(): string
    {
        return $this->render('index', [
            'title' => $this->createPageTitle('profile'),
            'user' => User::findOne(Yii::$app->user->identity->adm_refno)
        ]);
    }

    /**
     * @return Response
     */
    public function actionUpdateProfile(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $user = User::findOne(Yii::$app->user->identity->adm_refno);
            $user->primary_phone_no = $post['primaryPhone'];
            $user->alternative_phone_no = $post['secondaryPhone'];
            $user->post_address = $post['postAddress'];
            $user->post_code = $post['postCode'];
            $user->town = $post['town'];
            $user->national_id = $post['nationalIdNumber'];
            $user->birth_cert_no = $post['birthCertificateNumber'];
            $user->passport_no = $post['passportNumber'];
            if(!$user->save()){
                if(!$user->validate()){
                    $transaction->rollBack();
                    $errorMessage = SmisHelper::getModelErrors($user->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }else{
                    throw new Exception('Profile not updated.');
                }
            }
            $transaction->commit();
            $this->setFlash('success', 'Profile', 'Profile updated successfully.');
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }catch (Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @return Response
     */
    public function actionUpdatePassword(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $user = User::findOne(Yii::$app->user->identity->adm_refno);

            if(!$user->validatePassword($post['oldPassword'])){
                return $this->asJson(['success' => false, 'message' => 'Incorrect password']);
            }

            $user->password = Yii::$app->getSecurity()->generatePasswordHash($post['newPassword']);
            $user->password_changed_date = SmisHelper::formatDate('now', 'Y-m-d');
            if(!$user->save()){
                if(!$user->validate()){
                    $transaction->rollBack();
                    $errorMessage = SmisHelper::getModelErrors($user->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }else{
                    throw new Exception('Password not updated.');
                }
            }
            $transaction->commit();
            return $this->asJson(['success' => true, 'message' => 'Password changed successfully.']);
        }catch (Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @return Response
     */
    public function actionUpdateEmail(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $user = User::findOne(Yii::$app->user->identity->adm_refno);

            $primaryEmail = $post['primaryEmail'];
            $secondaryEmail = $post['secondaryEmail'];

            if(!empty($primaryEmail)){
                $user->primary_email = $primaryEmail;
                $user->primary_email_verified_date = null;
                $primaryEmailToken = Yii::$app->getSecurity()->generateRandomString();
                $user->primary_email_salt = Yii::$app->security->generatePasswordHash($primaryEmailToken);
            }

            $user->alternative_email = $secondaryEmail;
            $user->secondary_email_verified_date = null;
            $user->secondary_email_salt = null;
            if(!empty($secondaryEmail)){
                $secondaryEmailToken = Yii::$app->getSecurity()->generateRandomString();
                $user->secondary_email_salt = Yii::$app->security->generatePasswordHash($secondaryEmailToken);
            }

            if($user->save()){
                // Send instructions on how to verify the updated email
                if(!empty($primaryEmail)) {
                    $this->sendEmailChangeVerification($user->surname, $user->primary_email, $user->primary_email_salt);
                }
                if(!empty($secondaryEmail)) {
                    $this->sendEmailChangeVerification($user->surname, $user->alternative_email, $user->secondary_email_salt);
                }
            }else{
                if(!$user->validate()){
                    $transaction->rollBack();
                    $errorMessage = SmisHelper::getModelErrors($user->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }else{
                    throw new Exception('Emails not updated.');
                }
            }

            $transaction->commit();
            $this->setFlash('success', 'Emails', 'Follow the instructions sent to your inbox to verify your email address.');
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }catch (Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @param string $recipientName
     * @param string $recipientEmail
     * @param string $token
     * @return void
     * @throws Exception
     */
    private function sendEmailChangeVerification(string $recipientName, string $recipientEmail, string $token): void
    {
        $emails = [
            'recipientEmail' => $recipientEmail,
            'subject' => 'EMAIL CHANGE VERIFICATION',
            'params' => [
                'recipient' => $recipientName,
                'email' => $recipientEmail,
                'token' => $token
            ]
        ];
        $layout = '@app/mail/layouts/html';
        $view = '@app/mail/views/verifyEmail';
        SmisHelper::sendEmails([$emails], $layout, $view);
    }
}