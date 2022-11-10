<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\controllers;

use app\helpers\SmisHelper;
use app\models\AdmittedStudent;
use app\models\RegistrationDocument;
use app\models\RequiredDocument;
use app\models\SubmittedDocument;
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
        $submittedDocs = SubmittedDocument::find()->alias('sd')
            ->select([
                'sd.student_document_id',
                'sd.document_path',
                'sd.required_document_id',
                'sd.verify_status',
                'sd.doc_comments'
            ])
            ->where(['sd.adm_refno' => Yii::$app->user->identity->adm_refno])
            ->joinWith(['requiredDocument reqDoc' => function(ActiveQuery $q){
                $q->select([
                    'reqDoc.required_document_id',
                    'reqDoc.fk_document_id'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['requiredDocument.document doc' => function(ActiveQuery $q){
                $q->select([
                    'doc.document_id',
                    'doc.document_name',
                    'doc.document_desc'
                ]);
            }], true, 'INNER JOIN')
            ->asArray()
            ->all();

        $admittedStudent = AdmittedStudent::find()->select(['doc_submission_status'])
            ->where(['adm_refno' => Yii::$app->user->identity->adm_refno])->one();

        return $this->render('index', [
            'title' => $this->createPageTitle('my documents'),
            'user' => User::findOne(Yii::$app->user->identity->adm_refno),
            'submittedDocs' => $submittedDocs,
            'submitted' => $admittedStudent->doc_submission_status
        ]);
    }

    /**
     * @return Response|string
     */
    public function actionAddDocuments(): Response|string
    {
        $admittedStudent = AdmittedStudent::find()->select(['doc_submission_status'])
            ->where(['adm_refno' => Yii::$app->user->identity->adm_refno])->one();
        if($admittedStudent->doc_submission_status){
            return $this->redirect(['/registration/index']);
        }

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

        return $this->render('addDocuments', [
            'title' => $this->createPageTitle('add documents'),
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
            // Submitted docs must not be re-uploaded
            $admittedStudent = AdmittedStudent::find()->select(['doc_submission_status'])
                ->where(['adm_refno' => Yii::$app->user->identity->adm_refno])->one();
            if($admittedStudent->doc_submission_status){
                $this->setFlash('danger', 'Upload', 'Document failed to upload.');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

            if(empty($_FILES)){
                $this->setFlash('danger', 'Registration', 'No documents selected for upload.');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

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

            $adminRefNumber = Yii::$app->user->identity->adm_refno;

            /**
             * If a document already exists, delete and re-upload
             */
            $documentTypes = array_keys($_FILES);
            foreach ($documentTypes as $documentType){
                $newDocumentType = str_replace('-', '_', $documentType);
                $docPath = $path . '/' . $newDocumentType . '/';

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

                /**
                 * In documentType document-46, 46 is the document id
                 */
                $docId = substr($documentType, 9);
                $reqDocId = RequiredDocument::find()->where([
                    'fk_document_id' => $docId,
                    'fk_category_id' => Yii::$app->user->identity->student_category_id
                ])->one();

                $requiredDocId = $reqDocId->required_document_id;
                $submittedDocument = SubmittedDocument::find()->where([
                    'required_document_id' => $requiredDocId,
                    'adm_refno' => Yii::$app->user->identity->adm_refno
                ])->one();
                if(!$submittedDocument){
                    $submittedDocument = new SubmittedDocument();
                }
                $submittedDocument->required_document_id = $requiredDocId;
                $submittedDocument->document_path = $adminRefNumber . '/' . $newDocumentType . '/' . $newFileName;
                $submittedDocument->upload_date = SmisHelper::formatDate('now', 'Y-m-d');;
                $submittedDocument->verify_status = 'pending';
                $submittedDocument->adm_refno = $adminRefNumber;
                if(!$submittedDocument->save()){
                    if (!$submittedDocument->validate()) {
                        $transaction->rollBack();
                        $errorMessage = SmisHelper::getModelErrors($submittedDocument->getErrors());
                        return $this->asJson(['success' => false, 'message' => $errorMessage]);
                    } else {
                        throw new Exception('Documents uploaded were not saved.');
                    }
                }
            }

            $transaction->commit();
            $this->setFlash('success', 'Registration', 'Documents uploaded successfully.');
            return $this->redirect(['/registration/index']);
        }catch(Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @param string $id
     * @return Response|\yii\console\Response
     */
    public function actionDownloadDocument(string $id): Response|\yii\console\Response
    {
        try{
            $submittedDoc = SubmittedDocument::findOne($id);
            if(!$submittedDoc){
                $this->setFlash('danger', 'Download', 'Document failed to download.');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

            if($submittedDoc->adm_refno !== Yii::$app->user->identity->adm_refno){
                $this->setFlash('danger', 'Download', 'Document failed to download.');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

            $filepath = Yii::getAlias('@regDocsUploadUrl') . $submittedDoc->document_path;

            $docParts = explode('/', $submittedDoc->document_path);

            return Yii::$app->response->sendFile($filepath, $docParts[2], ['inline' => true]);
        }catch(Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['success' => false, 'message' => $message]);
        }
    }

    /**
     * @return Response
     * @throws \Throwable
     */
    public function actionDeleteDocument(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();

            // Submitted docs must not be deleted
            $admittedStudent = AdmittedStudent::find()->select(['doc_submission_status'])
                ->where(['adm_refno' => Yii::$app->user->identity->adm_refno])->one();

            if($admittedStudent->doc_submission_status){
                $this->setFlash('danger', 'Delete', 'Document failed to delete.');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

            $submittedDoc = SubmittedDocument::findOne($post['id']);
            if(!$submittedDoc){
                $this->setFlash('danger', 'Delete', 'Document failed to delete.');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

            // Other users' docs must not be deleted
            if($submittedDoc->adm_refno !== Yii::$app->user->identity->adm_refno){
                $this->setFlash('danger', 'Delete', 'Document failed to delete.');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

            $docParts = explode('/', $submittedDoc->document_path);

            $docPath = Yii::getAlias('@regDocsUploadUrl') . $docParts[0] . '/' . $docParts[1] . '/';

            if(is_dir($docPath)){
                $fileNames = array_diff(scandir($docPath), ['.', '..']);
                if(!empty($fileNames)){
                    foreach ($fileNames as $fileName){
                        $filePath = $docPath . $fileName;
                        if (!unlink($filePath)) {
                            throw new Exception('Can not be delete file due to an error');
                        }
                    }
                }
            }

            if(!$submittedDoc->delete()){
                $transaction->rollBack();
                return $this->asJson(['success' => false, 'message' => 'Document not deleted']);
            }else{
                $transaction->commit();
                $this->setFlash('success', 'Registration', 'Documents deleted successfully.');
            }

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
    public function actionSubmitDocuments(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $admittedStudent = AdmittedStudent::findOne(Yii::$app->user->identity->adm_refno);
            $admittedStudent->doc_submission_status = true;
            if(!$admittedStudent->save()){
                if(!$admittedStudent->validate()){
                    $transaction->rollBack();
                    $errorMessage = SmisHelper::getModelErrors($admittedStudent->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }else{
                    throw new Exception('Documents not submitted.');
                }
            }
            $transaction->commit();
            $this->setFlash('success', 'Registration', 'Documents submitted successfully.');
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
}