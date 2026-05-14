<?php

namespace app\models;

/**
 * This is the model class for table "ecitizen".
 *
 * @property int $payment_id
 * @property int|null $apiClientID
 * @property string|null $secureHash
 * @property string|null $billDesc
 * @property string|null $billRefNumber
 * @property string|null $currency
 * @property string|null $serviceID
 * @property string|null $clientMSISDN
 * @property string|null $clientName
 * @property string|null $clientIDNumber
 * @property string|null $clientEmail
 * @property string|null $callBackURLOnSuccess
 * @property string|null $pictureURL
 * @property string|null $notificationURL
 * @property float|null $amountExpected
 * @property string|null $registration_number
 * @property string $trans_date
 * @property string|null $response
 * @property string|null $status
 */
class Ecitizen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ecitizen';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['apiClientID', 'secureHash', 'billDesc', 'billRefNumber', 'currency', 'serviceID', 'clientMSISDN', 'clientName', 'clientIDNumber', 'clientEmail', 'callBackURLOnSuccess', 'pictureURL', 'notificationURL', 'amountExpected', 'registration_number', 'response'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'Pending'],
            [['apiClientID'], 'integer'],
            [['amountExpected'], 'number'],
            [['trans_date'], 'safe'],
            [['response'], 'string'],
            [['secureHash'], 'string', 'max' => 255],
            [['billDesc', 'billRefNumber', 'clientName', 'clientEmail', 'callBackURLOnSuccess', 'pictureURL', 'notificationURL'], 'string', 'max' => 100],
            [['currency'], 'string', 'max' => 3],
            [['serviceID'], 'string', 'max' => 8],
            [['clientMSISDN'], 'string', 'max' => 20],
            [['clientIDNumber', 'registration_number'], 'string', 'max' => 30],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'payment_id' => 'Payment ID',
            'apiClientID' => 'Api Client ID',
            'secureHash' => 'Secure Hash',
            'billDesc' => 'Bill Desc',
            'billRefNumber' => 'Bill Ref Number',
            'currency' => 'Currency',
            'serviceID' => 'Service ID',
            'clientMSISDN' => 'Client Msisdn',
            'clientName' => 'Client Name',
            'clientIDNumber' => 'Client Id Number',
            'clientEmail' => 'Client Email',
            'callBackURLOnSuccess' => 'Call Back Url On Success',
            'pictureURL' => 'Picture Url',
            'notificationURL' => 'Notification Url',
            'amountExpected' => 'Amount Expected',
            'registration_number' => 'Registration Number',
            'trans_date' => 'Trans Date',
            'response' => 'Response',
            'status' => 'Status',
        ];
    }
}
