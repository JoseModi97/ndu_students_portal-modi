<?php

namespace app\modules\ecitizen\controllers;

use app\controllers\BaseController;
use app\modules\ecitizen\models\forms\PaymentForm;
use app\modules\ecitizen\services\PaymentService;
use Yii;
use yii\base\DynamicModel;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

final class PaymentController extends BaseController
{
    private PaymentService $payments;

    public function init(): void
    {
        parent::init();
        $this->payments = new PaymentService();
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['notify'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'checkout' => ['post'],
                    'complete-payment' => ['post'],
                    'notify' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action): bool
    {
        if ($action->id === 'notify') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex(): string
    {
        $studentContext = $this->payments->resolveLoggedInStudent();
        $form = new PaymentForm();
        $configuredBankAccountId = $this->ecitizenParams()['bankAccountId'] ?? null;
        if (!empty($configuredBankAccountId)) {
            $form->bank_account_id = (string) $configuredBankAccountId;
        }
        $form->narration = PaymentForm::DEFAULT_NARRATION;
        $form->phone_number = $this->defaultPhoneNumber($studentContext);

        return $this->render('index', [
            'title' => $this->createPageTitle('eCitizen fee payment'),
            'model' => $form,
            'studentContext' => $studentContext,
            'paymentModeReady' => $this->payments->paymentModeExists(),
            'paymentTypes' => ArrayHelper::map($this->payments->paymentTypes(), 'payment_type_id', 'payment_desc'),
            'bankAccounts' => $this->bankAccountOptions(),
            'configuredBankAccountId' => $configuredBankAccountId,
            'recentRequests' => $this->payments->recentRequests($studentContext['registrationNumber']),
        ]);
    }

    public function actionCheckout(): string|Response|array
    {
        $model = new PaymentForm();
        $studentContext = $this->payments->resolveLoggedInStudent();
        $configuredBankAccountId = $this->ecitizenParams()['bankAccountId'] ?? null;

        if (!empty($configuredBankAccountId)) {
            $model->bank_account_id = (string) $configuredBankAccountId;
        }

        $loaded = $model->load(Yii::$app->request->post());
        if (!empty($configuredBankAccountId)) {
            $model->bank_account_id = (string) $configuredBankAccountId;
        }
        $model->narration = PaymentForm::DEFAULT_NARRATION;

        if (!$loaded || !$model->validate()) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $messages = [];
                foreach ($model->getErrors() as $errors) {
                    $messages[] = reset($errors);
                }
                return [
                    'success' => false,
                    'message' => implode('<br>', $messages),
                ];
            }

            return $this->render('index', [
                'title' => $this->createPageTitle('eCitizen fee payment'),
                'model' => $model,
                'studentContext' => $studentContext,
                'paymentModeReady' => $this->payments->paymentModeExists(),
                'paymentTypes' => ArrayHelper::map($this->payments->paymentTypes(), 'payment_type_id', 'payment_desc'),
                'bankAccounts' => $this->bankAccountOptions(),
                'configuredBankAccountId' => $configuredBankAccountId,
                'recentRequests' => $this->payments->recentRequests($studentContext['registrationNumber']),
            ]);
        }

        try {
            if (!$this->payments->paymentModeExists()) {
                throw new ServerErrorHttpException('eCitizen payment mode 12 is not configured in SMIS.');
            }

            $this->payments->gatewayConfig();

            $bankAccount = $this->payments->findBankAccount((int) $model->bank_account_id);
            if (!$bankAccount) {
                throw new BadRequestHttpException('The selected eCitizen settlement account was not found.');
            }
            if (empty($bankAccount['brank_id'])) {
                throw new BadRequestHttpException('The selected settlement account is not linked to a bank record.');
            }

            $paymentTypes = $this->payments->paymentTypes();
            $paymentType = null;
            foreach ($paymentTypes as $type) {
                if ((int) $type['payment_type_id'] === (int) $model->payment_type_id) {
                    $paymentType = $type;
                    break;
                }
            }
            if ($paymentType === null) {
                throw new BadRequestHttpException('The selected payment type is not available.');
            }
            $description = $paymentType['payment_desc'];
            $serviceId = $this->payments->serviceIdForPaymentType((int) $model->payment_type_id);
            $request = $this->payments->createPendingBankingSlip(
                $studentContext,
                $bankAccount,
                (float) $model->amount,
                (int) $model->payment_type_id,
                $serviceId,
                $description
            );
            $payload = $this->payments->buildGatewayPayload(
                $studentContext,
                (float) $model->amount,
                $request['reference'],
                $description,
                $serviceId,
                (string) $model->phone_number
            );

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => true,
                    'reference' => $request['reference'],
                    'html' => $this->renderPartial('_checkout_iframe', [
                        'gatewayUrl' => $this->ecitizenParams()['url'],
                        'payload' => $payload,
                        'reference' => $request['reference'],
                        'amount' => (float) $model->amount,
                        'description' => $description,
                    ]),
                ];
            }

            return $this->render('checkout', [
                'title' => $this->createPageTitle('Continue to eCitizen'),
                'gatewayUrl' => $this->ecitizenParams()['url'],
                'payload' => $payload,
                'reference' => $request['reference'],
                'amount' => (float) $model->amount,
                'description' => $description,
            ]);
        } catch (\Throwable $exception) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->statusCode = 400;
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'message' => $exception->getMessage(),
                ];
            }

            throw $exception;
        }
    }

    public function actionNotify(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $payload = Yii::$app->request->post();
        if (empty($payload)) {
            $rawPayload = json_decode(Yii::$app->request->rawBody, true);
            $payload = is_array($rawPayload) ? $rawPayload : [];
        }

        if (!$this->payments->validateNotificationHash($payload)) {
            Yii::$app->response->statusCode = 400;
            return $this->asJson(['success' => false, 'message' => 'Invalid eCitizen notification signature.']);
        }

        $notification = $this->payments->extractNotification($payload);
        if (empty($notification['reference']) || $notification['amount'] <= 0) {
            Yii::$app->response->statusCode = 400;
            return $this->asJson(['success' => false, 'message' => 'Invalid eCitizen notification payload.']);
        }

        $paidStatuses = ['', 'PAID', 'SUCCESS', 'COMPLETED', 'SETTLED'];
        if (!in_array($notification['status'], $paidStatuses, true)) {
            return $this->asJson(['success' => true, 'message' => 'Notification received but payment is not complete.']);
        }

        $queued = $this->payments->queuePaidRequestForSync(
            $notification['reference'],
            $notification['amount'],
            $notification['paymentDate'],
            $notification['gatewayReference'],
            $payload
        );

        return $this->asJson(['success' => true, 'payment_id' => $queued['payment_id'], 'sync_status' => 'queued']);
    }

    public function actionSuccess(string $reference): Response
    {
        return $this->redirect(['invoices']);
    }

    public function actionInvoices(): string
    {
        $studentContext = $this->payments->resolveLoggedInStudent();
        $invoices = $this->normalizeInvoices($this->payments->invoiceRequests($studentContext['registrationNumber']));
        $invoiceFilterModel = new DynamicModel([
            'reference',
            'deposit_date',
            'transaction_date',
            'deposit_amount',
            'settlement_status',
            'post_comment',
            'action_status',
        ]);
        $invoiceFilterModel->addRule([
            'reference',
            'deposit_date',
            'transaction_date',
            'deposit_amount',
            'settlement_status',
            'post_comment',
            'action_status',
        ], 'safe');
        $invoiceFilterModel->load(Yii::$app->request->queryParams);
        $filteredInvoices = $this->filterInvoices($invoices, $invoiceFilterModel->attributes);

        $invoiceDataProvider = new ArrayDataProvider([
            'allModels' => $filteredInvoices,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => [
                    'reference',
                    'source_reference',
                    'trans_reference',
                    'deposit_date',
                    'transaction_date',
                    'deposit_amount',
                    'settlement_status',
                    'post_comment',
                    'action_status',
                ],
                'defaultOrder' => [
                    'transaction_date' => SORT_DESC,
                ],
            ],
        ]);

        return $this->render('invoices', [
            'title' => $this->createPageTitle('My eCitizen invoices'),
            'registrationNumber' => $studentContext['registrationNumber'],
            'invoiceDataProvider' => $invoiceDataProvider,
            'invoiceFilterModel' => $invoiceFilterModel,
            'invoiceFilterOptions' => $this->invoiceFilterOptions($invoices),
        ]);
    }

    private function filterInvoices(array $invoices, array $filters): array
    {
        return array_values(array_filter($invoices, static function (array $invoice) use ($filters): bool {
            $reference = (string) $invoice['reference'];
            $date = $invoice['deposit_date'] ? date('Y-m-d', strtotime((string) $invoice['deposit_date'])) : '';
            $transactionDate = $invoice['transaction_date'] ? date('Y-m-d H:i:s', strtotime((string) $invoice['transaction_date'])) : '';
            $amount = (string) $invoice['deposit_amount'];
            $settlementStatus = (string) $invoice['settlement_status'];
            $comment = (string) $invoice['post_comment'];
            $actionStatus = (string) $invoice['action_status'];

            return self::filterMatches($filters['reference'] ?? null, $reference)
                && self::filterMatches($filters['deposit_date'] ?? null, $date)
                && self::filterMatches($filters['transaction_date'] ?? null, $transactionDate)
                && self::filterMatches($filters['deposit_amount'] ?? null, $amount)
                && self::filterMatches($filters['settlement_status'] ?? null, $settlementStatus)
                && self::filterMatches($filters['post_comment'] ?? null, $comment)
                && self::filterMatches($filters['action_status'] ?? null, $actionStatus);
        }));
    }

    private function normalizeInvoices(array $invoices): array
    {
        $normalized = array_map(static function (array $invoice): array {
            $invoice['reference'] = (string) ($invoice['source_reference'] ?: $invoice['trans_reference']);
            $invoice['transaction_date'] = $invoice['last_update'] ?? $invoice['deposit_date'] ?? null;
            $invoice['post_status'] = (string) ($invoice['post_status'] ?: 'PENDING');
            $postStatus = strtoupper((string) $invoice['post_status']);
            $isSettled = in_array($postStatus, ['NOT POSTED', 'CREDITED', 'SETTLED', 'POSTED'], true) || !empty($invoice['has_fee_payment']);
            $invoice['settlement_status'] = $isSettled ? 'Settled' : 'Not settled';
            $invoice['action_status'] = match (true) {
                $postStatus === 'POSTED' || $postStatus === 'SETTLED' => 'Posted',
                !empty($invoice['has_fee_payment']) || in_array($postStatus, ['NOT POSTED', 'CREDITED'], true) => 'Credited',
                default => 'Pending action',
            };

            return $invoice;
        }, $invoices);

        usort($normalized, static function (array $left, array $right): int {
            $leftDate = strtotime((string) ($left['transaction_date'] ?? '')) ?: 0;
            $rightDate = strtotime((string) ($right['transaction_date'] ?? '')) ?: 0;

            if ($leftDate === $rightDate) {
                return ((int) ($right['trans_id'] ?? 0)) <=> ((int) ($left['trans_id'] ?? 0));
            }

            return $rightDate <=> $leftDate;
        });

        return $normalized;
    }

    private static function filterMatches(mixed $filterValue, string $actualValue): bool
    {
        return $filterValue === null || $filterValue === '' || (string) $filterValue === $actualValue;
    }

    private function invoiceFilterOptions(array $invoices): array
    {
        $options = [
            'reference' => [],
            'deposit_date' => [],
            'transaction_date' => [],
            'deposit_amount' => [],
            'settlement_status' => [],
            'post_comment' => [],
            'action_status' => [],
        ];

        foreach ($invoices as $invoice) {
            $reference = (string) $invoice['reference'];
            $date = $invoice['deposit_date'] ? date('Y-m-d', strtotime((string) $invoice['deposit_date'])) : '';
            $transactionDate = $invoice['transaction_date'] ? date('Y-m-d H:i:s', strtotime((string) $invoice['transaction_date'])) : '';
            $amount = (string) $invoice['deposit_amount'];
            $formattedAmount = Yii::$app->formatter->asCurrency($invoice['deposit_amount']);
            $settlementStatus = (string) $invoice['settlement_status'];
            $comment = (string) $invoice['post_comment'];
            $actionStatus = (string) $invoice['action_status'];

            foreach (compact('reference', 'date', 'transactionDate', 'amount', 'settlementStatus', 'comment', 'actionStatus') as $key => $value) {
                if ($value === '') {
                    continue;
                }
                $attribute = match ($key) {
                    'date' => 'deposit_date',
                    'transactionDate' => 'transaction_date',
                    'amount' => 'deposit_amount',
                    'settlementStatus' => 'settlement_status',
                    'comment' => 'post_comment',
                    'actionStatus' => 'action_status',
                    default => $key,
                };
                $options[$attribute][$value] = $key === 'amount' ? $formattedAmount : $value;
            }
        }

        return $options;
    }

    public function actionReport(): string
    {
        return $this->render('report', [
            'title' => $this->createPageTitle('eCitizen workflow report'),
            'report' => $this->payments->workflowReport(),
        ]);
    }

    public function actionInvoice(int $trans_id): string|Response
    {
        $studentContext = $this->payments->resolveLoggedInStudent();
        $invoice = $this->payments->findInvoiceRequest($trans_id, $studentContext['registrationNumber']);
        if (!$invoice) {
            throw new \yii\web\NotFoundHttpException('The selected eCitizen invoice could not be found.');
        }

        $postStatus = strtoupper((string) $invoice['post_status']);
        if ($postStatus === 'POSTED' || $postStatus === 'SETTLED') {
            $this->setFlash('danger', 'Already posted', 'This invoice has already been posted and cannot be relaunched.');
            return $this->redirect(['invoices']);
        }

        if (!empty($invoice['has_fee_payment']) || in_array($postStatus, ['NOT POSTED', 'CREDITED'], true)) {
            $this->setFlash('info', 'Payment credited', 'This payment has already been credited to your fee statement and is queued for SMIS sync.');
            return $this->redirect(['invoices']);
        }

        $reference = (string) ($invoice['source_reference'] ?: $invoice['trans_reference']);
        $paymentTypeId = $invoice['payment_type_id'] ?? $invoice['deposit_type'] ?? null;
        if (empty($paymentTypeId) && !empty($invoice['response'])) {
            $metadata = json_decode((string) $invoice['response'], true);
            $paymentTypeId = is_array($metadata) ? ($metadata['payment_type_id'] ?? null) : null;
        }
        if (empty($paymentTypeId)) {
            throw new BadRequestHttpException('This invoice has no payment type service ID.');
        }
        $serviceId = $this->payments->serviceIdForPaymentType((int) $paymentTypeId);
        $payload = $this->payments->buildGatewayPayload(
            $studentContext,
            (float) $invoice['deposit_amount'],
            $reference,
            $invoice['post_comment'] ?: 'eCitizen student fee payment',
            $serviceId
        );

        return $this->render('checkout', [
            'title' => $this->createPageTitle('Pay eCitizen invoice'),
            'gatewayUrl' => $this->ecitizenParams()['url'],
            'payload' => $payload,
            'reference' => $reference,
            'amount' => (float) $invoice['deposit_amount'],
            'description' => $invoice['post_comment'] ?: 'eCitizen student fee payment',
        ]);
    }

    public function actionCompletePayment(int $trans_id): Response
    {
        $studentContext = $this->payments->resolveLoggedInStudent();
        $invoice = $this->payments->findInvoiceRequest($trans_id, $studentContext['registrationNumber']);
        if (!$invoice) {
            throw new \yii\web\NotFoundHttpException('The selected eCitizen invoice could not be found.');
        }

        $postStatus = strtoupper((string) $invoice['post_status']);
        if ($postStatus === 'POSTED' || $postStatus === 'SETTLED') {
            $this->setFlash('danger', 'Already posted', 'This invoice has already been posted.');
            return $this->redirect(['invoices']);
        }

        if (!empty($invoice['has_fee_payment']) || in_array($postStatus, ['NOT POSTED', 'CREDITED'], true)) {
            $this->setFlash('info', 'Payment credited', 'This payment has already been credited to your fee statement and is queued for SMIS sync.');
            return $this->redirect(['invoices']);
        }

        $reference = (string) ($invoice['source_reference'] ?: $invoice['trans_reference']);
        try {
            $statusPayload = $this->payments->queryPaymentStatus($reference);
        } catch (\Throwable $exception) {
            Yii::warning('eCitizen status query failed for invoice ' . $reference . ': ' . $exception->getMessage(), 'ecitizen.payment');
            $this->setFlash('danger', 'Verification unavailable', 'Unable to verify this invoice with eCitizen at the moment. Please try again later.');
            return $this->redirect(['invoices']);
        }

        if (!$this->payments->statusPayloadIsSettled($invoice, $statusPayload)) {
            $remoteStatus = trim((string) ($statusPayload['status'] ?? 'unknown'));
            $message = strtolower($remoteStatus) === 'pending'
                ? 'Your payment is still being processed. Please try again shortly.'
                : 'We could not confirm this payment yet. Please try again shortly.';
            $this->setFlash('danger', 'Payment not ready', $message);
            return $this->redirect(['invoices']);
        }

        try {
            $this->payments->queuePaidRequestForSync(
                $reference,
                $this->payments->paidAmount($statusPayload) ?? (float) $invoice['deposit_amount'],
                $this->payments->paymentDate($statusPayload),
                $this->payments->gatewayReference($statusPayload, $reference),
                $statusPayload
            );
        } catch (\Throwable $exception) {
            Yii::error('Unable to queue eCitizen invoice ' . $reference . ': ' . $exception->getMessage(), 'ecitizen.payment');
            $this->setFlash('danger', 'Crediting failed', 'eCitizen confirmed payment, but the fee statement credit failed. Please contact the administrator.');
            return $this->redirect(['invoices']);
        }

        $this->setFlash('success', 'Payment credited', 'eCitizen confirmed the payment and credited your fee statement. SMIS posting will complete by sync.');
        return $this->redirect(['invoices']);
    }

    private function bankAccountOptions(): array
    {
        $options = [];
        foreach ($this->payments->bankAccounts() as $account) {
            $labelParts = array_filter([
                $account['bank_name'] ?? null,
                $account['account_no'] ?? null,
                $account['account_details'] ?? null,
            ]);
            $options[$account['brank_account_id']] = implode(' - ', $labelParts);
        }
        return $options;
    }

    private function ecitizenParams(): array
    {
        return $this->module->ecitizenParams();
    }

    private function defaultPhoneNumber(array $studentContext): string
    {
        $studentPhone = (string) ($studentContext['student']['primary_phone_no'] ?? '');
        if ($studentPhone !== '') {
            return $studentPhone;
        }

        return (string) (Yii::$app->user->identity->primary_phone_no ?? '');
    }
}
