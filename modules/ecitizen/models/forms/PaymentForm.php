<?php

namespace app\modules\ecitizen\models\forms;

use Yii;
use yii\base\Model;

class PaymentForm extends Model
{
    public const DEFAULT_NARRATION = 'Not Posted';

    public ?string $amount = null;
    public ?string $payment_type_id = null;
    public ?string $bank_account_id = null;
    public ?string $narration = self::DEFAULT_NARRATION;
    public ?string $phone_number = null;

    public function rules(): array
    {
        return [
            [['amount', 'payment_type_id', 'bank_account_id', 'narration', 'phone_number'], 'required'],
            [['amount'], 'match', 'pattern' => '/^\d+(?:\.\d{1,2})?$/', 'message' => 'Amount must be a positive number with no more than two decimal places.'],
            [['amount'], 'number', 'min' => 1, 'max' => $this->maxPaymentAmount()],
            [['payment_type_id', 'bank_account_id'], 'integer', 'min' => 1],
            [['narration', 'phone_number'], 'trim'],
            [['narration'], 'string', 'max' => 100],
            [['phone_number'], 'string', 'min' => 9, 'max' => 20],
            [['phone_number'], 'match', 'pattern' => '/^\+?[0-9\s-]+$/', 'message' => 'Phone number can only contain digits, spaces, hyphens, or a leading plus sign.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'amount' => 'Amount',
            'payment_type_id' => 'Payment type',
            'bank_account_id' => 'Settlement account',
            'narration' => 'Narration',
            'phone_number' => 'Phone number',
        ];
    }

    public function maxPaymentAmount(): float
    {
        $module = Yii::$app->getModule('ecitizen');
        return (float) ($module->ecitizenParams()['maxPaymentAmount'] ?? 99999999.99);
    }
}
