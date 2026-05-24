<?php

namespace app\controllers;

use app\models\forms\EcitizenPaymentForm;
use app\services\EcitizenPaymentService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

final class EcitizenPaymentController extends BaseController
{
    private EcitizenPaymentService $payments;

    public function init(): void
    {
        parent::init();
        $this->payments = new EcitizenPaymentService();
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
        $form = new EcitizenPaymentForm();
        $configuredBankAccountId = Yii::$app->params['ecitizen']['bankAccountId'] ?? null;
        if (!empty($configuredBankAccountId)) {
            $form->bank_account_id = (string) $configuredBankAccountId;
        }

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
        $model = new EcitizenPaymentForm();
        $studentContext = $this->payments->resolveLoggedInStudent();
        $configuredBankAccountId = Yii::$app->params['ecitizen']['bankAccountId'] ?? null;

        if (!empty($configuredBankAccountId)) {
            $model->bank_account_id = (string) $configuredBankAccountId;
        }

        if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
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
            $description = $model->narration ?: ($paymentType['payment_desc'] ?? 'eCitizen student fee payment');
            $request = $this->payments->createPendingBankingSlip(
                $studentContext,
                $bankAccount,
                (float) $model->amount,
                (int) $model->payment_type_id,
                $description
            );
            $payload = $this->payments->buildGatewayPayload(
                $studentContext,
                (float) $model->amount,
                $request['reference'],
                $description
            );

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => true,
                    'reference' => $request['reference'],
                    'html' => $this->renderPartial('_checkout_iframe', [
                        'gatewayUrl' => Yii::$app->params['ecitizen']['url'],
                        'payload' => $payload,
                        'reference' => $request['reference'],
                        'amount' => (float) $model->amount,
                        'description' => $description,
                    ]),
                ];
            }

            return $this->render('checkout', [
                'title' => $this->createPageTitle('Continue to eCitizen'),
                'gatewayUrl' => Yii::$app->params['ecitizen']['url'],
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

        $paidStatuses = ['', 'PAID', 'SUCCESS', 'COMPLETED'];
        if (!in_array($notification['status'], $paidStatuses, true)) {
            return $this->asJson(['success' => true, 'message' => 'Notification received but payment is not complete.']);
        }

        $transId = $this->payments->postPaidBankingSlip(
            $notification['reference'],
            $notification['amount'],
            $notification['paymentDate'],
            $notification['gatewayReference']
        );

        return $this->asJson(['success' => true, 'trans_id' => $transId]);
    }

    public function actionSuccess(string $reference): string
    {
        return $this->render('success', [
            'title' => $this->createPageTitle('eCitizen payment status'),
            'reference' => $reference,
        ]);
    }

    public function actionInvoices(): string
    {
        $studentContext = $this->payments->resolveLoggedInStudent();

        return $this->render('invoices', [
            'title' => $this->createPageTitle('My eCitizen invoices'),
            'registrationNumber' => $studentContext['registrationNumber'],
            'invoices' => $this->payments->invoiceRequests($studentContext['registrationNumber']),
        ]);
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

        if ($invoice['post_status'] === 'POSTED') {
            $this->setFlash('danger', 'Already posted', 'This invoice has already been posted and cannot be relaunched.');
            return $this->redirect(['invoices']);
        }

        $reference = (string) ($invoice['source_reference'] ?: $invoice['trans_reference']);
        $payload = $this->payments->buildGatewayPayload(
            $studentContext,
            (float) $invoice['deposit_amount'],
            $reference,
            $invoice['post_comment'] ?: 'eCitizen student fee payment'
        );

        return $this->render('checkout', [
            'title' => $this->createPageTitle('Pay eCitizen invoice'),
            'gatewayUrl' => Yii::$app->params['ecitizen']['url'],
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

        if ($invoice['post_status'] === 'POSTED') {
            $this->setFlash('danger', 'Already posted', 'This invoice has already been posted.');
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
            $this->payments->postPaidBankingSlip(
                $reference,
                $this->payments->paidAmount($statusPayload) ?? (float) $invoice['deposit_amount'],
                $this->payments->paymentDate($statusPayload),
                $this->payments->gatewayReference($statusPayload, $reference)
            );
        } catch (\Throwable $exception) {
            Yii::error('Unable to post eCitizen invoice ' . $reference . ': ' . $exception->getMessage(), 'ecitizen.payment');
            $this->setFlash('danger', 'Posting failed', 'eCitizen confirmed payment, but the finance posting failed. Please contact the administrator.');
            return $this->redirect(['invoices']);
        }

        $this->setFlash('success', 'Payment confirmed', 'eCitizen confirmed the payment and the finance receipt has been posted.');
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
}
