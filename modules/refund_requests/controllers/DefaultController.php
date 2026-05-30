<?php

namespace app\modules\refund_requests\controllers;

use app\controllers\BaseController;
use app\modules\refund_requests\models\Bank;
use app\modules\refund_requests\models\BankBranch;
use app\modules\refund_requests\models\ApprovalLevel;
use app\modules\refund_requests\models\RefundRequest;
use app\modules\refund_requests\models\ApprovalProcess;
use app\modules\refund_requests\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Default controller for the `refund_requests` module
 */
class DefaultController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // Always allow the dashboard (index) to load so the user can see the checklist
        if ($action->id === 'index' || $action->id === 'branches') {
            return true;
        }

        /** @var User $user */
        $user = User::findOne(Yii::$app->user->id);
        $check = $this->checkEligibility($user);

        if (!$check['eligible']) {
            $this->setFlash('danger', 'Requirement Not Met', $check['reason']);
            $this->redirect(['index'])->send();
            return false;
        }

        return true;
    }

    /**
     * Check if a student is eligible for refund request
     * @param User $user
     * @return array ['eligible' => bool, 'reason' => string|null, 'academicStatus' => string, 'balance' => float, 'cautionFeePaid' => float, 'prog_curriculum_id' => int]
     */
    private function checkEligibility(User $user): array
    {
        $regNumber = $user->registration_number;
        if (empty($regNumber)) {
            return [
                'eligible' => false,
                'reason' => 'No registration number found. Please contact the administrator.',
                'academicStatus' => 'UNKNOWN',
                'balance' => 0,
                'cautionFeePaid' => 0,
                'expectedCaution' => 0,
                'hasExistingRequest' => false,
                'prog_curriculum_id' => null
            ];
        }

        // Normalize registration number: convert dashes to slashes for DB consistency with SMIS
        $normalizedRegNo = str_replace('-', '/', $regNumber);

        // Fetch Academic Status from authoritative SMIS database
        $smisStudentData = (new \yii\db\Query())
            ->select(['s.status', 'spc.status_id', 'spc.student_prog_curriculum_id', 'spc.prog_curriculum_id'])
            ->from('smis.sm_student_programme_curriculum spc')
            ->leftJoin('smis.sm_student_status s', 'spc.status_id = s.status_id')
            ->where(['spc.registration_number' => $normalizedRegNo])
            ->one(Yii::$app->smisDb);

        $academicStatus = $smisStudentData['status'] ?? 'UNKNOWN';
        $balance = $this->calculateFeeBalance($normalizedRegNo);
        $cautionFeePaid = $this->calculateCautionFeePaid($normalizedRegNo);
        $expectedCaution = $this->calculateExpectedCautionFee($normalizedRegNo);
        
        $hasExistingRequest = false;
        if (!empty($smisStudentData['student_prog_curriculum_id'])) {
            $hasExistingRequest = RefundRequest::find()
                ->where(['student_prog_curriculum_id' => $smisStudentData['student_prog_curriculum_id']])
                ->exists();
                
            if (!$hasExistingRequest) {
                $hasExistingRequest = (new \yii\db\Query())
                    ->from('smis.fss_refund_requests')
                    ->where(['student_prog_curriculum_id' => $smisStudentData['student_prog_curriculum_id']])
                    ->exists(Yii::$app->smisDb);
            }
        }

        $eligible = true;
        $reason = null;
        $allowedStatuses = ['GRADUATED', 'COMPLETED'];

        /** @var \app\modules\refund_requests\Module $module */
        $module = $this->module;

        if (strtoupper($user->clearance_status) !== 'CLEARED') {
            $eligible = false;
            $reason = 'You must be CLEARED to access this feature. Current status: ' . ($user->clearance_status ?: 'PENDING');
        } elseif (!in_array(strtoupper($academicStatus), $allowedStatuses)) {
            $eligible = false;
            $reason = 'Refund requests are only available for GRADUATED or COMPLETED students. Your current status: ' . $academicStatus;
        } elseif (!$module->overrideCautionFee && $cautionFeePaid < $expectedCaution) {
            $eligible = false;
            $maxStr = Yii::$app->formatter->asCurrency($expectedCaution);
            $paidStr = Yii::$app->formatter->asCurrency($cautionFeePaid);
            $reason = "You have not fully paid the required CAUTION FEE. (Required: {$maxStr}, Paid: {$paidStr})";
        }

        return [
            'eligible' => $eligible,
            'reason' => $reason,
            'academicStatus' => $academicStatus,
            'balance' => $balance,
            'cautionFeePaid' => $cautionFeePaid,
            'expectedCaution' => $expectedCaution,
            'hasExistingRequest' => $hasExistingRequest,
            'smisStudentData' => $smisStudentData,
            'student_prog_curriculum_id' => $smisStudentData['student_prog_curriculum_id'] ?? null,
            'prog_curriculum_id' => $smisStudentData['prog_curriculum_id'] ?? null
        ];
    }

    /**
     * Calculate expected caution fee for a student from their transactions
     * @param string $regNumber Registration number (normalized)
     * @return float
     */
    private function calculateExpectedCautionFee(string $regNumber): float
    {
        return (float)(new \yii\db\Query())
            ->from('smis.fss_fee_transactions ft')
            ->innerJoin('smis.fss_fee_items fi', 'ft.trans_desc = fi.fee_description')
            ->where(['LIKE', 'ft.progress_code', $regNumber . '%', false])
            ->andWhere([
                'fi.fee_description' => 'CAUTION MONEY',
                'fi.fee_type' => 'OTHER',
                'fi.priority' => 1,
                'ft.trans_type' => 'DR'
            ])
            ->sum('ft.trans_amount', Yii::$app->smisDb);
    }

    /**
     * Calculate total caution fee paid by student
     * @param string $regNumber Registration number (can be with dashes or slashes)
     * @return float
     */
    private function calculateCautionFeePaid(string $regNumber): float
    {
        // Normalize registration number: convert dashes to slashes for DB consistency with SMIS
        $normalizedRegNo = str_replace('-', '/', $regNumber);

        // Use normalized registration number logic from QueryController.php
        // We use DR because in this context CAUTION MONEY is recorded as DR transactions in SMIS
        return (float)(new \yii\db\Query())
            ->from('smis.fss_fee_transactions ft')
            ->innerJoin('smis.fss_fee_items fi', 'ft.trans_desc = fi.fee_description')
            ->where(['LIKE', 'ft.progress_code', $normalizedRegNo . '%', false])
            ->andWhere([
                'fi.fee_description' => 'CAUTION MONEY',
                'fi.fee_type' => 'OTHER',
                'fi.priority' => 1,
                'ft.trans_type' => 'CR'
            ])
            ->sum('ft.trans_amount', Yii::$app->smisDb);
    }


    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        /** @var User $user */
        $user = User::findOne(Yii::$app->user->id);
        $regNumber = $user->registration_number;

        $check = $this->checkEligibility($user);
        $academicStatus = $check['academicStatus'];

        // Use expected caution fee from eligibility check
        $expectedCautionFee = $check['expectedCaution'];

        // Normalize reg number for portal update
        $normalizedRegNo = str_replace('-', '/', $regNumber);

        // Synchronize student status to portal if it differs
        if (!empty($check['smisStudentData'])) {
            $portalStatusId = (new \yii\db\Query())
                ->select(['status_id'])
                ->from('smisportal.sm_student_status')
                ->where(['status' => $academicStatus])
                ->scalar();
            
            if ($portalStatusId) {
                Yii::$app->db->createCommand()->update('smisportal.sm_student_programme_curriculum',
                    ['status_id' => $portalStatusId],
                    ['registration_number' => $regNumber] // Keep portal reg number format if it's dashes
                )->execute();
            }
        }

        $allLevels = ApprovalLevel::find()->orderBy(['approval_order' => SORT_ASC])->all();
        $refundTypes = \app\modules\refund_requests\models\RefundType::find()->where(['refund_type_status' => true])->all();
        
        $existingRequest = null;
        $smisRequest = null;
        if ($check['student_prog_curriculum_id']) {
            $existingRequest = RefundRequest::findOne(['student_prog_curriculum_id' => $check['student_prog_curriculum_id']]);
            $smisRequest = \app\modules\refund_requests\models\RefundRequestOfficial::findOne(['student_prog_curriculum_id' => $check['student_prog_curriculum_id']]);
        }

        // Mode: STATUS (Already applied)
        if ($existingRequest) {
            $approvals = ApprovalProcess::find()
                ->where(['request_id' => $existingRequest->request_id])
                ->all();

            return $this->render('index', [
                'mode' => 'status',
                'user' => $user,
                'request' => $existingRequest,
                'smisRequest' => $smisRequest,
                'approvals' => $approvals,
                'allLevels' => $allLevels,
                'academicStatus' => $academicStatus,
                'cautionFeePaid' => $check['cautionFeePaid'],
                'expectedCautionFee' => $expectedCautionFee,
                'overrideCautionFee' => $this->module->overrideCautionFee,
            ]);
        }

        return $this->render('index', [
            'mode' => $check['eligible'] ? 'eligibility' : 'not-eligible',
            'user' => $user,
            'eligible' => $check['eligible'],
            'reason' => $check['reason'],
            'balance' => $check['balance'],
            'cautionFeePaid' => $check['cautionFeePaid'],
            'expectedCautionFee' => $expectedCautionFee,
            'overrideCautionFee' => $this->module->overrideCautionFee,
            'allLevels' => $allLevels,
            'refundTypes' => $refundTypes,
            'academicStatus' => $academicStatus
        ]);
    }

    /**
     * Calculate fee balance from transactions
     * @param string $regNumber Registration number (can be with dashes or slashes)
     * @return float
     */
    private function calculateFeeBalance(string $regNumber): float
    {
        $normalizedRegNo = str_replace('-', '/', $regNumber);

        $transactions = (new \yii\db\Query())
            ->select(['trans_amount', 'trans_type'])
            ->from('smis.fss_fee_transactions')
            ->where(['LIKE', 'progress_code', $normalizedRegNo . '%', false])
            ->all(Yii::$app->smisDb);

        $credits = 0;
        $debits = 0;
        foreach ($transactions as $transaction) {
            if ($transaction['trans_type'] === 'CR') {
                $credits += $transaction['trans_amount'];
            }
            if ($transaction['trans_type'] === 'DR') {
                $debits += $transaction['trans_amount'];
            }
        }
        return $debits - $credits;
    }


    /**
     * Create a new refund request
     * @return string|Response
     */
    public function actionApply()
    {
        /** @var User $user */
        $user = User::findOne(Yii::$app->user->id);
        $regNumber = $user->registration_number;

        $check = $this->checkEligibility($user);
        if (!$check['student_prog_curriculum_id']) {
            $this->setFlash('danger', 'Error', 'Could not resolve your curriculum record. Please contact the administrator.');
            return $this->redirect(['index']);
        }

        if ($check['hasExistingRequest']) {
            $this->setFlash('info', 'Active Application Found', 'You already have a pending refund request. You can track its status from your dashboard.');
            return $this->redirect(['index']);
        }

        $typeId = $this->request->post('type', $this->request->get('type'));
        if (!$typeId && $this->request->isPost && isset($this->request->post('RefundRequest')['refund_type'])) {
            $typeId = $this->request->post('RefundRequest')['refund_type'];
        }

        $refundType = \app\modules\refund_requests\models\RefundType::findOne($typeId);
        $passedAmount = $this->request->post('amount', $this->request->get('amount'));

        $refundableAmount = 0;
        // Validation for CAUTION refund type
        if ($refundType && strtoupper($refundType->refund_type_name) === 'CAUTION') {
            /** @var \app\modules\refund_requests\Module $module */
            $module = $this->module;
            if ($check['cautionFeePaid'] < $check['expectedCaution'] && !$module->overrideCautionFee) {
                $this->setFlash('danger', 'Requirement Not Met', 'You have not fully paid the CAUTION FEE required for this refund type.');
                return $this->redirect(['index']);
            }
            
            $refundableAmount = ($check['cautionFeePaid'] >= $check['expectedCaution']) ? $check['cautionFeePaid'] : ($module->overrideCautionFee ? $check['expectedCaution'] : 0);
            if ($refundableAmount <= 0) {
                $this->setFlash('danger', 'Requirement Not Met', 'The calculated Caution Refund amount must be greater than zero.');
                return $this->redirect(['index']);
            }
        } else {
            // For non-caution types, we could potentially limit by fee balance if it's a credit balance
            $refundableAmount = ($check['balance'] < 0) ? abs((float)$check['balance']) : 0;
        }

        $model = new RefundRequest();
        $model->max_amount = (float)$refundableAmount;

        if ($typeId) {
            $model->refund_type = $typeId;

            // Use amount passed from the first screen if available
            if ($passedAmount !== null) {
                $model->amount_requested = (float)$passedAmount;
            } elseif ($refundType && strtoupper($refundType->refund_type_name) === 'CAUTION') {
                // Fallback autofill logic for CAUTION type if not passed from index
                $amount = $check['cautionFeePaid'];
                if ($amount <= 0 && $this->module->overrideCautionFee) {
                    $amount = $check['expectedCaution'];
                }
                $model->amount_requested = $amount;
            }
        } else {
            // Default to STANDARD if no type is specified
            $standardType = \app\modules\refund_requests\models\RefundType::findOne(['refund_type_name' => 'STANDARD']);
            if ($standardType) {
                $model->refund_type = $standardType->refund_type_id;
            }
        }

        $model->student_prog_curriculum_id = $check['student_prog_curriculum_id'];
        $model->application_date = date('Y-m-d H:i:s');
        $model->refund_status = 'NOT REFUNDED';
        $model->approval_status = 'PENDING';
        $model->declaration_status = '0';
        $model->email = $user->primary_email;
        $model->passport_id = $user->passport_no ?: ($user->national_id ?: 'N/A');
        $model->account_name = $user->surname . ' ' . $user->other_names;
        $model->mobile_no = $user->primary_phone_no ?: '0000000000';
        $model->payment_method = 'bank';

        if ($this->request->isPost && $this->request->post('RefundRequest')) {
            $post = $this->request->post();

            if ($model->load($post)) {
                // Generate a request_id if not identity
                $maxId = RefundRequest::find()->max('request_id');
                $model->request_id = ($maxId ?: 0) + 1;

                if ($model->save()) {
                    $this->setFlash('success', 'Refund Request', 'Your application has been submitted successfully and will be synchronized soon.');
                    return $this->redirect(['index']);
                } else {
                    $errors = implode('<br>', \yii\helpers\ArrayHelper::getColumn($model->getErrors(), 0));
                    $this->setFlash('danger', 'Error', 'Failed to save your application: ' . $errors);
                }
            }
        }

        $banks = Bank::find()->all();
        $refundTypes = \app\modules\refund_requests\models\RefundType::find()->where(['refund_type_status' => true])->all();

        return $this->render('apply', [
            'model' => $model,
            'banks' => $banks,
            'refundTypes' => $refundTypes,
            'regNumber' => $regNumber
        ]);
    }

    /**
     * Get branches for a specific bank (AJAX)
     * @param int $bankId
     * @return array
     */
    public function actionBranches($bankId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $bank = Bank::findOne($bankId);
        if ($bank) {
            return BankBranch::find()->where(['bank_code' => $bank->bank_code])->all();
        }
        return [];
    }

    /**
     * Dedicated interface to track the whole process
     * @return string
     */
    public function actionTrack()
    {
        /** @var User $user */
        $user = User::findOne(Yii::$app->user->id);
        $regNumber = $user->registration_number;
        $check = $this->checkEligibility($user);

        $request = null;
        $smisRequest = null;
        $approvals = [];
        if ($check['student_prog_curriculum_id']) {
            $request = RefundRequest::findOne(['student_prog_curriculum_id' => $check['student_prog_curriculum_id']]);
            $smisRequest = \app\modules\refund_requests\models\RefundRequestOfficial::findOne(['student_prog_curriculum_id' => $check['student_prog_curriculum_id']]);
            if ($request) {
                $approvals = ApprovalProcess::find()
                    ->where(['request_id' => $request->request_id])
                    ->all();
            }
        }

        $allLevels = ApprovalLevel::find()->orderBy(['approval_order' => SORT_ASC])->all();
        $balance = $regNumber ? $this->calculateFeeBalance($regNumber) : 0;

        return $this->render('track', [
            'user' => $user,
            'request' => $request,
            'smisRequest' => $smisRequest,
            'approvals' => $approvals,
            'allLevels' => $allLevels,
            'balance' => $balance,
            'cautionFeePaid' => $check['cautionFeePaid'],
            'expectedCautionFee' => $check['expectedCaution'],
            'overrideCautionFee' => $this->module->overrideCautionFee,
            'eligible' => $check['eligible'],
            'reason' => $check['reason'],
        ]);
    }
}
