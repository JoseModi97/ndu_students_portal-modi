<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\helpers;

use app\models\RequiredDocument;
use app\models\StudentProgCurriculum;
use app\models\StudentSemesterSessionProgress;
use app\models\SubmittedDocument;
use DateTime;
use DateTimeZone;
use Exception;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class SmisHelper
{
    /**
     * @param array $modelErrors
     * @return string
     */
    public static function getModelErrors(array $modelErrors): string
    {
        $errorMsg = '';
        foreach ($modelErrors as $attributeErrors){
            for($i=0; $i < count($attributeErrors); $i++){
                $errorMsg .= ' ' . $attributeErrors[$i];
            }
        }
        return $errorMsg;
    }

    /**
     * Send an email message
     *
     * @param array $emails content to be passed in the message body
     * @param string $layout Layout of the email message
     * @param string $view body of the email message
     *
     * @throws Exception if email not sent
     *
     * @return void
     */
    public static function sendEmails(array $emails, string $layout, string $view):void
    {
        foreach($emails as $email){
            if(!empty($email['recipientEmail'])){
                $recipientEmail = $email['recipientEmail'];
//                if(YII_ENV_DEV){
//                    $recipientEmail = Yii::$app->params['noReplyEmail'];
//                }
                $message = Yii::$app->mailer->compose();
                $message->setFrom([Yii::$app->params['noReplyEmail'] => Yii::$app->params['sitename']])
                    ->setTo($recipientEmail)
                    ->setSubject($email['subject']);

                $body = Yii::$app->mailer->render($view, $email['params'], $layout);
                $message->setHtmlBody($body);
                if(!$message->send()){
                    throw  new Exception('Email not sent.');
                }
            }
        }
    }

    /**
     * Get the format used for dates
     * @return string
     */
    public static function getDateFormat(): string
    {
        return Yii::$app->components['formatter']['dateFormat'];
    }

    /**
     * Get the format used for dates and time
     * @return string
     */
    public static function getDateTimeFormat(): string
    {
        return Yii::$app->components['formatter']['datetimeFormat'];
    }

    /**
     * Get the format used for dates and time
     * @return string
     */
    public static function getDefaultTimezone(): string
    {
        return Yii::$app->components['formatter']['defaultTimeZone'];
    }

    /**
     * Format date and/or time into various formats
     * @throws Exception
     */
    public static function formatDate(string $dateToFormat, string $format): string
    {
        $newDate = new DateTime($dateToFormat, new DateTimeZone(self::getDefaultTimezone()));
        return $newDate->format($format);
    }

    /**
     * Check if a student can submit their registration documents
     * @param string $admRefNo
     * @param string $studentCategory
     * @return bool
     */
    public static function documentsCanBeSubmitted(string $admRefNo, string $studentCategory): bool
    {
        $requiredDocuments = RequiredDocument::find()
            ->select([
                'required_document_id',
                'fk_document_id',
                'fk_category_id'
            ])->joinWith(['document doc' => function(ActiveQuery $q){
                $q->select([
                    'doc.required',
                    'doc.document_id'
                ]);
            }], true, 'INNER JOIN')
            ->where([
                'fk_category_id' => $studentCategory,
                'doc.required' => true
            ])
            ->count();

        $submittedDocuments = SubmittedDocument::find()->alias('sd')
            ->select([
                'sd.student_document_id',
                'sd.required_document_id'
            ])
            ->joinWith(['requiredDocument reqDoc' => function(ActiveQuery $q){
                $q->select([
                    'reqDoc.required_document_id',
                    'reqDoc.fk_document_id'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['requiredDocument.document doc' => function(ActiveQuery $q){
                $q->select([
                    'doc.document_id',
                    'doc.required',
                ]);
            }], true, 'INNER JOIN')
            ->where([
                'sd.adm_refno' => $admRefNo,
                'doc.required' => true
            ])
            ->count();

        if($requiredDocuments === $submittedDocuments){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return array|ActiveRecord|bool
     */
    public static function studentHasAvailableSessionToJoin(): array|ActiveRecord|bool
    {
        $admRefNo = Yii::$app->user->identity->adm_refno;
        $studentProgCurr = StudentProgCurriculum::find()->select(['student_prog_curriculum_id'])
            ->where(['adm_refno' => $admRefNo])->asArray()->one();

        if(empty($studentProgCurr)){
            return false;
        }

        $studentSemSessProgress = StudentSemesterSessionProgress::find()->alias('sp')
            ->select([
                'sp.student_semester_session_id',
                'sp.academic_progress_id'
            ])
            ->where(['sp.registration_date' => null])
            ->joinWith(['academicProgress ap' => function (ActiveQuery $q) {
                $q->select([
                    'ap.academic_progress_id',
                    'ap.student_prog_curriculum_id'
                ]);
            }], true, 'INNER JOIN')
            ->andWhere(['ap.student_prog_curriculum_id' => $studentProgCurr['student_prog_curriculum_id']])
            ->orderBy(['sp.student_semester_session_id' => SORT_DESC])
            ->asArray()
            ->one();

        if(empty($studentSemSessProgress)){
            return false;
        }

        return $studentSemSessProgress;
    }
}