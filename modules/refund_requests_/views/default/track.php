<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\modules\refund_requests\models\User $user */
/** @var app\modules\refund_requests\models\RefundRequest|null $request */
/** @var app\modules\refund_requests\models\RefundRequest[] $requests */
/** @var app\modules\refund_requests\models\ApprovalProcess[] $approvals */
/** @var app\modules\refund_requests\models\ApprovalLevel[] $allLevels */
/** @var float $balance */
/** @var float $cautionFeePaid */
/** @var float $expectedCautionFee */
/** @var bool $overrideEligibility */
/** @var bool $eligible */
/** @var string|null $reason */
/** @var array|null $cancelledVoucher */

$this->title = 'Full Process Tracking';
$this->registerCssFile('@web/css/refund-requests.css');

// Calculate current stage
$completedCount = count($approvals);
$totalStages = count($allLevels) + 2; // +1 for Eligibility, +1 for Application
$currentStageIndex = 0;
$requestStatus = $request ? strtoupper((string)$request->approval_status) : null;
$isRejected = $requestStatus === 'NOT APPROVED';
$cancelledVoucher = $cancelledVoucher ?? null;
$isCancelled = !empty($cancelledVoucher);
$approvedLevelIds = [];
foreach ($approvals as $approval) {
    if (!$approval->approver || strtoupper((string)$approval->approval_status) !== 'APPROVED') {
        continue;
    }

    $approvedLevelIds[(int)$approval->approver->approval_level_id] = true;
}
$isWorkflowApproved = $request && count($allLevels) > 0;
foreach ($allLevels as $level) {
    if (!isset($approvedLevelIds[(int)$level->approval_level_id])) {
        $isWorkflowApproved = false;
        break;
    }
}
$isRefunded = $request && (
    strtoupper((string)($request->refund_status ?? '')) === 'REFUNDED'
    || strtoupper((string)($smisRequest->refund_status ?? '')) === 'REFUNDED'
);
$isApproved = !$isCancelled && ($requestStatus === 'APPROVED' || $isWorkflowApproved || $isRefunded);
$requestStatusLabel = $isCancelled ? 'CANCELLED' : ($isRefunded ? 'PAID' : ($isRejected ? 'NOT APPROVED' : ($isApproved ? 'APPROVED' : $requestStatus)));
$referenceNo = $request ? '#REF-' . str_pad($request->request_id, 5, '0', STR_PAD_LEFT) : null;
$requestRefundAmount = 0.0;
if ($request) {
    $requestRefundAmount = (float)(
        ($smisRequest->amount_approved ?? null)
        ?: ($request->amount_approved ?? null)
        ?: ($smisRequest->amount_requested ?? null)
        ?: ($request->amount_requested ?? 0)
    );
}
$cautionPaidAmount = $isRefunded && $requestRefundAmount > 0
    ? $requestRefundAmount
    : (float)$cautionFeePaid;
$formatNairobiDateTime = static function ($value): string {
    if (empty($value)) {
        return 'Not recorded';
    }

    try {
        $date = new \DateTimeImmutable((string)$value, new \DateTimeZone('UTC'));
        return $date
            ->setTimezone(new \DateTimeZone('Africa/Nairobi'))
            ->format('d M Y, h:i:s A') . ' EAT';
    } catch (\Exception $e) {
        return (string)$value;
    }
};

if (!$request) {
    if ($eligible) {
        $currentStageIndex = 1; // Application stage (Eligibility passed)
    } else {
        $currentStageIndex = 0; // Eligibility stage (Stuck here)
    }
} else {
    $currentStageIndex = 2 + $completedCount; // Approval stages
}

if ($request && ($isRejected || $isApproved)) {
    $currentStageIndex = $totalStages;
}

$progressPercent = ($currentStageIndex / $totalStages) * 100;
$progressPercent = min(100, max(0, $progressPercent));
?>

<div class="cr-page">
    <div class="cr-container">
        <nav class="cr-breadcrumb" aria-label="Breadcrumb">
            <?= Html::a('Refund Requests', ['index']) ?>
            <span class="cr-breadcrumb__separator">/</span>
            <span class="cr-breadcrumb__current"><?= $request ? Html::encode($referenceNo) : 'Track' ?></span>
        </nav>
        
        <div class="cr-header">
            <span class="cr-header__badge">National Defence University of Kenya</span>
            <h1 class="cr-header__title">
                <?= $request ? Html::encode($request->refundType->refund_type_name) . ' Refund Overview' : 'Process Overview' ?>
            </h1>
            <p class="cr-header__sub">
                <?= $request ? Html::encode($referenceNo . ' - ' . $requestStatusLabel) : 'Real-time tracking of the entire refund lifecycle' ?>
            </p>
        </div>

        <?php if ($isCancelled): ?>
            <div class="cr-notice cr-notice--warning" style="margin-bottom: 1.5rem;">
                <p class="cr-notice__title">Voucher Cancelled</p>
                <p>
                    Voucher No. <?= Html::encode($cancelledVoucher['voucher_no'] ?? 'N/A') ?>
                    was cancelled on <?= Html::encode(!empty($cancelledVoucher['date_cancelled']) ? Yii::$app->formatter->asDatetime($cancelledVoucher['date_cancelled']) : 'a recorded date') ?>.
                    <?php if (!empty($cancelledVoucher['remarks'])): ?>
                        Remarks: <?= Html::encode($cancelledVoucher['remarks']) ?>
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if (!empty($requests)): ?>
            <div class="cr-card" style="margin-bottom: 1.5rem;">
                <div class="cr-card__body">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
                        <div>
                            <p style="font-size: 0.8rem; font-weight: 800; color: var(--cr-blue-600); text-transform: uppercase; margin: 0;">Select Request</p>
                            <p style="font-size: 0.82rem; color: var(--cr-slate-500); margin: 0;">Choose a reference number to view the exact lifecycle for that application.</p>
                        </div>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <?php foreach ($requests as $item): ?>
                                <?php
                                $itemStatus = strtoupper((string)$item->approval_status);
                                $itemIsRefunded = strtoupper((string)($item->refund_status ?? '')) === 'REFUNDED';
                                $itemIsOfficialRefunded = $request
                                    && $smisRequest
                                    && (int)$request->request_id === (int)$item->request_id
                                    && strtoupper((string)($smisRequest->refund_status ?? '')) === 'REFUNDED';
                                $itemStatusLabel = ($itemIsRefunded || $itemIsOfficialRefunded)
                                    ? 'PAID'
                                    : ($itemStatus === 'NOT APPROVED' ? 'NOT APPROVED' : $itemStatus);
                                $itemClass = $request && (int)$request->request_id === (int)$item->request_id ? 'cr-btn--primary' : 'cr-btn--secondary';
                                ?>
                                <?= Html::a(
                                    '#REF-' . str_pad($item->request_id, 5, '0', STR_PAD_LEFT) . ' - ' . $itemStatusLabel,
                                    ['track', 'request_id' => $item->request_id],
                                    ['class' => 'cr-btn ' . $itemClass, 'style' => 'padding: 0.45rem 0.8rem; font-size: 0.75rem;']
                                ) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="cr-card" style="margin-bottom: 2.5rem;">
            <div class="cr-card__body">
                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <span style="font-size: 0.8rem; font-weight: 800; color: var(--cr-blue-600); text-transform: uppercase; letter-spacing: 0.05em;">Lifecycle Completion</span>
                        <span style="font-size: 1rem; font-weight: 800; color: var(--cr-blue-800);"><?= round($progressPercent) ?>%</span>
                    </div>
                    <div style="height: 12px; background: var(--cr-slate-200); border-radius: var(--cr-radius-sm); overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                        <div style="height: 100%; width: <?= $progressPercent ?>%; background: var(--cr-blue-600); transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);"></div>
                    </div>
                </div>

                <div class="cr-steps" style="margin-bottom: 1rem;">
                    <div class="cr-step <?= $currentStageIndex > 0 ? 'cr-step--completed' : 'cr-step--active' ?>">
                        <div class="cr-step__icon">
                            <?= $currentStageIndex > 0 ? '<i class="fas fa-check"></i>' : '1' ?>
                        </div>
                        <div class="cr-step__label">Eligibility</div>
                        <div class="cr-step__line"></div>
                    </div>

                    <div class="cr-step <?= $currentStageIndex > 1 ? 'cr-step--completed' : ($currentStageIndex == 1 ? 'cr-step--active' : '') ?>">
                        <div class="cr-step__icon">
                            <?= $currentStageIndex > 1 ? '<i class="fas fa-check"></i>' : '2' ?>
                        </div>
                        <div class="cr-step__label">Application</div>
                        <div class="cr-step__line"></div>
                    </div>

                    <?php foreach ($allLevels as $idx => $level): 
                        $stageIdx = 2 + $idx;
                        $isComp = $currentStageIndex > $stageIdx;
                        $isAct = $currentStageIndex == $stageIdx;
                        $isRej = false;
                        foreach ($approvals as $app) {
                            if ($app->approver && $app->approver->approval_level_id == $level->approval_level_id && strtoupper($app->approval_status) === 'NOT APPROVED') {
                                $isRej = true;
                                break;
                            }
                        }
                        
                        $cls = $isComp ? 'cr-step--completed' : ($isAct ? 'cr-step--active' : '');
                        if ($isRej) {
                            $cls = 'cr-step--rejected';
                        } elseif ($isRejected && !$isComp) {
                            $cls = '';
                        }
                    ?>
                        <div class="cr-step <?= $cls ?>">
                            <div class="cr-step__icon">
                                <?php if ($isRej): ?><i class="fas fa-times"></i>
                                <?php elseif ($isComp): ?><i class="fas fa-check"></i>
                                <?php else: ?><?= $idx + 3 ?><?php endif; ?>
                            </div>
                            <div class="cr-step__label"><?= Html::encode($level->description) ?></div>
                            <?php if ($idx < count($allLevels) - 1): ?>
                                <div class="cr-step__line"></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="cr-card">
                    <div class="cr-card__header">
                        <div class="cr-card__header-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                            </svg>
                        </div>
                        <h2 class="cr-card__title">Phase Detail: 
                            <?php 
                            if ($currentStageIndex == 0) echo "Eligibility Check";
                            elseif ($currentStageIndex == 1) echo "Form Submission";
                            else echo "Approval Workflow";
                            ?>
                        </h2>
                    </div>
                    <div class="cr-card__body">
                        <?php if ($currentStageIndex == 0): ?>
                            <div class="cr-notice cr-notice--warning">
                                <p class="cr-notice__title">Action Required</p>
                                <p><?= Html::encode($reason ?: 'You have not yet met the eligibility criteria. Please ensure you meet all University requirements.') ?></p>
                            </div>
                            <div style="margin-top: 1.5rem;">
                                <?= Html::a('View Requirements Checklist', ['index'], ['class' => 'cr-btn cr-btn--secondary', 'style' => 'text-decoration: none;']) ?>
                            </div>
                        <?php elseif ($currentStageIndex == 1): ?>
                            <div class="cr-notice">
                                <p class="cr-notice__title">Next Step</p>
                                <p>You are eligible! Please fill out and submit the caution refund application form.</p>
                            </div>
                            <div style="margin-top: 1.5rem;">
                                <?= Html::a('Go to Application Form', ['apply'], ['class' => 'cr-btn cr-btn--primary']) ?>
                            </div>
                        <?php else: ?>
                            <div class="cr-timeline">
                                <?php foreach ($approvals as $approval): ?>
                                    <div class="cr-timeline-item">
                                        <div class="cr-timeline-dot" style="border-color: var(--cr-teal-400);"></div>
                                        <div style="display: flex; justify-content: space-between;">
                                            <h4 style="font-size: 0.9rem; font-weight: 700; margin: 0;"><?= Html::encode($approval->approver->approvalLevel->description ?? 'Unknown Level') ?></h4>
                                            <?php
                                            $approvalStatus = strtoupper((string)$approval->approval_status);
                                            $approvalIsNotApproved = $approvalStatus === 'NOT APPROVED';
                                            $approvalStatusLabel = $approvalIsNotApproved ? 'NOT APPROVED' : $approvalStatus;
                                            ?>
                                            <span class="cr-badge <?= $approvalIsNotApproved ? 'cr-badge--rejected' : 'cr-badge--approved' ?>"><?= Html::encode($approvalStatusLabel) ?></span>
                                        </div>
                                        <p style="font-size: 0.75rem; color: var(--cr-slate-400); margin: 0.2rem 0;"><?= Html::encode($formatNairobiDateTime($approval->approval_date)) ?></p>
                                        <?php if ($approval->remarks): ?>
                                            <div class="cr-timeline-comment"><?= Html::encode($approval->remarks) ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                                <?php if ($isRejected): ?>
                                    <div class="cr-timeline-item">
                                        <div class="cr-timeline-dot" style="border-color: var(--cr-red);"></div>
                                        <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--cr-red);">Request Closed: Not Approved</h4>
                                        <p style="font-size: 0.85rem; color: var(--cr-slate-400);">This request is no longer active. Use the request selector above to track another application.</p>
                                    </div>
                                <?php elseif ($isRefunded): ?>
                                    <div class="cr-timeline-item">
                                        <div class="cr-timeline-dot" style="border-color: var(--cr-teal-400);"></div>
                                        <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--cr-teal-600);">Refund Paid</h4>
                                        <p style="font-size: 0.85rem; color: var(--cr-slate-400);">Finance has marked this refund request as paid.</p>
                                    </div>
                                <?php elseif ($isApproved): ?>
                                    <div class="cr-timeline-item">
                                        <div class="cr-timeline-dot" style="border-color: var(--cr-teal-400);"></div>
                                        <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--cr-teal-600);">Request Approved</h4>
                                        <p style="font-size: 0.85rem; color: var(--cr-slate-400);">This request has completed the approval workflow.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="cr-timeline-item">
                                        <div class="cr-timeline-dot" style="border-color: var(--cr-blue-400); animation: pulse 2s infinite;"></div>
                                        <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--cr-blue-600);">Currently At: <?= Html::encode($allLevels[$completedCount]->description ?? 'Final Processing') ?></h4>
                                        <p style="font-size: 0.85rem; color: var(--cr-slate-400);">Awaiting review by the respective office.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="cr-card">
                    <div class="cr-card__header">
                        <div class="cr-card__header-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <h2 class="cr-card__title">Profile</h2>
                    </div>
                    <div class="cr-card__body">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <span style="font-size: 0.7rem; font-weight: 700; color: var(--cr-slate-400); text-transform: uppercase;">Request Status</span>
                            <span class="cr-badge <?= $request ? ($isCancelled ? 'cr-badge--rejected' : ($isApproved ? 'cr-badge--approved' : ($isRejected ? 'cr-badge--rejected' : 'cr-badge--pending'))) : 'cr-badge--rejected' ?>" style="font-size: 0.65rem;">
                                <?= $request ? Html::encode($referenceNo . ' - ' . $requestStatusLabel) : 'NO ACTIVE REQUEST' ?>
                            </span>
                        </div>

                        <p style="font-size: 0.9rem; font-weight: 800; color: var(--cr-blue-800); margin-bottom: 0.2rem;"><?= Html::encode($user->surname . ' ' . $user->other_names) ?></p>
                        <p style="font-size: 0.8rem; color: var(--cr-slate-400); margin-bottom: 0;"><?= Html::encode($user->registration_number) ?></p>
                    </div>
                </div>

                <div class="cr-card">
                    <div class="cr-card__header">
                        <div class="cr-card__header-icon" style="background: var(--cr-teal-400);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        </div>
                        <h2 class="cr-card__title">
                            <?= ($request && strtoupper($request->refundType->refund_type_name) === 'CAUTION') ? 'Caution Refund Summary' : 'Financial Summary' ?>
                        </h2>
                    </div>
                    <div class="cr-card__body" style="padding: 1.25rem;">
                        <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px dashed var(--cr-slate-100);">
                            <span style="font-size: 0.7rem; font-weight: 700; color: var(--cr-slate-400); text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                <?= $request ? ($isRefunded ? 'Paid Refund Amount' : 'Approved Refund Amount') : 'Total Fee Balance' ?>
                            </span>
                            <span style="font-size: 1.1rem; font-weight: 800; color: <?= (!$request && $balance > 0) ? 'var(--cr-red)' : 'var(--cr-teal-600)' ?>;">
                                <?= $request ? Yii::$app->formatter->asCurrency($requestRefundAmount) : Yii::$app->formatter->asCurrency($balance) ?>
                            </span>
                        </div>

                        <?php if (($request && strtoupper($request->refundType->refund_type_name) === 'CAUTION') || (!$request)): ?>
                            <div style="margin-bottom: 1rem;">
                                <span style="font-size: 0.7rem; font-weight: 700; color: var(--cr-slate-400); text-transform: uppercase; display: block; margin-bottom: 0.4rem;">Caution Money Details</span>
                                <div style="display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 0.25rem;">
                                    <span style="color: var(--cr-slate-600);">Paid:</span>
                                    <span style="font-weight: 700; color: var(--cr-slate-800);"><?= Yii::$app->formatter->asCurrency($cautionPaidAmount) ?></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 0.82rem;">
                                    <span style="color: var(--cr-slate-600);">Required:</span>
                                    <span style="font-weight: 700; color: var(--cr-slate-800);"><?= Yii::$app->formatter->asCurrency($expectedCautionFee) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($overrideEligibility && (!$request || strtoupper($request->refundType->refund_type_name) === 'CAUTION')): ?>
                            <div style="background: var(--cr-teal-50); border: 1px solid var(--cr-teal-100); border-radius: 8px; padding: 0.6rem; display: flex; align-items: flex-start; gap: 0.5rem;">
                                <div style="color: var(--cr-teal-600); font-size: 0.9rem;">✔️</div>
                                <div style="font-size: 0.75rem; color: var(--cr-teal-800); line-height: 1.4;">
                                    <strong>Override Enabled:</strong> Requirements for full caution fee payment are currently bypassed for this process.
                                </div>
                            </div>
                        <?php elseif (!$overrideEligibility && $cautionFeePaid < $expectedCautionFee && (!$request || strtoupper($request->refundType->refund_type_name) === 'CAUTION')): ?>
                            <div style="background: var(--cr-red-50); border: 1px solid var(--cr-red-100); border-radius: 8px; padding: 0.6rem; display: flex; align-items: flex-start; gap: 0.5rem;">
                                <div style="color: var(--cr-red); font-size: 0.9rem;">⚠️</div>
                                <div style="font-size: 0.75rem; color: var(--cr-red-800); line-height: 1.4;">
                                    <strong>Payment Required:</strong> You must fully pay the caution fee before applying for a refund.
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="alert alert-info" style="background: var(--cr-blue-50); border: none; border-radius: var(--cr-radius); padding: 1.25rem;">
                    <h5 style="font-size: 0.9rem; font-weight: 800; color: var(--cr-blue-800);"><i class="fas fa-lightbulb mr-2"></i> Information</h5>
                    <p style="font-size: 0.82rem; color: var(--cr-slate-700); line-height: 1.5; margin: 0;">This tracker represents the full administrative path of your refund request, including both student-facing and internal back-office steps.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
  0% { box-shadow: 0 0 0 0 rgba(91, 163, 222, 0.4); }
  70% { box-shadow: 0 0 0 10px rgba(91, 163, 222, 0); }
  100% { box-shadow: 0 0 0 0 rgba(91, 163, 222, 0); }
}
</style>
