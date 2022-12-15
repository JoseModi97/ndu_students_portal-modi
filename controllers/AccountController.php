<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\controllers;

use app\helpers\SmisHelper;
use app\models\NameChange;
use app\models\search\NameChangeRequests;
use app\models\Student;
use app\models\StudentProgramme;
use app\models\User;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

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
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if(parent::beforeAction($action)){
            $identity = Yii::$app->user->identity;
            if($action->id == 'list-name-change'){
                if($identity->admission_status === parent::PRE_REGISTERED_STATUS){
                    $this->redirect(['/home/index']);
                    return false;
                }
            }
            return true;
        }
        return false;
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
            $admRefno = Yii::$app->user->identity->adm_refno;

            $user = User::findOne($admRefno);
            $this->updateProfile($user, $post, $transaction);

            // If student is registered, also update the student table
            if(Yii::$app->user->identity->admission_status === parent::REGISTERED_STATUS){
                $studentProgramme = StudentProgramme::find()->where(['adm_refno' => $admRefno])->one();
                $student = Student::findOne($studentProgramme->student_id);
                $this->updateProfile($student, $post, $transaction);
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
     * @param User|Student $profile
     * @param array $post
     * @param $transaction
     * @return void
     * @throws Exception
     */
    private function updateProfile(User|Student $profile, array $post, $transaction): void
    {
        $profile->primary_phone_no = $post['primaryPhone'];
        $profile->alternative_phone_no = $post['secondaryPhone'];
        $profile->post_address = $post['postAddress'];
        $profile->post_code = $post['postCode'];
        $profile->town = $post['town'];
        $profile->passport_no = $post['passportNumber'];
        if($profile instanceof User){
            $profile->national_id = $post['nationalIdNumber'];
            $profile->birth_cert_no = $post['birthCertificateNumber'];
        }else{
            $profile->id_no = $post['nationalIdNumber'];
        }
        if(!$profile->save()){
            if(!$profile->validate()){
                $transaction->rollBack();
                $errorMessage = SmisHelper::getModelErrors($profile->getErrors());
                $this->asJson(['success' => false, 'message' => $errorMessage]);
            }else{
                throw new Exception('Profile not updated.');
            }
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
            $admRefno = Yii::$app->user->identity->adm_refno;
            $user = User::findOne($admRefno);

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
            $admRefno = Yii::$app->user->identity->adm_refno;
            $user = User::findOne($admRefno);

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

            // If student is registered, also update the student table
            if(Yii::$app->user->identity->admission_status === parent::REGISTERED_STATUS){
                $studentProgramme = StudentProgramme::find()->where(['adm_refno' => $admRefno])->one();
                $student = Student::findOne($studentProgramme->student_id);

                if(!empty($primaryEmail)){
                    $student->primary_email = $primaryEmail;
                }
                $student->alternative_email = $secondaryEmail;

                if(!$student->save()){
                    if(!$student->validate()){
                        $transaction->rollBack();
                        $errorMessage = SmisHelper::getModelErrors($student->getErrors());
                        return $this->asJson(['success' => false, 'message' => $errorMessage]);
                    }else{
                        throw new Exception('Emails not updated.');
                    }
                }
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
     * List change name requests
     * @return string
     */
    public function actionListNameChange(): string
    {
        $studentProgramme = StudentProgramme::find()->select(['student_id', 'registration_number'])
            ->where(['adm_refno' => Yii::$app->user->identity->adm_refno])->asArray()->one();
        $studentId = $studentProgramme['student_id'];

        $requestsSearchModel = new NameChangeRequests();
        $requestsDataProvider = $requestsSearchModel->search(Yii::$app->request->queryParams, [
            'studentId' => $studentId
        ]);

        $canCreateRequest = false;
        $requestsCount = NameChange::find()->where(['student_id' => $studentId])->count();
        if($requestsCount == 0){
            $canCreateRequest = true;
        }else{
            $rejectRequestsCount = NameChange::find()
                ->where(['student_id' => $studentId, 'status' => 'REJECTED'])->count();
            if($rejectRequestsCount == $requestsCount){
                $canCreateRequest = true;
            }
        }

        return $this->render('listNameChange', [
            'title' => $this->createPageTitle('name change requests'),
            'requestsSearchModel' => $requestsSearchModel,
            'requestsDataProvider' => $requestsDataProvider,
            'canCreateRequest' => $canCreateRequest
        ]);
    }

    /**
     * Create change name requests
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionCreateNameChange(): string
    {
        try{
            return $this->render('createNameChange', [
                'title' => $this->createPageTitle('change name'),
                'user' => User::findOne(Yii::$app->user->identity->adm_refno)
            ]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Edit change name requests
     * @param string $requestId
     * @return string
     * @throws ServerErrorHttpException
     */
    public function actionEditNameChange(string $requestId): string
    {
        try{
            if(empty($requestId)){
                throw new Exception('Wrongly formed url');
            }

            $nameChange = NameChange::findOne($requestId);
            if(!$nameChange){
                throw new Exception('Wrongly formed url');
            }

            $studentProgramme = StudentProgramme::find()->select(['adm_refno'])
                ->where(['student_id' => $nameChange->student_id])->one();

            if($studentProgramme->adm_refno != Yii::$app->user->identity->adm_refno){
                throw new Exception('Wrongly formed url');
            }

            return $this->render('editNameChange', [
                'title' => $this->createPageTitle('change name'),
                'nameChange' => $nameChange
            ]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * Save change name requests
     * @return Response
     */
    public function actionStoreNameChange(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();

            $studentProgramme = StudentProgramme::find()->select(['student_id', 'registration_number'])
                ->where(['adm_refno' => Yii::$app->user->identity->adm_refno])->asArray()->one();
            $studentId = $studentProgramme['student_id'];
            $regNumber = $studentProgramme['registration_number'];

            $nameChange = new NameChange();
            $nameChange->student_id = $studentId;
            $nameChange->current_surname = Yii::$app->user->identity->surname;
            $nameChange->current_othernames = Yii::$app->user->identity->other_names;
            $nameChange->new_surname = $post['newSurname'];
            $nameChange->new_othernames = $post['newOtherNames'];
            $nameChange->reason = $post['reason'];
            $nameChange->request_date = SmisHelper::formatDate('now', 'Y-m-d');
            $nameChange->status = 'PENDING';

            if(!$nameChange->save()){
                if(!$nameChange->validate()){
                    $transaction->rollBack();
                    $errorMessage = SmisHelper::getModelErrors($nameChange->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }else{
                    throw new Exception('Name change request not created.');
                }
            }

            $docUrl = $this->uploadNameChangeDocs($nameChange->name_change_id, $regNumber, $_FILES['document']);

            $countUpdated = NameChange::updateAll(['document_url' => $docUrl], 'name_change_id = ' . $nameChange->name_change_id);
            if($countUpdated === 0){
                throw new Exception('Name change request not created.');
            }

            $transaction->commit();
            $this->setFlash('success', 'Change name', 'Request to change name has been submitted successfully.');
            return $this->redirect(['/account/list-name-change']);
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
     * Update change name requests
     * @return Response
     */
    public function actionUpdateNameChange(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();

            $nameChange = NameChange::findOne($post['id']);
            if(!$nameChange){
                throw new Exception('Wrongly formed parameters');
            }

            $studentProgramme = StudentProgramme::find()->select(['adm_refno', 'registration_number'])
                ->where(['student_id' => $nameChange->student_id])->one();
            $regNumber = $studentProgramme['registration_number'];

            if($studentProgramme->adm_refno != Yii::$app->user->identity->adm_refno){
                throw new Exception('Wrongly formed parameters');
            }

            $file = $_FILES['document'];
            if($file['error'] === 0){
                $docUrl = $this->uploadNameChangeDocs($nameChange->name_change_id, $regNumber, $file);
                $nameChange->document_url = $docUrl;
            }

            $nameChange->new_surname = $post['newSurname'];
            $nameChange->new_othernames = $post['newOtherNames'];
            $nameChange->reason = $post['reason'];
            $nameChange->request_date = SmisHelper::formatDate('now', 'Y-m-d');

            if(!$nameChange->save()){
                if(!$nameChange->validate()){
                    $transaction->rollBack();
                    $errorMessage = SmisHelper::getModelErrors($nameChange->getErrors());
                    return $this->asJson(['success' => false, 'message' => $errorMessage]);
                }else{
                    throw new Exception('Name change request not updated.');
                }
            }

            $transaction->commit();
            $this->setFlash('success', 'Change name', 'Request to change name has been updated successfully.');
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

    /**
     * @param string $requestId
     * @return Response|\yii\console\Response
     * @throws ServerErrorHttpException
     */
    public function actionDownloadNameChangeDoc(string $requestId): Response|\yii\console\Response
    {
        try{
            if(empty($requestId)){
                throw new Exception('Wrongly formed url');
            }

            $nameChange = NameChange::findOne($requestId);
            if(!$nameChange){
                throw new Exception('Wrongly formed url');
            }

            $studentProgramme = StudentProgramme::find()->select(['adm_refno'])
                ->where(['student_id' => $nameChange->student_id])->one();

            if($studentProgramme->adm_refno != Yii::$app->user->identity->adm_refno){
                throw new Exception('Wrongly formed url');
            }

            $filepath = Yii::getAlias('@changeNameDocsUploadDir') . $nameChange->document_url;

            $docParts = explode('/', $nameChange->document_url);

            return Yii::$app->response->sendFile($filepath, $docParts[2], ['inline' => true]);
        }catch (Exception $ex){
            $message = $ex->getMessage();
            if(YII_ENV_DEV) {
                $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    /**
     * @return Response
     * @throws Throwable
     */
    public function actionDeleteNameChange(): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $nameChange = NameChange::findOne($post['id']);

            if($nameChange->status !== 'PENDING'){
                throw new Exception('Name request failed to delete.');
            }

            $studentProgramme = StudentProgramme::find()->select(['adm_refno'])
                ->where(['student_id' => $nameChange->student_id])->one();

            if($studentProgramme->adm_refno != Yii::$app->user->identity->adm_refno){
                throw new Exception('Name request failed to delete.');
            }

            $docParts = explode('/', $nameChange->document_url);

            $docPath = Yii::getAlias('@changeNameDocsUploadDir') . $docParts[0] . '/' . $docParts[1] . '/';

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

            if(!$nameChange->delete()){
                $transaction->rollBack();
                return $this->asJson(['success' => false, 'message' => 'Supporting documents not deleted']);
            }else{
                $transaction->commit();
                $this->setFlash('success', 'Name change', 'Name change request deleted successfully.');
                return $this->redirect(['/account/list-name-change']);
            }
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

    /**
     * @param string $requestId
     * @param string $regNumber
     * @param array $file
     * @return string
     * @throws Exception
     */
    private function uploadNameChangeDocs(string $requestId, string $regNumber, array $file): string
    {
        if($file['error'] !== 0){
            throw new Exception('File error code: ' . $file['error']);
        }

        /**
         * File paths take the format:
         * uploads/change_name/KMA401_0114_2022/1/change_my_name.pdf
         * where:
         * KMA401_0114_2022 is the reg no.
         * 1 is the request id
         */
        $path = Yii::getAlias('@changeNameDocsUploadDir');
        $path .= str_replace('/', '_', $regNumber) . '/' . $requestId . '/';

        if(is_dir($path)){
            /**
             * If a document already exists, delete and re-upload
             */
            $fileNames = array_diff(scandir($path), ['.', '..']);
            if(!empty($fileNames)){
                foreach ($fileNames as $fileName){
                    if (!unlink($path . $fileName)) {
                        throw new Exception('cannot be unlink file due to an error');
                    }
                }
            }
        }else{
            if(!mkdir($path, 0777, true)){
                throw new Exception('Failed to create uploads directory.');
            }
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if($ext != 'pdf'){
            throw new Exception($ext . ' files are not allowed.');
        }

        $newFileName = strtolower(pathinfo($file['name'], PATHINFO_FILENAME));
        $newFileName = preg_replace('/\s/','_', $newFileName);
        $newFileName .= '.' . $ext;

        $destinationFile = $path . $newFileName;

        if(!move_uploaded_file($file['tmp_name'], $destinationFile)){
            throw new Exception('Document not uploaded.');
        }

        return str_replace('/', '_', $regNumber) . '/' . $requestId . '/' . $newFileName;
    }
}