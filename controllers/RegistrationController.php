<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\controllers;

use app\models\RegistrationDocument;
use app\models\RequiredDocument;
use app\models\User;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\web\Response;

class RegistrationController extends BaseController
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
        $documents = RequiredDocument::find()->select([
                'required_document_id',
                'fk_document_id',
                'fk_category_id'
            ])->joinWith(['document doc' => function(ActiveQuery $q){
                $q->select([
                    'doc.document_name',
                    'doc.document_desc',
                    'doc.required',
                    'doc.document_id'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['category cat' => function(ActiveQuery $q){
                $q->select([
                    'cat.std_category_id',
                    'cat.std_category_name'
                ]);
            }], true, 'INNER JOIN')
            ->where(['cat.std_category_id' => Yii::$app->user->identity->student_category_id])
            ->asArray()
            ->all();

        return $this->render('index', [
            'title' => $this->createPageTitle('registration documents'),
            'user' => User::findOne(Yii::$app->user->identity->adm_refno),
            'documents' => $documents
        ]);
    }

    /**
     * @param string $id
     * @return Response
     */
    public function actionRegistrationDocument(string $id): Response
    {
        try{
            return $this->asJson(['success' => true, 'document' => RegistrationDocument::find()->where(['document_id' => $id])
                ->asArray()->one()]);
        }catch(Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }
}