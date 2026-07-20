<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var string $mode 'eligibility' | 'status' | 'not-eligible' */
/** @var app\modules\caution_refund\models\User $user */
/** @var app\modules\caution_refund\models\CautionRefund|null $request */
/** @var app\modules\caution_refund\models\ApprovalProcess[]|null $approvals */
/** @var app\modules\caution_refund\models\ApprovalLevel[] $allLevels */
/** @var string $academicStatus */
/** @var bool|null $eligible */
/** @var string|null $reason */
/** @var float|null $balance */

$this->title = 'Caution Refund Dashboard';

$this->registerCssFile('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');
$this->registerCssFile('@web/css/caution-refund.css');

$totalLevels = count($allLevels);

// Initialize Popovers
$this->registerJs("
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"popover\"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
      return new bootstrap.Popover(popoverTriggerEl)
    })
");
?>

<div class="cr-page">
    <div class="cr-container">
        
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="cr-flash cr-flash--success"><?= Html::encode(Yii::$app->session->getFlash('success')) ?></div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('danger')): ?>
            <div class="cr-flash cr-flash--danger"><?= Html::encode(Yii::$app->session->getFlash('danger')) ?></div>
        <?php endif; ?>

        <div class="cr-header">
            <span class="cr-header__badge">National Defence University of Kenya</span>
            <h1 class="cr-header__title">Caution Refund</h1>
            <p class="cr-header__sub">
                <?= $mode === 'status' ? 'Application Tracking' : 'Eligibility & Application' ?>
            </p>
        </div>

        <?php if ($mode === 'status'): ?>
            <!-- Mode: ALREADY APPLIED -->
            <div class="cr-notice" style="background: var(--cr-blue-50); border-color: var(--cr-blue-400); margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 1.5rem;">ℹ️</div>
                <div>
                    <p class="cr-notice__title" style="margin:0;">Active Application Found</p>
                    <p style="font-size: 0.85rem; margin:0; color: var(--cr-slate-700);">You have an active refund request submitted on <?= Yii::$app->formatter->asDate($request->application_date) ?>.</p>
                </div>
            </div>

            <!-- Workflow Tracker Integrated -->
            <div class="cr-card" style="margin-bottom: 2rem;">
                <div class="cr-card__header">
                    <div class="cr-card__header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <h2 class="cr-card__title">Live Workflow Tracking</h2>
                </div>
                <div class="cr-card__body">
                    <?php
                    $progressPercent = ($totalLevels > 0) ? (count($approvals) / $totalLevels) * 100 : 0;
                    $completedLevels = [];
                    foreach ($approvals as $app) { $completedLevels[$app->level_id] = strtoupper($app->status); }
                    ?>
                    <div style="margin-bottom: 2rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <span style="font-size: 0.75rem; font-weight: 700; color: var(--cr-blue-600); text-transform: uppercase;">Approval Progress</span>
                            <span style="font-size: 0.85rem; font-weight: 800; color: var(--cr-blue-800);"><?= round($progressPercent) ?>%</span>
                        </div>
                        <div style="height: 8px; background: var(--cr-blue-50); border-radius: 999px; overflow: hidden;">
                            <div style="height: 100%; width: <?= $progressPercent ?>%; background: linear-gradient(90deg, var(--cr-blue-400), var(--cr-teal-400)); transition: width 1s ease-in-out;"></div>
                        </div>
                    </div>

                    <div class="cr-steps">
                        <?php foreach ($allLevels as $index => $level): 
                            $isCompleted = isset($completedLevels[$level->level_id]);
                            $isCurrent = !$isCompleted && (empty($approvals) ? ($index === 0) : (count($approvals) === $index));
                            $isRejected = ($completedLevels[$level->level_id] ?? '') === 'REJECTED';
                            $stepClass = $isCompleted ? 'cr-step--completed' : ($isCurrent ? 'cr-step--active' : '');
                            if ($isRejected) $stepClass = 'cr-step--rejected';
                        ?>
                            <div class="cr-step <?= $stepClass ?>">
                                <div class="cr-step__icon">
                                    <?php if ($isRejected): ?><i class="fas fa-times"></i>
                                    <?php elseif ($isCompleted): ?><i class="fas fa-check"></i>
                                    <?php else: ?><?= $index + 1 ?><?php endif; ?>
                                </div>
                                <div class="cr-step__label"><?= Html::encode($level->level_name) ?></div>
                                <?php if ($index < $totalLevels - 1): ?>
                                    <div class="cr-step__line"></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="cr-card">
                <div class="cr-card__header">
                    <div class="cr-card__header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <h2 class="cr-card__title">Application Summary</h2>
                </div>
                <div class="cr-card__body">
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Reference No</span>
                        <span class="cr-status-row__value">#REF-<?= str_pad($request->refund_id, 5, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Requested Amount</span>
                        <span class="cr-status-row__value"><strong><?= Yii::$app->formatter->asCurrency($request->refund_amount) ?></strong></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Payment Mode</span>
                        <span class="cr-status-row__value">
                            <?php if ($request->bankBranch): ?>
                                <span class="badge bg-secondary mb-1">Bank Transfer</span><br>
                                <span style="font-size: 0.85rem; color: var(--cr-slate-400);"><?= Html::encode($request->bankBranch->bank->bank_name) ?> (Acc: <?= Html::encode($request->account_number) ?>)</span>
                            <?php else: ?>
                                <span class="badge bg-success mb-1">M-PESA</span><br>
                                <span style="font-size: 0.85rem; color: var(--cr-slate-400);">Mobile: <?= Html::encode($request->mobile_number) ?></span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Current Status</span>
                        <span class="cr-status-row__value">
                            <?php 
                            $s = strtoupper($request->status);
                            $b = $s === 'APPROVED' ? 'cr-badge--approved' : ($s === 'REJECTED' ? 'cr-badge--rejected' : 'cr-badge--pending');
                            ?>
                            <span class="cr-badge <?= $b ?>"><?= $s ?></span>
                        </span>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Mode: ELIGIBILITY / NOT-ELIGIBLE -->
            <div class="cr-card">
                <div class="cr-card__header">
                    <div class="cr-card__header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <h2 class="cr-card__title">Student Information</h2>
                </div>
                <div class="cr-card__body">
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Full Name</span>
                        <span class="cr-status-row__value"><?= Html::encode($user->surname . ' ' . $user->other_names) ?></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Registration No</span>
                        <span class="cr-status-row__value"><?= Html::encode($user->registration_number) ?></span>
                    </div>
                </div>
            </div>

            <div class="cr-card">
                <div class="cr-card__header">
                    <div class="cr-card__header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                    </div>
                    <h2 class="cr-card__title">Requirements Checklist</h2>
                </div>
                <div class="cr-card__body">
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">University Clearance</span>
                        <span class="cr-status-row__value">
                            <?php if (strtoupper($user->clearance_status) === 'CLEARED'): ?>
                                <span class="cr-badge cr-badge--approved">CLEARED</span>
                            <?php else: ?>
                                <span class="cr-badge cr-badge--rejected"><?= Html::encode($user->clearance_status ?: 'PENDING') ?></span>
                            <?php endif; ?>
                            <i class="fas fa-question-circle text-muted ms-1" 
                               style="cursor: pointer;"
                               data-bs-toggle="popover" 
                               data-bs-trigger="hover focus" 
                               title="University Clearance" 
                               data-bs-content="Official confirmation that you have returned all university property (library books, equipment) and fulfilled all non-financial obligations."></i>
                        </span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Fee Balance</span>
                        <span class="cr-status-row__value">
                            <?php if (isset($balance) && $balance <= 0): ?>
                                <span class="cr-badge cr-badge--approved">NO DEBT</span>
                            <?php else: ?>
                                <span class="cr-badge cr-badge--rejected"><?= Yii::$app->formatter->asCurrency($balance ?? 0) ?></span>
                            <?php endif; ?>
                            <i class="fas fa-question-circle text-muted ms-1" 
                               style="cursor: pointer;"
                               data-bs-toggle="popover" 
                               data-bs-trigger="hover focus" 
                               title="Fee Balance" 
                               data-bs-content="Your total outstanding debt to the university. A zero balance (NO DEBT) is required to process any refunds."></i>
                        </span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Academic Status</span>
                        <span class="cr-status-row__value">
                            <?php 
                            $as = strtoupper($academicStatus);
                            $asBadge = ($as === 'GRADUATED' || $as === 'COMPLETED') ? 'cr-badge--approved' : 'cr-badge--rejected';
                            ?>
                            <span class="cr-badge <?= $asBadge ?>"><?= Html::encode($as) ?></span>
                            <i class="fas fa-question-circle text-muted ms-1" 
                               style="cursor: pointer;"
                               data-bs-toggle="popover" 
                               data-bs-trigger="hover focus" 
                               title="Academic Status" 
                               data-bs-content="Your current standing in the Student Management Information System. Refund applications are only permitted for students who have officially 'GRADUATED' or 'COMPLETED' their studies."></i>
                        </span>
                    </div>

                    <div class="cr-section">
                        <?php if ($mode === 'eligibility'): ?>
                            <div class="cr-notice">
                                <p class="cr-notice__title">Congratulations!</p>
                                <p style="font-size:0.9rem; color:var(--cr-slate-700);">You meet all the requirements for a caution money refund. You can now proceed to fill out the application form.</p>
                            </div>
                            <div style="text-align: center; margin-top: 1.5rem;">
                                <?= Html::a('Proceed to Application', ['apply'], ['class' => 'cr-btn cr-btn--primary']) ?>
                            </div>
                        <?php else: ?>
                            <div class="cr-notice cr-notice--warning">
                                <p class="cr-notice__title">Action Required</p>
                                <p style="font-size:0.9rem; color:var(--cr-slate-700);"><?= Html::encode($reason) ?></p>
                                <p style="font-size:0.8rem; color:var(--cr-slate-400); margin-top:0.5rem;">Please ensure all checklist items are marked with a green badge before you can apply.</p>
                            </div>
                            <div style="text-align: center; margin-top: 1.5rem;">
                                <?= Html::a('Refresh Status', ['index'], ['class' => 'cr-btn cr-btn--secondary']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Footer Info (Always Shown) -->
        <div class="cr-form-grid" style="margin-top: 2rem;">
            <div class="cr-card">
                <div class="cr-card__header">
                    <div class="cr-card__header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <h2 class="cr-card__title">Need Assistance?</h2>
                </div>
                <div class="cr-card__body small">
                    <p style="font-size:0.82rem; line-height:1.6; color:var(--cr-slate-400); margin:0;">
                        For technical issues with this portal, contact ICT Support. For application status inquiries after 14 days, visit the Finance Office.
                    </p>
                </div>
            </div>
            <div class="cr-card">
                <div class="cr-card__header">
                    <div class="cr-card__header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <h2 class="cr-card__title">Secure Processing</h2>
                </div>
                <div class="cr-card__body small">
                    <p style="font-size:0.82rem; line-height:1.6; color:var(--cr-slate-400); margin:0;">
                        All refund disbursements are audited and verified to ensure funds are sent only to accounts registered in the student's name.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

