<?php

namespace tests\unit\modules\ecitizen;

use app\modules\ecitizen\services\PaymentService;
use Codeception\Test\Unit;
use Yii;
use yii\web\BadRequestHttpException;

class InvoiceTokenSecurityTest extends Unit
{
    private PaymentService $payments;

    protected function _before(): void
    {
        Yii::$app->setModule('ecitizen', [
            'class' => 'app\modules\ecitizen\Module',
            'params' => [
                'apiKey' => 'unit-test-api-key-with-sufficient-entropy',
                'secret' => 'unit-test-gateway-secret',
                'invoiceTokenKey' => 'unit-test-dedicated-invoice-token-key-with-sufficient-entropy',
                'invoiceTokenTtl' => 900,
            ],
        ]);

        $reflection = new \ReflectionClass(PaymentService::class);
        $this->payments = $reflection->newInstanceWithoutConstructor();
    }

    public function testTokenConcealsAndResolvesTransactionId(): void
    {
        $token = $this->payments->invoiceToken(123456, 'C01/1234/2026');

        self::assertStringNotContainsString('123456', $token);
        self::assertSame(123456, $this->payments->transIdFromInvoiceToken($token, 'C01/1234/2026'));
    }

    public function testTokenUsesRandomEncryption(): void
    {
        $first = $this->payments->invoiceToken(123456, 'C01/1234/2026');
        $second = $this->payments->invoiceToken(123456, 'C01/1234/2026');

        self::assertNotSame($first, $second);
    }

    public function testTamperedTokenIsRejected(): void
    {
        $token = $this->payments->invoiceToken(123456, 'C01/1234/2026');
        $token[10] = $token[10] === 'A' ? 'B' : 'A';

        $this->expectException(BadRequestHttpException::class);
        $this->payments->transIdFromInvoiceToken($token, 'C01/1234/2026');
    }

    public function testTokenCannotBeUsedByAnotherStudent(): void
    {
        $token = $this->payments->invoiceToken(123456, 'C01/1234/2026');

        $this->expectException(BadRequestHttpException::class);
        $this->payments->transIdFromInvoiceToken($token, 'C01/9999/2026');
    }

    public function testExpiredTokenIsRejected(): void
    {
        $keyMethod = new \ReflectionMethod(PaymentService::class, 'invoiceTokenKey');
        $keyMethod->setAccessible(true);
        $contextMethod = new \ReflectionMethod(PaymentService::class, 'invoiceTokenContext');
        $contextMethod->setAccessible(true);
        $payload = json_encode([
            'v' => 1,
            'trans_id' => 123456,
            'issued_at' => time() - 901,
        ], JSON_THROW_ON_ERROR);
        $encrypted = Yii::$app->security->encryptByKey(
            $payload,
            $keyMethod->invoke($this->payments),
            $contextMethod->invoke($this->payments, 'C01/1234/2026')
        );
        $token = rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');

        $this->expectException(BadRequestHttpException::class);
        $this->payments->transIdFromInvoiceToken($token, 'C01/1234/2026');
    }

    /**
     * @dataProvider notificationStatuses
     */
    public function testOnlyExplicitFinalNotificationStatusesArePaid(string $status, bool $expected): void
    {
        self::assertSame($expected, PaymentService::notificationStatusIsPaid($status));
    }

    public function notificationStatuses(): array
    {
        return [
            'blank' => ['', false],
            'pending' => ['PENDING', false],
            'failed' => ['FAILED', false],
            'paid' => ['PAID', true],
            'settled case insensitive' => ['settled', true],
        ];
    }

    /**
     * @dataProvider paymentDates
     */
    public function testNormalizesGatewayPaymentDates(string $value, string $expected): void
    {
        self::assertSame($expected, PaymentService::normalizePaymentDate($value));
    }

    public function paymentDates(): array
    {
        return [
            'gateway timezone format' => ['2026-06-28 11:30:06+03:00 EAT Africa/Nairobi', '2026-06-28'],
            'ISO date time' => ['2026-06-28T08:30:06Z', '2026-06-28'],
            'plain date' => ['2026-06-28', '2026-06-28'],
            'invalid date' => ['not-a-date', ''],
        ];
    }
}
