<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\controllers;

use Exception;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;

class BaseController extends Controller
{
    const REGISTERED_STATUS = 'REGISTERED';

    const PRE_REGISTERED_STATUS = 'PRE-REGISTRATION';

    /**
     * Setup controllers with initial data
     * @return void
     * @throws ServerErrorHttpException
     */
    public function init(): void
    {
        try{
            parent::init();
        }catch(Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if(parent::beforeAction($action)){
            if(!Yii::$app->user->isGuest){
                $identity = Yii::$app->user->identity;

                // These actions are accessible even when user profile is incomplete
                $exemptedActions = [
                    'account',
                    'error',
                    'logout',
                    'verify'
                ];

                if (in_array('Yii::$app->controller->id', $exemptedActions)) {
                    /**
                     * Check if user's default/forgotten password has been updated.
                     * We require that these be updated to a password user will remember and also make sure it meets the set requirements.
                     */
                    if (empty($identity->password_changed_date)){
                        $this->setFlash('danger', 'Update password', 'You must change your password before you continue.');
                        $this->redirect(['/account/index']);
                        return false;
                    }

                    /**
                     * Check if user profile is complete.
                     * All mandatory fields that can be updated from the user's interface must be present.
                     */
                    $profileComplete = true;
                    if (empty($identity->post_code)) {
                        $profileComplete = false;
                    } elseif (empty($identity->post_address)) {
                        $profileComplete = false;
                    } elseif (empty($identity->town)) {
                        $profileComplete = false;
                    }elseif (empty($identity->service)) {
                        $profileComplete = false;
                    }elseif (empty($identity->service_number)) {
                        $profileComplete = false;
                    }elseif (empty($identity->blood_group)) {
                        $profileComplete = false;
                    }elseif (empty($identity->date_of_birth)) {
                        $profileComplete = false;
                    }elseif (empty($identity->nationality)) {
                        $profileComplete = false;
                    }elseif (empty($identity->sponsor)) {
                        $profileComplete = false;
                    }

                    if (!$profileComplete) {
                        $this->setFlash('danger', 'Account settings', 'You must complete your profile before you continue.');
                        $this->redirect(['/account/index']);
                        return false;
                    }

                    /**
                     * All provided emails must be verified
                     */
                    $emailVerified = true;
                    if (empty($identity->primary_email)) {
                        $emailVerified = false;
                    } elseif (empty($identity->primary_email_verified_date)) {
                        $emailVerified = false;
                    } elseif (!empty($identity->alternative_email) && empty($identity->secondary_email_verified_date)) {
                        $emailVerified = false;
                    }

                    if (!$emailVerified) {
                        $this->setFlash('danger', 'Account settings', 'You must verify all your emails before you continue.');
                        $this->redirect(['/account/index']);
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }

    /**
     * @param string $type
     * @param string $title
     * @param string $msg
     * @return void
     */
    protected function setFlash(string $type, string $title, string $msg): void
    {
        Yii::$app->getSession()->setFlash('new', [
            'type' => $type,
            'title' => $title,
            'message' => $msg
        ]);
    }

    /**
     * @param string $type
     * @param string $title
     * @param string $msg
     * @return void
     */
    protected function addFlash(string $type, string $title, string $msg): void
    {
        Yii::$app->getSession()->addFlash('added', [
            'type' => $type,
            'title' => $title,
            'message' => $msg
        ]);
    }

    /**
     * Create the page title
     * @param string $title
     * @return string full page title
     */
    protected function createPageTitle(string $title): string
    {
        return Yii::$app->params['sitename'] . ' - ' . $title;
    }
}