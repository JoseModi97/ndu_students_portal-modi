<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\controllers;

use app\helpers\SmisHelper;
use app\models\ForgotPasswordForm;
use app\models\LoginForm;
use app\models\StudentProgCurriculum;
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
        if (parent::beforeAction($action)) {
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
        if (Yii::$app->user->isGuest) {
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
                if (Yii::$app->user->identity->admission_status === parent::PRE_REGISTERED_STATUS) {
                    return Yii::$app->response->redirect(['/registration/add-documents']);
                }

                return Yii::$app->response->redirect(['/account/index']);
            }
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
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

            if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
                return $this->incorrectCredentialsMessage();
            }

            // @todo re-enable
            if (Yii::$app->ldapAuth->authenticate($model->username, $model->password)) {

                $primaryEmail = Yii::$app->ldapAuth->findUserEntry($model->username)['email'];

                if (empty($primaryEmail)) {
                    return $this->incorrectCredentialsMessage();
                }
            } else {
                return $this->incorrectCredentialsMessage();
            }

            // @todo remove after testing
//            NR605/0001/2022
//            $primaryEmail = 'irene.adhiambo@niruc.ac.ke';

            // This email must match one in the AD
            $user = User::findByUsername($primaryEmail);

            if (!$user) {
                return $this->incorrectCredentialsMessage();
            }

            if (Yii::$app->user->login($user)) {
                $this->setFlash('success', 'Login', 'Logged in successfully.');

                // Not fully registered students are redirected to the registration page.
                if (Yii::$app->user->identity->admission_status === parent::PRE_REGISTERED_STATUS) {
                    return Yii::$app->response->redirect(['/registration/add-documents']);
                }

                // Fully registered students are redirected to the portal dashboard.
                return Yii::$app->response->redirect(['/account/index']);

            } else {
                throw new Exception('An error occurred while trying to log in.');
            }
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @return Response
     */
    private function incorrectCredentialsMessage(): Response
    {
        $this->setFlash('danger', 'Login', 'Incorrect username or password.');
        return $this->redirect(['/site/login']);
    }

    /**
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
