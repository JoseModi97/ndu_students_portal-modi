<?php

namespace tests\unit\modules\ecitizen;

use app\modules\ecitizen\models\forms\PaymentForm;
use Codeception\Test\Unit;
use Yii;

class PaymentFormSecurityTest extends Unit
{
    protected function _before(): void
    {
        Yii::$app->setModule('ecitizen', [
            'class' => 'app\modules\ecitizen\Module',
            'params' => [
                'bankAccountId' => '51',
                'maxPaymentAmount' => 99999999.99,
            ],
        ]);
    }

    public function testAcceptsNormalPaymentAmount(): void
    {
        $model = $this->paymentForm('100.50');

        self::assertTrue($model->validate(), json_encode($model->getErrors()));
    }

    /**
     * @dataProvider unsafeAmounts
     */
    public function testRejectsUnsafeAmountFormats(string $amount): void
    {
        $model = $this->paymentForm($amount);

        self::assertFalse($model->validate());
        self::assertArrayHasKey('amount', $model->getErrors());
    }

    public function unsafeAmounts(): array
    {
        return [
            'scientific notation' => ['1e6'],
            'too many decimal places' => ['100.001'],
            'negative amount' => ['-100'],
            'non-numeric suffix' => ['100KES'],
            'above configured maximum' => ['100000000.00'],
        ];
    }

    private function paymentForm(string $amount): PaymentForm
    {
        return new PaymentForm([
            'amount' => $amount,
            'payment_type_id' => '1',
            'bank_account_id' => '51',
            'narration' => PaymentForm::DEFAULT_NARRATION,
            'phone_number' => '254712345678',
        ]);
    }
}
