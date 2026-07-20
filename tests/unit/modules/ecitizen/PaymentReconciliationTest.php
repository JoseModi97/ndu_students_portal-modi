<?php

namespace tests\unit\modules\ecitizen;

use app\modules\ecitizen\services\PaymentService;
use Codeception\Test\Unit;

class PaymentReconciliationTest extends Unit
{
    private PaymentService $payments;

    protected function _before(): void
    {
        $reflection = new \ReflectionClass(PaymentService::class);
        $this->payments = $reflection->newInstanceWithoutConstructor();
    }

    public function testMatchingSettledResponseIsConfirmed(): void
    {
        $result = $this->payments->classifyReconciliationPayload($this->request(), [
            'status' => 'Settled',
            'client_invoice_ref' => 'ECIT-TEST-1',
            'amount_paid' => '1500.00',
        ]);

        self::assertSame(PaymentService::RECONCILIATION_CONFIRMED, $result['reconciliation_status']);
    }

    public function testMatchingReversedResponseRequiresReview(): void
    {
        $result = $this->payments->classifyReconciliationPayload($this->request(), [
            'status' => 'Reversed',
            'client_invoice_ref' => 'ECIT-TEST-1',
            'reversed_amount' => '1500.00',
        ]);

        self::assertSame(PaymentService::RECONCILIATION_REVIEW_REQUIRED, $result['reconciliation_status']);
    }

    /**
     * @dataProvider unsafeResponses
     */
    public function testUnsafeResponseNeverTriggersReversal(array $payload): void
    {
        $result = $this->payments->classifyReconciliationPayload($this->request(), $payload);

        self::assertSame(PaymentService::RECONCILIATION_UNKNOWN, $result['reconciliation_status']);
    }

    public function unsafeResponses(): array
    {
        return [
            'pending' => [[
                'status' => 'Pending',
                'client_invoice_ref' => 'ECIT-TEST-1',
                'amount_paid' => '1500.00',
            ]],
            'unknown status' => [[
                'status' => 'Cancelled',
                'client_invoice_ref' => 'ECIT-TEST-1',
                'amount_paid' => '1500.00',
            ]],
            'wrong reference' => [[
                'status' => 'Reversed',
                'client_invoice_ref' => 'ECIT-OTHER',
                'reversed_amount' => '1500.00',
            ]],
            'missing reference' => [[
                'status' => 'Reversed',
                'reversed_amount' => '1500.00',
            ]],
            'wrong amount' => [[
                'status' => 'Reversed',
                'client_invoice_ref' => 'ECIT-TEST-1',
                'reversed_amount' => '500.00',
            ]],
            'missing amount' => [[
                'status' => 'Reversed',
                'client_invoice_ref' => 'ECIT-TEST-1',
            ]],
        ];
    }

    private function request(): array
    {
        return [
            'billRefNumber' => 'ECIT-TEST-1',
            'amountExpected' => '1500.00',
        ];
    }
}
