<?php

namespace app\models\forms;

use yii\base\Model;

class EcitizenPaymentForm extends Model
{
    public ?string $amount = null;
    public ?string $payment_type_id = null;
    public ?string $bank_account_id = null;
    public ?string $narration = null;

    public function rules(): array
    {
        return [
            [['amount', 'payment_type_id'], 'required'],
            [['bank_account_id'], 'required', 'when' => fn () => empty($this->configuredBankAccountId())],
            [['amount'], 'number', 'min' => 1],
            [['payment_type_id', 'bank_account_id'], 'integer'],
            [['narration'], 'trim'],
            [['narration'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'amount' => 'Amount',
            'payment_type_id' => 'Payment type',
            'bank_account_id' => 'Settlement account',
            'narration' => 'Narration',
        ];
    }

    private function configuredBankAccountId(): ?string
    {
        return \Yii::$app->params['ecitizen']['bankAccountId'] ?? null;
    }
}
