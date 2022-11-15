<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\controllers;

use app\models\SubmittedDocument;
use Exception;
use Yii;
use yii\web\Response;

class DownloadRegController extends BaseController
{
    /**
     * @param string $submittedDocId
     * @param string $admRefNo
     * @return Response
     */
    public function actionDoc(string $submittedDocId, string $admRefNo): Response
    {
        try{
            $submittedDoc = SubmittedDocument::findOne($submittedDocId);
            if(!$submittedDoc){
                $this->setFlash('danger', 'Download', 'Document failed to download.');
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }

            if($submittedDoc->adm_refno != $admRefNo){
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
}