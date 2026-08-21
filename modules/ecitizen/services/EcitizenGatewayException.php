<?php

namespace app\modules\ecitizen\services;

/**
 * Thrown by requestJson()/queryPaymentStatus() for eCitizen gateway call
 * failures. The message is always one of PaymentService's own curated,
 * secret-free strings (never a raw curl error or response body), so unlike
 * an arbitrary \Throwable it's safe to surface both the message and
 * $httpStatus to the browser console for client-side debugging.
 */
class EcitizenGatewayException extends \RuntimeException
{
    public function __construct(string $message, public readonly ?int $httpStatus = null)
    {
        parent::__construct($message);
    }
}
