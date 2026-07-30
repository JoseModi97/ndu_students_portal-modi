<?php

return [
    'apiClientID' => 'your-api-client-id',
    'apiKey' => 'your-api-key',
    'secret' => 'your-secret',
    'invoiceTokenKey' => 'generate-a-dedicated-random-key',
    'serviceID' => '2798167',
    'url' => 'https://payments.ecitizen.go.ke/PaymentAPI/iframev2.1.php',
    // May instead be supplied as ECITIZEN_CALLBACK_BASE_URL.
    'callbackBaseUrl' => 'https://portal.example.ac.ke',
    'allowedGatewayHosts' => ['payments.ecitizen.go.ke'],
    'workflowReportEnabled' => false,
    'invoiceTokenTtl' => 900,
    'maxPaymentAmount' => 99999999.99,
    'enforceFeeBalance' => false,
    'currency' => 'KES',
    'pictureURL' => '',
    'sendSTK' => false,
];
