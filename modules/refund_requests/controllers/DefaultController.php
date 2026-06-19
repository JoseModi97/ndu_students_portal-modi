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
                'cautionReservedAmount' => 0,
                'cautionRemainingAmount' => 0,
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
        $studentProgCurriculumId = !empty($smisStudentData['student_prog_curriculum_id'])
            ? (int)$smisStudentData['student_prog_curriculum_id']
            : null;
        $balance = $this->calculateFeeBalance($normalizedRegNo);
        $cautionFeePaid = $this->calculateCautionFeePaid($normalizedRegNo, $studentProgCurriculumId);
        $expectedCaution = $this->calculateExpectedCautionFee($normalizedRegNo, $studentProgCurriculumId);
        $cautionTypeId = $this->refundTypeId('CAUTION');
        $cautionReservedAmount = ($studentProgCurriculumId !== null && $cautionTypeId !== null)
            ? $this->reservedRefundAmount($studentProgCurriculumId, $cautionTypeId)
            : 0;
        $cautionBaseAmount = ($cautionFeePaid >= $expectedCaution) ? $cautionFeePaid : ($this->module->overrideEligibility ? $expectedCaution : 0);
        $cautionRemainingAmount = max(0, $cautionBaseAmount - $cautionReservedAmount);
        
        $hasExistingRequest = false;
        if (!empty($smisStudentData['student_prog_curriculum_id'])) {
            $hasExistingRequest = RefundRequest::find()
                ->where(['student_prog_curriculum_id' => $smisStudentData['student_prog_curriculum_id']])
                ->andWhere('UPPER(approval_status) NOT IN (:approved, :notApproved)', [
                    ':approved' => 'APPROVED',
                    ':notApproved' => 'NOT APPROVED',
                ])
                ->exists();
                
            if (!$hasExistingRequest) {
                $hasExistingRequest = (new \yii\db\Query())
                    ->from('smis.fss_refund_requests')
                    ->where(['student_prog_curriculum_id' => $smisStudentData['student_prog_curriculum_id']])
                    ->andWhere('UPPER(approval_status) NOT IN (:approved, :notApproved)', [
                        ':approved' => 'APPROVED',
                        ':notApproved' => 'NOT APPROVED',
                    ])
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
        } elseif (!$module->overrideEligibility) {
            if ($balance > 0) {
                $eligible = false;
                $balStr = Yii::$app->formatter->asCurrency($balance);
                $reason = "You have an outstanding fee balance of {$balStr}. All balances must be cleared to apply.";
            }
        }

        return [
            'eligible' => $eligible,
            'reason' => $reason,
            'academicStatus' => $academicStatus,
            'balance' => $balance,
            'cautionFeePaid' => $cautionFeePaid,
            'expectedCaution' => $expectedCaution,
            'hasExistingRequest' => $hasExistingRequest,
            'cautionReservedAmount' => $cautionReservedAmount,
            'cautionRemainingAmount' => $cautionRemainingAmount,
            'smisStudentData' => $smisStudentData,
            'student_prog_curriculum_id' => $smisStudentData['student_prog_curriculum_id'] ?? null,
            'prog_curriculum_id' => $smisStudentData['prog_curriculum_id'] ?? null
        ];
    }

    private function refundTypeId(string $refundTypeName): ?int
    {
        $value = \app\modules\refund_requests\models\RefundType::find()
            ->select('refund_type_id')
            ->where('UPPER(refund_type_name) = :refund_type_name', [':refund_type_name' => strtoupper($refundTypeName)])
            ->scalar();

        return $value === false || $value === null ? null : (int)$value;
    }

    private function reservedRefundAmount(int $studentProgCurriculumId, int $refundTypeId): float
    {
        $rows = [];
        $sources = [
            [Yii::$app->db, 'smisportal.fss_refund_requests'],
            [Yii::$app->smisDb, 'smis.fss_refund_requests'],
        ];

        foreach ($sources as [$db, $table]) {
            $sourceRows = (new \yii\db\Query())
                ->select(['request_id', 'approval_status', 'amount_requested', 'amount_approved'])
                ->from($table)
                ->where([
                    'student_prog_curriculum_id' => $studentProgCurriculumId,
                    'refund_type' => $refundTypeId,
                ])
                ->andWhere('UPPER(approval_status) <> :notApproved', [':notApproved' => 'NOT APPROVED'])
                ->all($db);

            foreach ($sourceRows as $row) {
                $requestId = (string)$row['request_id'];
                $amount = strtoupper((string)$row['approval_status']) === 'APPROVED' && (float)$row['amount_approved'] > 0
                    ? (float)$row['amount_approved']
                    : (float)$row['amount_requested'];

                $rows[$requestId] = max($rows[$requestId] ?? 0, $amount);
            }
        }

        return array_sum($rows);
    }

    private function refundedRequestsByType(int $studentProgCurriculumId): array
    {
        $smisRequests = \app\modules\refund_requests\models\RefundRequestOfficial::find()
            ->where(['student_prog_curriculum_id' => $studentProgCurriculumId])
            ->andWhere('UPPER(refund_status) = :refunded', [':refunded' => 'REFUNDED'])
            ->orderBy(['application_date' => SORT_DESC, 'request_id' => SORT_DESC])
            ->all();

        if (!$smisRequests) {
            return [];
        }

        $refundTypes = \app\modules\refund_requests\models\RefundType::find()
            ->indexBy('refund_type_id')
            ->all();
        $refundedRequests = [];

        foreach ($smisRequests as $smisRequest) {
            $refundTypeId = (int)$smisRequest->refund_type;
            if (isset($refundedRequests[$refundTypeId])) {
                continue;
            }

            $portalRequest = RefundRequest::find()
                ->where(['request_id' => $smisRequest->request_id])
                ->with(['refundType', 'bank'])
                ->one();
            $refundType = $portalRequest ? ($portalRequest->refundType ?? null) : null;
            $refundType = $refundType ?? ($refundTypes[$refundTypeId] ?? null);
            $paymentMethod = strtoupper((string)($portalRequest ? $portalRequest->payment_method : ''));
            $paymentLabel = $paymentMethod === 'MPESA'
                ? 'M-PESA'
                : ($paymentMethod === 'BANK' ? 'Bank Transfer' : 'Payment Method');
            $paymentDetail = '';

            if ($paymentMethod === 'MPESA') {
                $paymentDetail = 'Mobile: ' . (($portalRequest ? $portalRequest->mobile_no : null) ?: $smisRequest->mobile_no);
            } elseif ($paymentMethod === 'BANK') {
                $bankName = ($portalRequest && $portalRequest->bank) ? $portalRequest->bank->bank_name : 'Bank account';
                $paymentDetail = $bankName . ' (Acc: ' . (($portalRequest ? $portalRequest->account_no : null) ?: $smisRequest->account_no) . ')';
            }

            $refundedRequests[$refundTypeId] = [
                'requestId' => (int)$smisRequest->request_id,
                'referenceNo' => '#REF-' . str_pad((string)$smisRequest->request_id, 5, '0', STR_PAD_LEFT),
                'refundType' => $refundType->displayName ?? $refundType->refund_type_name ?? 'Refund',
                'amount' => (float)($smisRequest->amount_approved ?: $smisRequest->amount_requested),
                'voucherNo' => $smisRequest->voucher_no,
                'paymentLabel' => $paymentLabel,
                'paymentDetail' => $paymentDetail,
            ];
        }

        return $refundedRequests;
    }

    private function hasActiveNonRefundedRequest(int $studentProgCurriculumId, array $refundedRequests): bool
    {
        $refundedRequestIds = array_values(array_filter(array_map(
            static fn(array $request): int => (int)($request['requestId'] ?? 0),
            $refundedRequests
        )));

        $sources = [
            [Yii::$app->db, 'smisportal.fss_refund_requests'],
            [Yii::$app->smisDb, 'smis.fss_refund_requests'],
        ];

        foreach ($sources as [$db, $table]) {
            $query = (new \yii\db\Query())
                ->from($table)
                ->where(['student_prog_curriculum_id' => $studentProgCurriculumId])
                ->andWhere('UPPER(approval_status) <> :notApproved', [':notApproved' => 'NOT APPROVED'])
                ->andWhere('UPPER(COALESCE(refund_status, \'\')) <> :refunded', [':refunded' => 'REFUNDED']);

            if ($refundedRequestIds) {
                $query->andWhere(['not in', 'request_id', $refundedRequestIds]);
            }

            if ($query->exists($db)) {
                return true;
            }
        }

        return false;
    }

    private function activeRequestsByType(int $studentProgCurriculumId, array $refundedRequests): array
    {
        $refundedRequestIds = array_flip(array_map(
            static fn(array $request): int => (int)($request['requestId'] ?? 0),
            $refundedRequests
        ));
        $requests = RefundRequest::find()
            ->where(['student_prog_curriculum_id' => $studentProgCurriculumId])
            ->andWhere('UPPER(approval_status) <> :notApproved', [':notApproved' => 'NOT APPROVED'])
            ->andWhere('UPPER(COALESCE(refund_status, \'\')) <> :refunded', [':refunded' => 'REFUNDED'])
            ->with(['refundType', 'bank'])
            ->orderBy(['application_date' => SORT_DESC, 'request_id' => SORT_DESC])
            ->all();
        $smisRequests = \app\modules\refund_requests\models\RefundRequestOfficial::find()
            ->where(['student_prog_curriculum_id' => $studentProgCurriculumId])
            ->andWhere('UPPER(approval_status) <> :notApproved', [':notApproved' => 'NOT APPROVED'])
            ->andWhere('UPPER(COALESCE(refund_status, \'\')) <> :refunded', [':refunded' => 'REFUNDED'])
            ->orderBy(['application_date' => SORT_DESC, 'request_id' => SORT_DESC])
            ->all();
        $refundTypes = \app\modules\refund_requests\models\RefundType::find()
            ->indexBy('refund_type_id')
            ->all();

        $allLevels = ApprovalLevel::find()->orderBy(['approval_order' => SORT_ASC])->all();
        $totalLevels = count($allLevels);
        $activeRequests = [];

        foreach ($requests as $request) {
            if (isset($refundedRequestIds[(int)$request->request_id])) {
                continue;
            }

            $refundTypeId = (int)$request->refund_type;
            if (isset($activeRequests[$refundTypeId])) {
                continue;
            }

            $approvals = ApprovalProcess::find()
                ->where(['request_id' => $request->request_id])
                ->andWhere('approval_date >= :application_date', [':application_date' => $request->application_date])
                ->all();
            $approvedLevelIds = [];
            foreach ($approvals as $approval) {
                if (!$approval->approver || strtoupper((string)$approval->approval_status) !== 'APPROVED') {
                    continue;
                }
                $approvedLevelIds[(int)$approval->approver->approval_level_id] = true;
            }

            $isWorkflowApproved = $totalLevels > 0;
            foreach ($allLevels as $level) {
                if (!isset($approvedLevelIds[(int)$level->approval_level_id])) {
                    $isWorkflowApproved = false;
                    break;
                }
            }

            $status = strtoupper((string)$request->approval_status);
            $statusLabel = $isWorkflowApproved || $status === 'APPROVED' ? 'APPROVED' : $status;
            $paymentMethod = strtoupper((string)$request->payment_method);
            $paymentLabel = $paymentMethod === 'MPESA'
                ? 'M-PESA'
                : ($paymentMethod === 'BANK' ? 'Bank Transfer' : 'Payment Method');
            $paymentDetail = '';
            if ($paymentMethod === 'MPESA') {
                $paymentDetail = 'Mobile: ' . $request->mobile_no;
            } elseif ($paymentMethod === 'BANK') {
                $paymentDetail = ($request->bank->bank_name ?? 'Bank account') . ' (Acc: ' . $request->account_no . ')';
            }

            $activeRequests[$refundTypeId] = [
                'requestId' => (int)$request->request_id,
                'referenceNo' => '#REF-' . str_pad((string)$request->request_id, 5, '0', STR_PAD_LEFT),
                'refundType' => $request->refundType->displayName ?? $request->refundType->refund_type_name ?? 'Refund',
                'amount' => (float)($request->amount_approved ?: $request->amount_requested),
                'amountLabel' => isset($request->refundType) && strtoupper((string)$request->refundType->refund_type_name) === 'CAUTION'
                    ? 'Caution Amount'
                    : 'Requested Amount',
                'applicationDate' => $request->application_date,
                'statusLabel' => $statusLabel,
                'voucherNo' => $request->voucher_no,
                'paymentLabel' => $paymentLabel,
                'paymentDetail' => $paymentDetail,
            ];
        }

        foreach ($smisRequests as $smisRequest) {
            if (isset($refundedRequestIds[(int)$smisRequest->request_id])) {
                continue;
            }

            $refundTypeId = (int)$smisRequest->refund_type;
            $portalRequest = null;
            foreach ($requests as $request) {
                if ((int)$request->request_id === (int)$smisRequest->request_id) {
                    $portalRequest = $request;
                    break;
                }
            }

            $existing = $activeRequests[$refundTypeId] ?? null;
            if ($existing !== null && (int)$existing['requestId'] !== (int)$smisRequest->request_id) {
                $existingTime = strtotime((string)($existing['applicationDate'] ?? '')) ?: 0;
                $smisTime = strtotime((string)$smisRequest->application_date) ?: 0;
                if ($existingTime > $smisTime) {
                    continue;
                }
            }

            $refundType = $portalRequest ? ($portalRequest->refundType ?? null) : null;
            $refundType = $refundType ?? ($refundTypes[$refundTypeId] ?? null);
            $paymentMethod = strtoupper((string)($portalRequest ? $portalRequest->payment_method : ''));
            $paymentLabel = $paymentMethod === 'MPESA'
                ? 'M-PESA'
                : ($paymentMethod === 'BANK' ? 'Bank Transfer' : 'Payment Method');
            $paymentDetail = '';

            if ($paymentMethod === 'MPESA') {
                $paymentDetail = 'Mobile: ' . (($portalRequest ? $portalRequest->mobile_no : null) ?: $smisRequest->mobile_no);
            } elseif ($paymentMethod === 'BANK') {
                $bank = ($portalRequest && $portalRequest->bank) ? $portalRequest->bank : null;
                if ($bank === null && $smisRequest->bank_id) {
                    $bank = Bank::findOne((int)$smisRequest->bank_id);
                }
                $bankName = $bank ? $bank->bank_name : 'Bank account';
                $paymentDetail = $bankName . ' (Acc: ' . (($portalRequest ? $portalRequest->account_no : null) ?: $smisRequest->account_no) . ')';
            }

            $activeRequests[$refundTypeId] = [
                'requestId' => (int)$smisRequest->request_id,
                'referenceNo' => '#REF-' . str_pad((string)$smisRequest->request_id, 5, '0', STR_PAD_LEFT),
                'refundType' => $refundType->displayName ?? $refundType->refund_type_name ?? 'Refund',
                'amount' => (float)($smisRequest->amount_approved ?: $smisRequest->amount_requested),
                'amountLabel' => $refundType && strtoupper((string)$refundType->refund_type_name) === 'CAUTION'
                    ? 'Caution Amount'
                    : 'Requested Amount',
                'applicationDate' => $smisRequest->application_date,
                'statusLabel' => strtoupper((string)$smisRequest->approval_status),
                'voucherNo' => $smisRequest->voucher_no,
                'paymentLabel' => $paymentLabel,
                'paymentDetail' => $paymentDetail,
            ];
        }

        return $activeRequests;
    }

    /**
     * Calculate expected caution fee for a student from their transactions
     * @param string $regNumber Registration number (normalized)
     * @return float
     */
    private function calculateExpectedCautionFee(string $regNumber, ?int $studentProgCurriculumId = null): float
    {
        return $this->sumCautionTransactions($regNumber, 'DR', $studentProgCurriculumId);
    }

    /**
     * Calculate total caution fee paid by student
     * @param string $regNumber Registration number (can be with dashes or slashes)
     * @return float
     */
    private function calculateCautionFeePaid(string $regNumber, ?int $studentProgCurriculumId = null): float
    {
        return $this->sumCautionTransactions($regNumber, 'CR', $studentProgCurriculumId);
    }

    private function sumCautionTransactions(string $regNumber, string $transactionType, ?int $studentProgCurriculumId = null): float
    {
        $studentFilter = ['or'];
        $academicProgressIds = $studentProgCurriculumId !== null
            ? $this->academicProgressIds($studentProgCurriculumId)
            : [];

        if (!empty($academicProgressIds)) {
            $studentFilter[] = ['ft.academic_progress_id' => $academicProgressIds];
        }

        foreach ($this->registrationNumberVariants($regNumber) as $variant) {
            $studentFilter[] = ['LIKE', 'ft.progress_code', $variant . '%', false];
        }

        if (count($studentFilter) === 1) {
            return 0.0;
        }

        $sum = (new \yii\db\Query())
            ->from('smis.fss_fee_transactions ft')
            ->where(['ft.trans_type' => $transactionType])
            ->andWhere($studentFilter)
            ->andWhere(new \yii\db\Expression('TRIM(ft.trans_desc) = :cautionDescription'))
            ->addParams([':cautionDescription' => 'CAUTION MONEY'])
            ->sum('ft.trans_amount', Yii::$app->smisDb);

        return (float)$sum;
    }

    private function academicProgressIds(int $studentProgCurriculumId): array
    {
        return array_map('intval', (new \yii\db\Query())
            ->select('academic_progress_id')
            ->from('smis.sm_academic_progress')
            ->where(['student_prog_curriculum_id' => $studentProgCurriculumId])
            ->column(Yii::$app->smisDb));
    }

    private function registrationNumberVariants(string $regNumber): array
    {
        $regNumber = trim($regNumber);

        return array_values(array_unique(array_filter([
            $regNumber,
            str_replace('-', '/', $regNumber),
            str_replace('/', '-', $regNumber),
        ])));
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
        $previousRequests = [];
        $refundedRequests = [];
        $activeRequests = [];
        if ($check['student_prog_curriculum_id']) {
            $existingRequest = RefundRequest::find()
                ->where(['student_prog_curriculum_id' => $check['student_prog_curriculum_id']])
                ->andWhere('UPPER(approval_status) NOT IN (:approved, :notApproved)', [
                    ':approved' => 'APPROVED',
                    ':notApproved' => 'NOT APPROVED',
                ])
                ->orderBy(['application_date' => SORT_DESC, 'request_id' => SORT_DESC])
                ->one();
            $smisRequest = \app\modules\refund_requests\models\RefundRequestOfficial::find()
                ->where(['student_prog_curriculum_id' => $check['student_prog_curriculum_id']])
                ->andWhere('UPPER(approval_status) NOT IN (:approved, :notApproved)', [
                    ':approved' => 'APPROVED',
                    ':notApproved' => 'NOT APPROVED',
                ])
                ->orderBy(['application_date' => SORT_DESC, 'request_id' => SORT_DESC])
                ->one();
            $previousRequests = RefundRequest::find()
                ->where(['student_prog_curriculum_id' => $check['student_prog_curriculum_id']])
                ->andWhere('UPPER(approval_status) = :notApproved', [':notApproved' => 'NOT APPROVED'])
                ->with([
                    'refundType',
                    'approvalProcesses' => function ($query) {
                        $query->andWhere('UPPER(approval_status) = :notApproved', [':notApproved' => 'NOT APPROVED'])
                            ->orderBy(['approval_date' => SORT_DESC, 'approval_process_id' => SORT_DESC]);
                    },
                ])
                ->orderBy(['application_date' => SORT_DESC, 'request_id' => SORT_DESC])
                ->all();
            $refundedRequests = $this->refundedRequestsByType((int)$check['student_prog_curriculum_id']);

            $refundedRequestIds = array_flip(array_map(
                static fn(array $request): int => (int)($request['requestId'] ?? 0),
                $refundedRequests
            ));

            if ($existingRequest && isset($refundedRequestIds[(int)$existingRequest->request_id])) {
                $existingRequest = null;
            }
            $activeRequests = $this->activeRequestsByType((int)$check['student_prog_curriculum_id'], $refundedRequests);
        }

        $hasExternalActiveRequest = $check['hasExistingRequest']
            && !$activeRequests
            && !empty($check['student_prog_curriculum_id'])
            && $this->hasActiveNonRefundedRequest((int)$check['student_prog_curriculum_id'], $refundedRequests);

        return $this->render('index', [
            'mode' => $hasExternalActiveRequest ? 'not-eligible' : 'eligibility',
            'user' => $user,
            'eligible' => $check['eligible'] && !$hasExternalActiveRequest,
            'reason' => $hasExternalActiveRequest
                ? 'You can make another refund request only after the current request has been approved through all approval levels or not approved.'
                : $check['reason'],
            'balance' => $check['balance'],
            'cautionFeePaid' => $check['cautionFeePaid'],
            'expectedCautionFee' => $expectedCautionFee,
            'cautionReservedAmount' => $check['cautionReservedAmount'],
            'cautionRemainingAmount' => $check['cautionRemainingAmount'],
            'overrideEligibility' => $this->module->overrideEligibility,
            'allLevels' => $allLevels,
            'refundTypes' => $refundTypes,
            'academicStatus' => $academicStatus,
            'previousRequests' => $previousRequests,
            'refundedRequests' => $refundedRequests,
            'activeRequests' => $activeRequests,
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

        $rejectedRequestId = (int)$this->request->post('rejected_request_id', $this->request->get('rejected_request_id', 0));
        $rejectedRequest = null;
        $latestRejection = null;
        $isPostingRejection = false;
        if ($rejectedRequestId > 0) {
            $rejectedRequest = RefundRequest::find()
                ->where([
                    'request_id' => $rejectedRequestId,
                    'student_prog_curriculum_id' => $check['student_prog_curriculum_id'],
                ])
                ->andWhere('UPPER(approval_status) = :notApproved', [':notApproved' => 'NOT APPROVED'])
                ->with('refundType')
                ->one();

            if ($rejectedRequest === null) {
                $this->setFlash('warning', 'Refund Request', 'The rejected refund request was not found.');
                return $this->redirect(['index']);
            }

            $latestRejection = ApprovalProcess::find()
                ->where(['request_id' => $rejectedRequest->request_id])
                ->andWhere('UPPER(approval_status) = :notApproved', [':notApproved' => 'NOT APPROVED'])
                ->orderBy(['approval_date' => SORT_DESC, 'approval_process_id' => SORT_DESC])
                ->one();
            $isPostingRejection = $this->isPostingWindowDisapproval((int)$rejectedRequest->request_id);
        }

        $typeId = $this->request->post('type', $this->request->get('type'));
        if (!$typeId && $this->request->isPost && isset($this->request->post('RefundRequest')['refund_type'])) {
            $typeId = $this->request->post('RefundRequest')['refund_type'];
        }
        if (!$typeId && $rejectedRequest !== null) {
            $typeId = $rejectedRequest->refund_type;
        }

        if (!$typeId) {
            $this->setFlash('info', 'Refund Request', 'Please start your refund application from the requirements page.');
            return $this->redirect(['index']);
        }

        $refundType = \app\modules\refund_requests\models\RefundType::findOne($typeId);
        if (!$refundType) {
            $this->setFlash('danger', 'Refund Request', 'The selected refund type was not found. Please start again.');
            return $this->redirect(['index']);
        }

        $refundedRequests = $this->refundedRequestsByType((int)$check['student_prog_curriculum_id']);
        $refundedRequestDetails = $refundedRequests[(int)$typeId] ?? null;
        $activeRequests = $this->activeRequestsByType((int)$check['student_prog_curriculum_id'], $refundedRequests);
        $activeRequestDetails = $activeRequests[(int)$typeId] ?? null;

        $isReadOnlyExistingRequest = $refundedRequestDetails !== null || $activeRequestDetails !== null;

        if ($check['hasExistingRequest'] && !$isReadOnlyExistingRequest) {
            $this->setFlash('info', 'Active Application Found', 'You can make another refund request only after the current request has been approved through all approval levels or not approved.');
            return $this->redirect(['index']);
        }

        $passedAmount = $this->request->post('amount', $this->request->get('amount'));

        $refundableAmount = 0;
        if ($refundedRequestDetails !== null) {
            $refundableAmount = (float)$refundedRequestDetails['amount'];
        } elseif ($activeRequestDetails !== null) {
            $refundableAmount = (float)$activeRequestDetails['amount'];
        // Validation for CAUTION refund type
        } elseif ($refundType && strtoupper($refundType->refund_type_name) === 'CAUTION') {
            /** @var \app\modules\refund_requests\Module $module */
            $module = $this->module;
            if ($check['cautionFeePaid'] < $check['expectedCaution'] && !$module->overrideEligibility) {
                $this->setFlash('danger', 'Requirement Not Met', 'You have not fully paid the CAUTION FEE required for this refund type.');
                return $this->redirect(['index']);
            }

            $refundableAmount = (float)$check['cautionRemainingAmount'];
            if ($refundableAmount <= 0) {
                $this->setFlash('danger', 'Requirement Not Met', 'Your available Caution Refund balance has already been requested or approved.');
                return $this->redirect(['index']);
            }
        } else {
            // For non-caution types, we could potentially limit by fee balance if it's a credit balance
            $reservedAmount = $typeId ? $this->reservedRefundAmount((int)$check['student_prog_curriculum_id'], (int)$typeId) : 0;
            $refundableAmount = max(0, (($check['balance'] < 0) ? abs((float)$check['balance']) : 0) - $reservedAmount);
            if ($refundableAmount <= 0 && $check['hasExistingRequest']) {
                $this->setFlash('info', 'Active Application Found', 'You already have a pending or approved refund request using the available balance.');
                return $this->redirect(['index']);
            }
        }

        $model = $rejectedRequest ?: new RefundRequest();
        $model->max_amount = (float)$refundableAmount;

        if ($typeId) {
            $model->refund_type = $typeId;

            // Use amount passed from the first screen if available
            if ($passedAmount !== null) {
                $model->amount_requested = (float)$passedAmount;
            } elseif ($refundType && strtoupper($refundType->refund_type_name) === 'CAUTION') {
                // Fallback autofill logic for CAUTION type if not passed from index
                $model->amount_requested = $refundableAmount;
            }
        } else {
            // Default to STANDARD if no type is specified
            $standardType = \app\modules\refund_requests\models\RefundType::findOne(['refund_type_name' => 'STANDARD']);
            if ($standardType) {
                $model->refund_type = $standardType->refund_type_id;
            }
        }

        if ($refundedRequestDetails !== null) {
            $model->max_amount = (float)$refundedRequestDetails['amount'];
            $model->amount_requested = (float)$refundedRequestDetails['amount'];
        } elseif ($activeRequestDetails !== null) {
            $model->max_amount = (float)$activeRequestDetails['amount'];
            $model->amount_requested = (float)$activeRequestDetails['amount'];
        }

        if ($rejectedRequest === null) {
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
        } else {
            $model->max_amount = max((float)$refundableAmount, (float)$model->amount_requested);
        }

        if ($this->request->isPost && $this->request->post('RefundRequest')) {
            $post = $this->request->post();

            if ($model->load($post)) {
                $model->payment_method = $post['RefundRequest']['payment_method'] ?? 'bank';
                $model->amount_requested = (float)$refundableAmount;
                $model->voucher_no = null;

                if ($rejectedRequest === null) {
                    // Generate a request_id if not identity
                    $maxId = RefundRequest::find()->max('request_id');
                    $model->request_id = ($maxId ?: 0) + 1;
                } else {
                    $model->application_date = date('Y-m-d H:i:s');
                    $model->approval_status = 'PENDING';
                    $model->refund_status = 'NOT REFUNDED';
                    if (!$isPostingRejection) {
                        $model->amount_approved = null;
                    }
                    $model->sync_error = null;
                }

                if ($model->save()) {
                    if ($rejectedRequest !== null) {
                        $this->markDisapprovedRequestActioned((int)$rejectedRequest->request_id);
                    }

                    $message = $rejectedRequest !== null
                        ? ($isPostingRejection
                            ? 'Your updated application has been submitted and will be returned to posting.'
                            : 'Your updated application has been submitted and will restart approval from Level 1.')
                        : 'Your application has been submitted successfully and will be synchronized soon.';
                    $this->setFlash('success', 'Refund Request', $message);
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
            'regNumber' => $regNumber,
            'rejectedRequest' => $rejectedRequest,
            'latestRejection' => $latestRejection,
            'isPostingRejection' => $isPostingRejection,
            'refundedRequestDetails' => $refundedRequestDetails,
            'activeRequestDetails' => $activeRequestDetails,
        ]);
    }

    private function markDisapprovedRequestActioned(int $requestId): void
    {
        foreach ([[Yii::$app->db, 'smisportal'], [Yii::$app->smisDb, 'smis']] as [$db, $schema]) {
            if ($db->getTableSchema($schema . '.fss_refund_requests_disapproved', true) === null) {
                continue;
            }

            $db->createCommand()
                ->update(
                    $schema . '.fss_refund_requests_disapproved',
                    [
                        'action_flag' => true,
                        'date_reinstated' => date('Y-m-d H:i:s'),
                        'reinstatement_remarks' => 'Student updated rejected refund request.',
                        'reinstated_by' => Yii::$app->user->id,
                    ],
                    ['request_id' => $requestId]
                )
                ->execute();
        }
    }

    private function isPostingWindowDisapproval(int $requestId): bool
    {
        foreach ([[Yii::$app->db, 'smisportal'], [Yii::$app->smisDb, 'smis']] as [$db, $schema]) {
            $table = $db->getTableSchema($schema . '.fss_refund_requests_disapproved', true);
            if ($table === null) {
                continue;
            }

            $originCondition = in_array('rejection_origin', $table->columnNames, true)
                ? "(UPPER(COALESCE(d.rejection_origin, '')) = 'POSTING_WINDOW' OR UPPER(COALESCE(d.remarks, '')) = 'CANCELLED AT POSTING WINDOW')"
                : "UPPER(COALESCE(d.remarks, '')) = 'CANCELLED AT POSTING WINDOW'";

            $latestRowCondition = in_array('disapproved_refund_id', $table->columnNames, true)
                ? "NOT EXISTS (
                    SELECT 1
                    FROM {$schema}.fss_refund_requests_disapproved newer
                    WHERE newer.request_id = d.request_id
                      AND (
                          newer.approval_date > d.approval_date
                          OR (
                              newer.approval_date = d.approval_date
                              AND newer.disapproved_refund_id > d.disapproved_refund_id
                          )
                      )
                )"
                : "NOT EXISTS (
                    SELECT 1
                    FROM {$schema}.fss_refund_requests_disapproved newer
                    WHERE newer.request_id = d.request_id
                      AND newer.approval_date > d.approval_date
                )";

            $exists = (new \yii\db\Query())
                ->from($schema . '.fss_refund_requests_disapproved d')
                ->where(['d.request_id' => $requestId])
                ->andWhere('UPPER(COALESCE(d.approval_status, \'\')) = :status', [':status' => 'NOT APPROVED'])
                ->andWhere($originCondition)
                ->andWhere($latestRowCondition)
                ->exists($db);

            if ($exists) {
                return true;
            }
        }

        return false;
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
    public function actionTrack(?int $request_id = null)
    {
        /** @var User $user */
        $user = User::findOne(Yii::$app->user->id);
        $regNumber = $user->registration_number;
        $check = $this->checkEligibility($user);

        $request = null;
        $smisRequest = null;
        $approvals = [];
        $requests = [];
        if ($check['student_prog_curriculum_id']) {
            $requests = RefundRequest::find()
                ->where(['student_prog_curriculum_id' => $check['student_prog_curriculum_id']])
                ->with('refundType')
                ->orderBy(['application_date' => SORT_DESC, 'request_id' => SORT_DESC])
                ->all();

            if ($request_id !== null) {
                $request = RefundRequest::find()
                    ->where([
                        'student_prog_curriculum_id' => $check['student_prog_curriculum_id'],
                        'request_id' => $request_id,
                    ])
                    ->with('refundType')
                    ->one();

                if ($request === null) {
                    $this->setFlash('warning', 'Refund Request', 'The requested refund application was not found.');
                    return $this->redirect(['index']);
                }
            }

            if ($request === null && $requests) {
                $request = $requests[0];
            }

            if ($request) {
                $smisRequest = \app\modules\refund_requests\models\RefundRequestOfficial::findOne(['request_id' => $request->request_id]);
            }

            if ($request) {
                $approvals = ApprovalProcess::find()
                    ->where(['request_id' => $request->request_id])
                    ->andWhere('approval_date >= :application_date', [':application_date' => $request->application_date])
                    ->orderBy(['approval_date' => SORT_ASC, 'approval_process_id' => SORT_ASC])
                    ->all();
            }
        }

        if ($request === null) {
            $this->setFlash('info', 'Refund Request', 'No refund application is available to track.');
            return $this->redirect(['index']);
        }

        $allLevels = ApprovalLevel::find()->orderBy(['approval_order' => SORT_ASC])->all();
        $balance = $regNumber ? $this->calculateFeeBalance($regNumber) : 0;

        return $this->render('track', [
            'user' => $user,
            'request' => $request,
            'requests' => $requests,
            'smisRequest' => $smisRequest,
            'approvals' => $approvals,
            'allLevels' => $allLevels,
            'balance' => $balance,
            'cautionFeePaid' => $check['cautionFeePaid'],
            'expectedCautionFee' => $check['expectedCaution'],
            'overrideEligibility' => $this->module->overrideEligibility,
            'eligible' => $check['eligible'],
            'reason' => $check['reason'],
        ]);
    }
}
