<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

$ecitizenCredentialsFile = 'C:/Users/odhis/Desktop/ecitizen/config/credentials.php';
$ecitizenCredentials = is_file($ecitizenCredentialsFile) ? require $ecitizenCredentialsFile : [];

return [
    'sitename' => 'smis',
    'sitenameLong' => 'smis',
    'icon-framework' => 'fa',
    'senderName' => 'ndu smis',
    'noReplyEmail' => 'ndukenyadev@uonbi.ac.ke',
    'bsVersion' => '5.x', // this will set globally `bsVersion` to Bootstrap 4.x for all Krajee Extensions
    'verifyEmailUrl' => 'http://localhost:81/ndu_students_portal/web/verify/email',
    // These controllers are accessible even when user profile is incomplete
    'accessibleControllersIfProfileIncomplete' => [
        'account',
        'verify'
    ],
    // These actions are accessible even when user profile is incomplete
    'accessibleActionsIfProfileIncomplete' => [
        'error',
        'logout',
    ],
    'changePasswordUrl' => 'http://adpassreset.ndu.ac.ke/',
    'ecitizen' => [
        'apiClientID' => getenv('ECITIZEN_API_CLIENT_ID') ?: ($ecitizenCredentials['apiClientID'] ?? null),
        'apiKey' => getenv('ECITIZEN_API_KEY') ?: ($ecitizenCredentials['apiKey'] ?? null),
        'secret' => getenv('ECITIZEN_SECRET') ?: ($ecitizenCredentials['secret'] ?? null),
        'serviceID' => getenv('ECITIZEN_SERVICE_ID') ?: '2798167',
        'url' => getenv('ECITIZEN_PAYMENT_URL') ?: 'https://payments.ecitizen.go.ke/PaymentAPI/iframev2.1.php',
        'statusUrl' => getenv('ECITIZEN_STATUS_URL') ?: null,
        'caBundlePath' => getenv('ECITIZEN_CA_BUNDLE_PATH') ?: null,
        'currency' => getenv('ECITIZEN_CURRENCY') ?: 'KES',
        'pictureURL' => getenv('ECITIZEN_PICTURE_URL') ?: '',
        'bankAccountId' => getenv('ECITIZEN_BANK_ACCOUNT_ID') ?: null,
        'sendSTK' => filter_var(getenv('ECITIZEN_SEND_STK') ?: false, FILTER_VALIDATE_BOOLEAN),
    ],
];
