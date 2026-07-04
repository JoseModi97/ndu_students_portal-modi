<?php

return [
    'apiClientID' => 'your-api-client-id',
    'apiKey' => 'your-api-key',
    'secret' => 'your-secret',
    'invoiceTokenKey' => 'generate-a-dedicated-random-key',
    'serviceID' => '2798167',
    'url' => 'https://payments.ecitizen.go.ke/PaymentAPI/iframev2.1.php',
    'statusUrl' => 'https://payments.ecitizen.go.ke/api/invoice/payment/status',
    'callbackBaseUrl' => 'https://smisportalndudev.uonbi.ac.ke',
    'allowedPortalHosts' => ['smisportalndudev.uonbi.ac.ke'],
    'allowedGatewayHosts' => ['payments.ecitizen.go.ke'],
    'workflowReportEnabled' => false,
    'invoiceTokenTtl' => 900,
    'maxPaymentAmount' => 99999999.99,
    'enforceFeeBalance' => false,
    'caBundlePath' => null,
    'currency' => 'KES',
    'pictureURL' => '',
    // Optional: set this to a valid smis.fss_bank_accounts.brank_account_id to hide the selector.
    'bankAccountId' => null,
    'sendSTK' => false,
];
