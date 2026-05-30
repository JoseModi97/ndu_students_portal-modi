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
/** @var float|null $cautionFeePaid */
/** @var float|null $expectedCautionFee */
/** @var bool $overrideCautionFee */

$this->title = 'Refund Request Dashboard';

$this->registerCssFile('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');
$this->registerCssFile('@web/css/refund-requests.css');

$totalLevels = count($allLevels);

$studentInfoHtml = "<b>Name:</b> " . Html::encode($user->surname . ' ' . $user->other_names) . "<br><b>Reg No:</b> " . Html::encode($user->registration_number);
$helpHtml = "For technical issues with this portal, contact ICT Support. For application status inquiries after 14 days, visit the Finance Office.";
$secureHtml = "All refund disbursements are audited and verified to ensure funds are sent only to accounts registered in the student's name.";

// Initialize Popovers
$this->registerJs("
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"popover\"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
      return new bootstrap.Popover(popoverTriggerEl, {
        html: true,
        trigger: 'hover focus'
      })
    })

    $('.dash-refund-type').on('change', function() {
        var typeId = $(this).val();
        var typeText = $(this).find('option:selected').text().toUpperCase();
        var applyBtn = $('#proceed-to-apply');
        var selectField = $(this);
        var errorMsg = $('#type-error-msg');
        var cautionSummary = $('#caution-refund-summary');
        
        var cautionFeePaid = " . (float)$cautionFeePaid . ";
        var expectedCautionFee = " . (float)$expectedCautionFee . ";
        var overrideCautionFee = " . ($overrideCautionFee ? 'true' : 'false') . ";
        
        cautionSummary.hide();
        errorMsg.hide();
        selectField.css('border-color', 'var(--cr-blue-100)');

        if (typeId) {
            // Logic for Caution Refund
            if (typeText.includes('CAUTION')) {
                var displayAmount = (cautionFeePaid >= expectedCautionFee) ? cautionFeePaid : (overrideCautionFee ? expectedCautionFee : 0);
                
                if (cautionFeePaid < expectedCautionFee && !overrideCautionFee) {
                    applyBtn.attr('href', '#');
                    applyBtn.css({'opacity': '0.7'});
                    selectField.css('border-color', 'var(--cr-red)');
                    errorMsg.text('You cannot apply for a Caution Refund because you have not fully paid the CAUTION FEE.').show();
                    return;
                }

                if (displayAmount <= 0) {
                    applyBtn.attr('href', '#');
                    applyBtn.css({'opacity': '0.7'});
                    selectField.css('border-color', 'var(--cr-red)');
                    errorMsg.text('You cannot apply for a Caution Refund because the refundable amount is zero.').show();
                    return;
                }
                
                // Show dynamic summary for caution
                $('#caution-amount-display').text(new Intl.NumberFormat('en-KE', { 
                    style: 'currency', 
                    currency: 'KES',
                    minimumFractionDigits: 2
                }).format(displayAmount));
                
                cautionSummary.fadeIn();
            }

            applyBtn.css({'opacity': '1'});
            errorMsg.hide();
        } else {
            applyBtn.attr('href', '#');
            applyBtn.css({'opacity': '0.7'});
        }
    });

    $('#proceed-to-apply').on('click', function(e) {
        e.preventDefault();
        var typeId = $('.dash-refund-type').val();
        var typeText = $('.dash-refund-type option:selected').text().toUpperCase();
        var cautionFeePaid = " . (float)$cautionFeePaid . ";
        var expectedCautionFee = " . (float)$expectedCautionFee . ";
        var overrideCautionFee = " . ($overrideCautionFee ? 'true' : 'false') . ";

        if (!typeId) {
            $('.dash-refund-type').css('border-color', 'var(--cr-red)').focus();
            $('#type-error-msg').text('Please select a refund type to proceed').show();
            return;
        } 
        
        if (typeText.includes('CAUTION')) {
            var displayAmount = (cautionFeePaid >= expectedCautionFee) ? cautionFeePaid : (overrideCautionFee ? expectedCautionFee : 0);
            if (cautionFeePaid < expectedCautionFee && !overrideCautionFee) {
                $('#type-error-msg').text('You cannot apply for a Caution Refund because you have not fully paid the CAUTION FEE.').show();
                return;
            } else if (displayAmount <= 0) {
                $('#type-error-msg').text('You cannot apply for a Caution Refund because the refundable amount is zero.').show();
                return;
            }
            $('#apply-post-amount').val(displayAmount);
        }

        $('#apply-post-type').val(typeId);
        $('#apply-post-form').submit();
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
        <?php endif; ?>

        <div class="cr-card">
            <div class="cr-card__header" style="display: flex; align-items: center;">
                <div class="cr-card__header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <?php if ($mode === 'status'): ?>
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        <?php else: ?>
                            <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        <?php endif; ?>
                    </svg>
                </div>
                <h2 class="cr-card__title"><?= $mode === 'status' ? 'Application Summary' : 'Requirements Checklist' ?></h2>
                
                <div style="margin-left: auto; display: flex; gap: 0.6rem;">
                    <button type="button" class="btn btn-sm p-0 rounded-circle d-flex align-items-center justify-content-center" 
                            style="width:30px; height:30px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white;"
                            data-bs-toggle="popover" data-bs-trigger="hover focus" title="Student Information" data-bs-content="<?= $studentInfoHtml ?>">
                        <i class="fas fa-user-graduate text-dark" style="font-size: 0.85rem;"></i>
                    </button>
                    <button type="button" class="btn btn-sm p-0 rounded-circle d-flex align-items-center justify-content-center" 
                            style="width:30px; height:30px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white;"
                            data-bs-toggle="popover" data-bs-trigger="hover focus" title="Need Assistance?" data-bs-content="<?= $helpHtml ?>">
                        <i class="fas fa-headset text-dark" style="font-size: 0.85rem;"></i>
                    </button>
                    <button type="button" class="btn btn-sm p-0 rounded-circle d-flex align-items-center justify-content-center" 
                            style="width:30px; height:30px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white;"
                            data-bs-toggle="popover" data-bs-trigger="hover focus" title="Secure Processing" data-bs-content="<?= $secureHtml ?>">
                        <i class="fas fa-user-shield text-dark" style="font-size: 0.85rem;"></i>
                    </button>
                </div>
            </div>

            <div class="cr-card__body">
                <?php if ($mode === 'status'): ?>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Current Status</span>
                        <span class="cr-status-row__value">
                            <?php 
                            $s = strtoupper($request->approval_status);
                            $b = $s === 'APPROVED' ? 'cr-badge--approved' : ($s === 'REJECTED' ? 'cr-badge--rejected' : 'cr-badge--pending');
                            ?>
                            <span class="cr-badge <?= $b ?>" style="font-size: 0.9rem; padding: 0.3rem 1rem;"><?= $s ?></span>
                        </span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Refund Type</span>
                        <span class="cr-status-row__value"><span class="badge bg-info text-dark" style="font-weight: 700;"><?= Html::encode($request->refundType->refund_type_name ?? 'Standard') ?></span></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Reference No</span>
                        <span class="cr-status-row__value">#REF-<?= str_pad($request->request_id, 5, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">
                            <?= (isset($request->refundType) && strtoupper($request->refundType->refund_type_name) === 'CAUTION') ? 'Caution Amount' : 'Requested Amount' ?>
                        </span>
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
                    <div style="text-align: center; margin-top: 2rem;">
                        <?= Html::a('View Application Details', ['track'], ['class' => 'cr-btn cr-btn--primary']) ?>
                    </div>
                <?php else: ?>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">University Clearance</span>
                        <span class="cr-status-row__value">
                            <?php if (strtoupper($user->clearance_status) === 'CLEARED'): ?>
                                <span class="cr-badge cr-badge--approved">CLEARED</span>
                            <?php else: ?>
                                <span class="cr-badge cr-badge--rejected"><?= Html::encode($user->clearance_status ?: 'PENDING') ?></span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Fee Balance</span>
                        <span class="cr-status-row__value">
                            <span class="cr-badge cr-badge--pending" style="background: var(--cr-blue-50); border: 1px solid var(--cr-blue-200); color: var(--cr-blue-800);">
                                <?= Yii::$app->formatter->asCurrency($balance ?? 0) ?>
                            </span>
                            <small class="ms-2 text-muted" style="font-size: 0.75rem;">(Informational only)</small>
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
                        </span>
                    </div>

                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Caution Money Payment</span>
                        <span class="cr-status-row__value">
                            <?php 
                            $cp = (float)$cautionFeePaid;
                            $ex = (float)$expectedCautionFee;
                            $ov = (bool)$overrideCautionFee;
                            $cpBadge = ($cp >= $ex || $ov) ? 'cr-badge--approved' : 'cr-badge--rejected';
                            $cpLabel = ($cp >= $ex) ? 'PAID' : ($ov ? 'OVERRIDDEN' : 'NOT PAID');
                            ?>
                            <span class="cr-badge <?= $cpBadge ?>"><?= $cpLabel ?></span>
                        </span>
                    </div>

                    <div class="cr-section">
                        <?php if ($mode === 'eligibility'): ?>
                            <div class="cr-notice">
                                <p class="cr-notice__title">Congratulations!</p>
                                <p style="font-size:0.9rem; color:var(--cr-slate-700);">You meet all the requirements for a refund request. You can now proceed to fill out the application form.</p>
                            </div>

                            <div style="margin-top: 2rem; border-top: 1px solid var(--cr-blue-50); padding-top: 1.5rem;">
                                <p style="font-size: 0.85rem; font-weight: 700; color: var(--cr-blue-600); text-transform: uppercase; margin-bottom: 1rem; text-align: center;">Select Refund Type to Proceed</p>

                                <div style="max-width: 400px; margin: 0 auto 0.5rem auto;">
                                    <?= Html::dropDownList('dash-refund-type', null, 
                                        \yii\helpers\ArrayHelper::map($refundTypes, 'refund_type_id', function($type) {
                                            return $type->displayName;
                                        }), 
                                        [
                                            'class' => 'form-select dash-refund-type',
                                            'prompt' => '--- Choose Refund Type ---',
                                            'style' => 'border-radius: 12px; border: 2px solid var(--cr-blue-100); padding: 0.75rem 1rem; font-family: var(--cr-font); font-weight: 600; color: var(--cr-blue-800); transition: border-color 0.2s;'
                                        ]
                                    ) ?>
                                </div>
                                <p id="type-error-msg" style="display: none; color: var(--cr-red); font-size: 0.8rem; font-weight: 700; text-align: center; margin-bottom: 1.5rem;">Please select a refund type to proceed</p>

                                <div id="caution-refund-summary" style="display: none; background: var(--cr-teal-50); border: 1px solid var(--cr-teal-200); border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; text-align: center;">
                                    <p style="margin: 0; font-size: 0.8rem; color: var(--cr-teal-800); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Estimated Refundable Amount</p>
                                    <p id="caution-amount-display" style="margin: 0; font-size: 1.5rem; font-weight: 800; color: var(--cr-teal-900);"></p>
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem; color: var(--cr-teal-600);">This amount is based on your Caution Money records.</p>
                                </div>

                                <div style="text-align: center; margin-top: 1rem;">
                                    <?= Html::beginForm(['apply'], 'post', ['id' => 'apply-post-form', 'style' => 'display:none;']) ?>
                                        <input type="hidden" name="type" id="apply-post-type">
                                        <input type="hidden" name="amount" id="apply-post-amount">
                                    <?= Html::endForm() ?>

                                    <?= Html::a('Proceed to Application', '#', [
                                        'id' => 'proceed-to-apply',
                                        'class' => 'cr-btn cr-btn--primary',
                                        'style' => 'opacity: 0.7;'
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
