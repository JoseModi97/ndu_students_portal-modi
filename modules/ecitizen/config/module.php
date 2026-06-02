<?php

$credentialsFile = __DIR__ . '/credentials.php';
$credentials = is_file($credentialsFile) ? require $credentialsFile : [];

return [
    'class' => 'app\modules\ecitizen\Module',
    'portalDb' => 'db',
    'smisDb' => 'smisDb',
    'params' => [
        'apiClientID' => $credentials['apiClientID'] ?? null,
        'apiKey' => $credentials['apiKey'] ?? null,
        'secret' => $credentials['secret'] ?? null,
        'serviceID' => $credentials['serviceID'] ?? '2798167',
        'url' => $credentials['url'] ?? 'https://payments.ecitizen.go.ke/PaymentAPI/iframev2.1.php',
        'statusUrl' => $credentials['statusUrl'] ?? null,
        'caBundlePath' => $credentials['caBundlePath'] ?? null,
        'currency' => $credentials['currency'] ?? 'KES',
        'pictureURL' => $credentials['pictureURL'] ?? '',
        'bankAccountId' => $credentials['bankAccountId'] ?? '51',
        'sendSTK' => (bool) ($credentials['sendSTK'] ?? false),
    ],
];
