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

    /**
     * Upload registration documents
     * @return Response
     */
    public function actionUpload(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $validExtensions = ['jpeg', 'jpg', 'png', 'pdf'];

            /**
             * File paths take the format:
             * uploads/registration/6001/document_46/sample_data.txt
             * where:
             * 6001 is the admission ref no.
             * 46 is the document id in the db
             */
            $path = Yii::getAlias('@regDocsUploadUrl');
            $path .= Yii::$app->user->identity->adm_refno;

            if(!is_dir($path)){
                if(!mkdir($path, 0777, true)){
                    throw new Exception('Failed to create uploads directory.');
                }
            }

            /**
             * If a document already exists, delete and re-upload
             */
            $documentTypes = array_keys($_FILES);
            foreach ($documentTypes as $documentType){
                $docPath = $path . '/' . str_replace('-', '_', $documentType) . '/';
                if(is_dir($docPath)){
                    $fileNames = array_diff(scandir($docPath), ['.', '..']);
                    if(!empty($fileNames)){
                        foreach ($fileNames as $fileName){
                            $filePath = $docPath . $fileName;
                            if (!unlink($filePath)) {
                                throw new Exception('cannot be unlink file due to an error');
                            }
                        }
                    }
                }else{
                    if(!mkdir($docPath, 0777, true)){
                        throw new Exception('Failed to create uploads directory.');
                    }
                }

                $file = $_FILES[$documentType];
                if($file['error'] !== 0){
                    throw new Exception('An error occurred while trying to upload files.');
                }

                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if(!in_array($ext, $validExtensions)){
                    throw new Exception($ext . ' files are not allowed.');
                }

                $newFileName = strtolower(pathinfo($file['name'], PATHINFO_FILENAME));
                $newFileName = preg_replace('/\s/','_', $newFileName);
                $newFileName .= '.' . $ext;

                $destinationFile = $docPath . $newFileName;

                if(!move_uploaded_file($file['tmp_name'], $destinationFile)){
                    throw new Exception('Document not uploaded.');
                }
            }

            $transaction->commit();
            $this->setFlash('success', 'Registration', 'Documents uploaded successfully.');
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }catch(Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }
}