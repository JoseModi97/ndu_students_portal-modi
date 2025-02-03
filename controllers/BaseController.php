<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\controllers;

use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
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
        try {
            parent::init();
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if (YII_ENV_DEV) {
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
        if (parent::beforeAction($action)) {
            if (!Yii::$app->user->isGuest) {
                $identity = Yii::$app->user->identity;

                $controllerId = Yii::$app->controller->id;
                $actionId = Yii::$app->controller->action->id;

                // These controllers/actions are accessible even when user profile is incomplete
                $exemptedControllers = Yii::$app->params['accessibleControllersIfProfileIncomplete'];
                $exemptedActions = Yii::$app->params['accessibleActionsIfProfileIncomplete'];

                $profileMustBeComplete = true;

                if (in_array($controllerId, $exemptedControllers) || in_array($actionId, $exemptedActions)) {
                    $profileMustBeComplete = false;
                }

                if ($profileMustBeComplete) {
                    /**
                     * The following fields can be updated by the student. So if they are not provided we'll ask the
                     * student to provide them.
                     */
                    $profileComplete = true;
                    if (empty($identity->post_code)) {
                        $profileComplete = false;
                    } elseif (empty($identity->post_address)) {
                        $profileComplete = false;
                    } elseif (empty($identity->town)) {
                        $profileComplete = false;
                    } elseif (empty($identity->blood_group)) {
                        $profileComplete = false;
                    } elseif (empty($identity->date_of_birth)) {
                        $profileComplete = false;
                    } elseif (strtolower($identity->nationality) === 'kenyan' && empty($identity->national_id)) {
                        // For Kenyans, passport is optional. National ID is mandatory.
                        $profileComplete = false;
                    } elseif (strtolower($identity->nationality) !== 'kenyan' && empty($identity->passport_no)) {
                        // For non-nationals, passport is mandatory. National ID is optional.
                        $profileComplete = false;
                    } elseif (empty($identity->primary_phone_no)) {
                        $profileComplete = false;
                    }

                    if (!$profileComplete) {
                        $this->setFlash('danger', 'Account settings', 'You must complete your profile before you continue.');
                        $this->redirect(['/account/index']);
                        return false;
                    }

                    /**
                     * @note for now the requirement to verify provided emails is paused
                     * @todo return email verification later
                     * All provided emails must be verified
                     */
//                    $emailVerified = true;
//                    if (empty($identity->primary_email)) {
//                        $emailVerified = false;
//                    } elseif (empty($identity->primary_email_verified_date)) {
//                        $emailVerified = false;
//                    } elseif (!empty($identity->alternative_email) && empty($identity->secondary_email_verified_date)) {
//                        $emailVerified = false;
//                    }
//                    if (!$emailVerified) {
//                        $this->setFlash('danger', 'Account settings', 'You must verify all your emails before you continue.');
//                        $this->redirect(['/account/index']);
//                        return false;
//                    }
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

    /**
     * @param array $models
     * @return array
     */
    protected function mergeModelErrors(array $models): array
    {
        $errors = [];
        foreach ($models as $model) {
            if ($model instanceof Model && $model->hasErrors()) {
                $errors = ArrayHelper::merge($errors, $model->getErrors());
            }
        }
        return $errors;
    }
}