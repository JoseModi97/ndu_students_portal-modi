<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\controllers;

use app\helpers\SmisHelper;
use app\models\ForgotPasswordForm;
use app\models\LoginForm;
use app\models\User;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['access' => "array"])]
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['error' => "string[]"])]
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if(parent::beforeAction($action)) {
            if ($action->id == 'error') {
                $this->layout = 'error';
            }
            return true;
        }
        return false;
    }

    /**
     * @return Response
     */
    public function actionIndex(): Response
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }
        return $this->redirect(['/account/index']);
    }

    /**
     * @return string|\yii\console\Response|Response
     * @throws ServerErrorHttpException
     */
    public function actionLogin(): Response|string|\yii\console\Response
    {
        try {
            if (Yii::$app->user->isGuest) {
                $this->layout = 'login';
                return $this->render('login', [
                    'title' => $this->createPageTitle('login'),
                    'model' => new LoginForm()
                ]);
            } else {
                /**
                 * Fully registered students are redirected to the portal dashboard.
                 * Not fully registered students are redirected to the registration page.
                 */
                if(Yii::$app->user->identity->admission_status === parent::PRE_REGISTERED_STATUS){
                    return Yii::$app->response->redirect(['/registration/add-documents']);
                }

                return Yii::$app->response->redirect(['/account/index']);
            }
        }catch(Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @return Response|string|\yii\console\Response
     * @throws ServerErrorHttpException
     */
    public function actionProcessLogin(): Response|string|\yii\console\Response
    {
        try {
            $model = new LoginForm();
            if($model->load(Yii::$app->request->post())){
                if($model->validate()){
                    if(Yii::$app->user->login($model->getUser())){
                        $this->setFlash('success', 'Login', 'Logged in successfully.');
                        // Not fully registered students are redirected to the registration page.
                        if(Yii::$app->user->identity->admission_status === parent::PRE_REGISTERED_STATUS){
                            return Yii::$app->response->redirect(['/registration/add-documents']);
                        }
                        // Fully registered students are redirected to the portal dashboard.
                        return Yii::$app->response->redirect(['/account/index']);
                    }else{
                        throw new Exception('An error occurred while trying to log in.');
                    }
                }else{
                    $this->setFlash('danger', 'Login', 'Incorrect username or password.');
                    return $this->redirect(['/site/login']);
                }
            }
            return $this->redirect(['/site/login']);
        }catch(Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Display page for forget password
     * @throws ServerErrorHttpException
     */
    public function actionForgotPassword(): string
    {
        try {
            $this->layout = 'login';
            return $this->render('forgotPassword', [
                'title' => $this->createPageTitle('I forgot my password'),
                'model' => new ForgotPasswordForm()
            ]);
        }catch(Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @return Response
     * @throws ServerErrorHttpException
     */
    public function actionPasswordReset(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();

            $email = $post['ForgotPasswordForm']['email'];
            $refNumber =  $post['ForgotPasswordForm']['username'];

            $user = User::find()->where(['primary_email' => $email, 'adm_refno' => $refNumber])->one();
            if(!$user){
                $user = User::find()->where(['alternative_email' => $email, 'adm_refno' => $refNumber])->one();
                if(!$user){
                    $this->setFlash('danger', 'Password reset', 'Incorrect reference number or email');
                    return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
                }
            }

            if($user){
                $password = $user->generatePassword();
                $user->password = $password['hash'];
                $user->password_changed_date = null;
                if ($user->save()) {
                    $emails = [
                        'recipientEmail' => $email,
                        'subject' => 'PASSWORD RESET',
                        'params' => [
                            'recipient' => $user->surname,
                            'password' => $password['plain']
                        ]
                    ];

                    $layout = '@app/mail/layouts/html';
                    $view = '@app/mail/views/passwordReset';
                    SmisHelper::sendEmails([$emails], $layout, $view);
                }else{
                    if(!$user->validate()){
                        $transaction->rollBack();
                        $errorMessage = SmisHelper::getModelErrors($user->getErrors());
                        $this->setFlash('danger', 'Password reset', $errorMessage);
                        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
                    }else{
                        throw new Exception('Profile not updated.');
                    }
                }
            }

            $this->setFlash('success', 'Password reset', 'A new password has been sent to your email address.');
            $transaction->commit();
            Yii::$app->user->logout();
            return $this->redirect(['/site/login']);
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
