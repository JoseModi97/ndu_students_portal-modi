<?php

$credentialsFile = __DIR__ . '/credentials.php';
$credentials = is_file($credentialsFile) ? require $credentialsFile : [];

$environmentValue = static function (string $key): ?string {
    $value = getenv($key);
    if ($value === false || trim($value) === '') {
        return null;
    }

    return trim($value);
};

$callbackBaseUrl = rtrim(
    $environmentValue('ECITIZEN_CALLBACK_BASE_URL')
        ?? (string) ($credentials['callbackBaseUrl'] ?? 'https://smisportal.ndu.ac.ke'),
    '/'
);

return [
    'class' => 'app\modules\ecitizen\Module',
    'portalDb' => 'db',
    'params' => [
        'apiClientID' => $credentials['apiClientID'] ?? null,
        'apiKey' => $credentials['apiKey'] ?? null,
        'secret' => $credentials['secret'] ?? null,
        'invoiceTokenKey' => $credentials['invoiceTokenKey'] ?? null,
        'serviceID' => $credentials['serviceID'] ?? '2798167',
        'url' => $credentials['url'] ?? 'https://payments.ecitizen.go.ke/PaymentAPI/iframev2.1.php',
        'statusUrl' => $credentials['statusUrl'] ?? null,
        'caBundlePath' => $credentials['caBundlePath'] ?? null,
        'callbackBaseUrl' => $callbackBaseUrl,
        'allowedGatewayHosts' => $credentials['allowedGatewayHosts'] ?? ['payments.ecitizen.go.ke'],
        'workflowReportEnabled' => (bool) ($credentials['workflowReportEnabled'] ?? false),
        'invoiceTokenTtl' => max(60, (int) ($credentials['invoiceTokenTtl'] ?? 900)),
        'maxPaymentAmount' => min(99999999.99, max(1, (float) ($credentials['maxPaymentAmount'] ?? 99999999.99))),
        'enforceFeeBalance' => (bool) ($credentials['enforceFeeBalance'] ?? false),
        'currency' => $credentials['currency'] ?? 'KES',
        'pictureURL' => $credentials['pictureURL'] ?? '',
        'bankAccountId' => $credentials['bankAccountId'] ?? null,
        'sendSTK' => (bool) ($credentials['sendSTK'] ?? false),
    ],
];
