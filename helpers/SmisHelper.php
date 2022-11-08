<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\helpers;

use DateTime;
use DateTimeZone;
use Exception;
use Yii;

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
}