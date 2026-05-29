<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var string $mode 'eligibility' | 'status' | 'not-eligible' */
/** @var app\modules\refund_requests\models\User $user */
/** @var app\modules\refund_requests\models\RefundRequest|null $request */
/** @var app\modules\refund_requests\models\ApprovalProcess[]|null $approvals */
/** @var app\modules\refund_requests\models\ApprovalLevel[] $allLevels */
/** @var app\modules\refund_requests\models\RefundType[] $refundTypes */
/** @var string $academicStatus */
/** @var bool|null $eligible */
/** @var string|null $reason */
/** @var float|null $balance */

$this->title = 'Refund Request Dashboard';

$this->registerCssFile('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');
$this->registerCssFile('@web/css/refund-requests.css');

$totalLevels = count($allLevels);

// Initialize Popovers
$this->registerJs("
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"popover\"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
      return new bootstrap.Popover(popoverTriggerEl)
    })

    $('.dash-refund-type').on('change', function() {
        var typeId = $(this).val();
        var baseUrl = '" . Url::to(['apply']) . "';
        var applyBtn = $('#proceed-to-apply');
        applyBtn.attr('href', baseUrl + (baseUrl.includes('?') ? '&' : '?') + 'type=' + typeId);
        applyBtn.removeClass('disabled').css({'opacity': '1', 'pointer-events': 'auto'});
    });
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
            <h1 class="cr-header__title">Refund Request</h1>
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
                        <span class="cr-status-row__value">#REF-<?= str_pad($request->request_id, 5, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Declaration Status</span>
                        <span class="cr-status-row__value">
                            <?php if ($request->declaration_status == '1'): ?>
                                <span class="cr-badge cr-badge--approved">ACCEPTED</span>
                            <?php else: ?>
                                <span class="cr-badge cr-badge--rejected">NOT ACCEPTED</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Requested Amount</span>
                        <span class="cr-status-row__value"><strong><?= Yii::$app->formatter->asCurrency($request->amount_requested) ?></strong></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Payment Mode</span>
                        <span class="cr-status-row__value">
                            <?php if ($request->payment_method === 'bank'): ?>
                                <span class="badge bg-secondary mb-1">Bank Transfer</span><br>
                                <span style="font-size: 0.85rem; color: var(--cr-slate-400);">
                                    <?= Html::encode($request->bank->bank_name ?? 'Unknown Bank') ?> 
                                    (Acc: <?= Html::encode($request->account_no) ?>)
                                </span>
                            <?php elseif ($request->payment_method === 'mpesa'): ?>
                                <span class="badge bg-success mb-1">M-PESA</span><br>
                                <span style="font-size: 0.85rem; color: var(--cr-slate-400);">Mobile: <?= Html::encode($request->mobile_no) ?></span>
                            <?php else: ?>
                                <span class="badge bg-warning mb-1">Not Specified</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Current Status</span>
                        <span class="cr-status-row__value">
                            <?php 
                            $s = strtoupper($request->refund_status);
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
                                <p style="font-size:0.9rem; color:var(--cr-slate-700);">You meet all the requirements for a refund request. You can now proceed to fill out the application form.</p>
                            </div>

                            <div style="margin-top: 2rem; border-top: 1px solid var(--cr-blue-50); padding-top: 1.5rem;">
                                <p style="font-size: 0.85rem; font-weight: 700; color: var(--cr-blue-600); text-transform: uppercase; margin-bottom: 1.5rem; text-align: center;">Select Refund Type to Proceed</p>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
                                    <?php foreach ($refundTypes as $type): 
                                        $id = 'dash-type-' . strtolower($type->refund_type_name);
                                    ?>
                                        <label class="cr-type-card" for="<?= $id ?>">
                                            <input class="dash-refund-type" type="radio" name="dash-refund-type" id="<?= $id ?>"
                                                value="<?= $type->refund_type_id ?>">
                                            <div class="cr-type-card__content">
                                                <div class="cr-type-card__icon">
                                                    <?php if ($type->refund_type_name === 'CHSS'): ?>
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                                                    <?php else: ?>
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3"></path></svg>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="cr-type-card__label"><?= Html::encode($type->displayName) ?></div>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>

                                <div style="text-align: center;">
                                    <?= Html::a('Proceed to Application', '#', [
                                        'id' => 'proceed-to-apply',
                                        'class' => 'cr-btn cr-btn--primary disabled',
                                        'style' => 'opacity: 0.5; pointer-events: none;'
                                    ]) ?>
                                </div>
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
