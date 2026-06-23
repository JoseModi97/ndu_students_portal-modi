<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var string $mode 'eligibility' | 'status' | 'not-eligible' */
/** @var app\modules\refund_requests\models\User $user */
/** @var app\modules\refund_requests\models\RefundRequest|null $request */
/** @var app\modules\refund_requests\models\RefundRequestOfficial|null $smisRequest */
/** @var app\modules\refund_requests\models\ApprovalProcess[]|null $approvals */
/** @var app\modules\refund_requests\models\ApprovalLevel[] $allLevels */
/** @var app\modules\refund_requests\models\RefundType[] $refundTypes */
/** @var string $academicStatus */
/** @var bool|null $eligible */
/** @var string|null $reason */
/** @var float|null $balance */
/** @var float|null $cautionFeePaid */
/** @var float|null $expectedCautionFee */
/** @var float|null $cautionReservedAmount */
/** @var float|null $cautionRemainingAmount */
/** @var bool $overrideEligibility */
/** @var app\modules\refund_requests\models\RefundRequest[] $previousRequests */
/** @var array $refundedRequests */
/** @var array $activeRequests */
/** @var array|null $cancelledVoucher */

$this->title = 'Refund Request Dashboard';

$this->registerCssFile('@web/css/refund-requests.css');

$request = $request ?? null;
$approvals = $approvals ?? [];
$allLevels = $allLevels ?? [];
$refundedRequests = $refundedRequests ?? [];
$activeRequests = $activeRequests ?? [];
$cancelledVoucher = $cancelledVoucher ?? null;
$isCancelled = !empty($cancelledVoucher);
$refundedTypeIdsJson = Json::htmlEncode(array_fill_keys(array_map('strval', array_keys($refundedRequests)), true));
$activeTypeIdsJson = Json::htmlEncode(array_fill_keys(array_map('strval', array_keys($activeRequests)), true));
$totalLevels = count($allLevels);

$studentInfoHtml = "<b>Name:</b> " . Html::encode($user->surname . ' ' . $user->other_names) . "<br><b>Reg No:</b> " . Html::encode($user->registration_number);
$helpHtml = "For technical issues with this portal, contact ICT Support. For application status inquiries after 14 days, visit the Finance Office.";
$secureHtml = "All refund disbursements are audited and verified to ensure funds are sent only to accounts registered in the student's name.";
$hasRejectedRequests = !empty($previousRequests);
$showMainRequestCard = !$hasRejectedRequests;
$officialRequest = $smisRequest ?? null;
$approvedLevelIds = [];
foreach ((array)$approvals as $approval) {
    if (!$approval->approver || strtoupper((string)$approval->approval_status) !== 'APPROVED') {
        continue;
    }

    $approvedLevelIds[(int)$approval->approver->approval_level_id] = true;
}
$isWorkflowApproved = $request && $totalLevels > 0;
foreach ($allLevels as $level) {
    if (!isset($approvedLevelIds[(int)$level->approval_level_id])) {
        $isWorkflowApproved = false;
        break;
    }
}
$isRefunded = $request && (
    strtoupper((string)($request->refund_status ?? '')) === 'REFUNDED'
    || strtoupper((string)($officialRequest->refund_status ?? '')) === 'REFUNDED'
);
$headerSub = $mode === 'status' ? 'Application Tracking' : 'Eligibility & Application';
$clearanceStatusJson = Json::htmlEncode(strtoupper((string)($user->clearance_status ?: 'PENDING')));
$academicStatusJson = Json::htmlEncode(strtoupper((string)$academicStatus));

// Initialize Popovers
$this->registerJs("
    var refundedRequestTypeIds = {$refundedTypeIdsJson};
    var activeRequestTypeIds = {$activeTypeIdsJson};

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
        var cautionReservedAmount = " . (float)$cautionReservedAmount . ";
        var cautionRemainingAmount = " . (float)$cautionRemainingAmount . ";
        var feeBalance = " . (float)$balance . ";
        var overrideEligibility = " . ($overrideEligibility ? 'true' : 'false') . ";
        var clearanceStatus = {$clearanceStatusJson};
        var academicStatus = {$academicStatusJson};
        
        cautionSummary.hide();
        errorMsg.hide();
        selectField.css('border-color', 'var(--cr-blue-100)');

        var currencyFormatter = new Intl.NumberFormat('en-KE', {
            style: 'currency',
            currency: 'KES',
            minimumFractionDigits: 2
        });

        var appendWarning = function(errorMsg, warning) {
            var source = warning.source || '';
            var badgeColor = source === 'smisportal' ? '#0f766e' : (source === 'smis' ? '#1d4ed8' : '#7c3aed');
            var line = $('<span>').css('display', 'block');
            line.text(warning.message + ' ');
            /*
            if (source) {
                $('<span>')
                    .text(source)
                    .css({
                        'display': 'inline-block',
                        'margin-left': '0.35rem',
                        'padding': '0.1rem 0.4rem',
                        'border-radius': '999px',
                        'background': badgeColor,
                        'color': '#fff',
                        'font-size': '0.68rem',
                        'font-weight': '800',
                        'text-transform': 'uppercase'
                    })
                    .appendTo(line);
            }
            */
            line.appendTo(errorMsg);
        };

        var disableSelection = function(messages) {
            messages = Array.isArray(messages) ? messages : [messages];
            applyBtn.attr('href', '#');
            applyBtn.css({'opacity': '0.7'});
            selectField.css('border-color', 'var(--cr-red)');
            errorMsg.empty();
            messages.forEach(function(message) {
                appendWarning(errorMsg, message);
            });
            errorMsg.show();
        };

        if (typeId) {
            if (refundedRequestTypeIds[typeId] || activeRequestTypeIds[typeId]) {
                applyBtn.css({'opacity': '1'});
                errorMsg.hide();
                return;
            }

            var warnings = [];
            if (clearanceStatus !== 'CLEARED') {
                warnings.push({
                    message: 'You must be CLEARED to apply for this refund type. Current status: ' + clearanceStatus + '.',
                    source: 'smisportal'
                });
            }

            if (academicStatus !== 'GRADUATED' && academicStatus !== 'COMPLETED') {
                warnings.push({
                    message: 'Refund requests are only available for GRADUATED or COMPLETED students. Your current status: ' + academicStatus + '.',
                    source: 'smis'
                });
            }

            if (feeBalance > 0 && !overrideEligibility) {
                warnings.push({
                    message: 'You have an outstanding fee balance of ' + currencyFormatter.format(feeBalance) + '. All balances must be cleared to apply.',
                    source: 'smis'
                });
            }

            // Logic for Caution Refund
            if (typeText.includes('CAUTION')) {
                var displayAmount = cautionRemainingAmount;
                
                if (cautionFeePaid < expectedCautionFee && !overrideEligibility) {
                    warnings.push({
                        message: 'You cannot apply for a Caution Refund because you have not fully paid the CAUTION FEE.',
                        source: 'smis'
                    });
                }

                if (displayAmount <= 0) {
                    warnings.push({
                        message: 'You cannot apply for a Caution Refund because the refundable amount is zero.',
                        source: 'smis/smisportal'
                    });
                }
            }

            if (warnings.length > 0) {
                disableSelection(warnings);
                return;
            }

            if (typeText.includes('CAUTION')) {
                var displayAmount = cautionRemainingAmount;

                // Show dynamic summary for caution
                $('#caution-amount-display').text(currencyFormatter.format(displayAmount));
                $('#caution-reserved-display').text(currencyFormatter.format(cautionReservedAmount));
                
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
        var cautionRemainingAmount = " . (float)$cautionRemainingAmount . ";
        var feeBalance = " . (float)$balance . ";
        var overrideEligibility = " . ($overrideEligibility ? 'true' : 'false') . ";
        var clearanceStatus = {$clearanceStatusJson};
        var academicStatus = {$academicStatusJson};
        var currencyFormatter = new Intl.NumberFormat('en-KE', {
            style: 'currency',
            currency: 'KES',
            minimumFractionDigits: 2
        });
        var appendWarning = function(errorMsg, warning) {
            var source = warning.source || '';
            var badgeColor = source === 'smisportal' ? '#0f766e' : (source === 'smis' ? '#1d4ed8' : '#7c3aed');
            var line = $('<span>').css('display', 'block');
            line.text(warning.message + ' ');
            /*
            if (source) {
                $('<span>')
                    .text(source)
                    .css({
                        'display': 'inline-block',
                        'margin-left': '0.35rem',
                        'padding': '0.1rem 0.4rem',
                        'border-radius': '999px',
                        'background': badgeColor,
                        'color': '#fff',
                        'font-size': '0.68rem',
                        'font-weight': '800',
                        'text-transform': 'uppercase'
                    })
                    .appendTo(line);
            }
            */
            line.appendTo(errorMsg);
        };
        var showWarnings = function(messages) {
            var errorMsg = $('#type-error-msg');
            errorMsg.empty();
            messages.forEach(function(message) {
                appendWarning(errorMsg, message);
            });
            errorMsg.show();
        };

        if (!typeId) {
            $('.dash-refund-type').css('border-color', 'var(--cr-red)').focus();
            $('#type-error-msg').text('Please select a refund type to proceed').show();
            return;
        }

        if (refundedRequestTypeIds[typeId] || activeRequestTypeIds[typeId]) {
            $('#apply-post-type').val(typeId);
            $('#apply-post-form').submit();
            return;
        }

        var warnings = [];
        if (clearanceStatus !== 'CLEARED') {
            warnings.push({
                message: 'You must be CLEARED to apply for this refund type. Current status: ' + clearanceStatus + '.',
                source: 'smisportal'
            });
        }

        if (academicStatus !== 'GRADUATED' && academicStatus !== 'COMPLETED') {
            warnings.push({
                message: 'Refund requests are only available for GRADUATED or COMPLETED students. Your current status: ' + academicStatus + '.',
                source: 'smis'
            });
        }

        if (feeBalance > 0 && !overrideEligibility) {
            warnings.push({
                message: 'You have an outstanding fee balance of ' + currencyFormatter.format(feeBalance) + '. All balances must be cleared to apply.',
                source: 'smis'
            });
        }

        if (typeText.includes('CAUTION')) {
            var displayAmount = cautionRemainingAmount;
            if (cautionFeePaid < expectedCautionFee && !overrideEligibility) {
                warnings.push({
                    message: 'You cannot apply for a Caution Refund because you have not fully paid the CAUTION FEE.',
                    source: 'smis'
                });
            }
            if (displayAmount <= 0) {
                warnings.push({
                    message: 'You cannot apply for a Caution Refund because the refundable amount is zero.',
                    source: 'smis/smisportal'
                });
            }
            $('#apply-post-amount').val(displayAmount);
        }

        if (warnings.length > 0) {
            showWarnings(warnings);
            return;
        }

        $('#apply-post-type').val(typeId);
        $('#apply-post-form').submit();
    });
");
?>

<div class="cr-page">
    <div class="cr-container">
        <nav class="cr-breadcrumb" aria-label="Breadcrumb">
            <span class="cr-breadcrumb__current">Refund Requests</span>
        </nav>
        
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="cr-flash cr-flash--success"><?= Html::encode(Yii::$app->session->getFlash('success')) ?></div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('danger')): ?>
            <div class="cr-flash cr-flash--danger"><?= Html::encode(Yii::$app->session->getFlash('danger')) ?></div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('info')): ?>
            <div class="cr-flash"><?= Html::encode(Yii::$app->session->getFlash('info')) ?></div>
        <?php endif; ?>

        <?php if ($cancelledVoucher): ?>
            <div class="cr-notice cr-notice--warning" style="margin-bottom: 2rem;">
                <p class="cr-notice__title" style="margin:0;">Voucher Cancelled</p>
                <p style="font-size: 0.85rem; margin:0; color: var(--cr-slate-700);">
                    Voucher No. <?= Html::encode($cancelledVoucher['voucher_no'] ?? 'N/A') ?>
                    was cancelled on <?= Html::encode(!empty($cancelledVoucher['date_cancelled']) ? Yii::$app->formatter->asDatetime($cancelledVoucher['date_cancelled']) : 'a recorded date') ?>.
                    <?php if (!empty($cancelledVoucher['remarks'])): ?>
                        Remarks: <?= Html::encode($cancelledVoucher['remarks']) ?>
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- <div class="cr-header">
            <span class="cr-header__badge">National Defence University of Kenya</span>
            <h1 class="cr-header__title">Refund Request</h1>
            <p class="cr-header__sub">
                <?php Html::encode($headerSub) ?>
            </p>
        </div> -->

        <?php if ($mode === 'status'): ?>
            <!-- Mode: ALREADY APPLIED -->
            <div class="cr-notice" style="margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
                <div class="cr-card__header-icon"><i class="fas fa-info" style="color: #fff;"></i></div>
                <div>
                    <p class="cr-notice__title" style="margin:0;">Active Application Found</p>
                    <p style="font-size: 0.85rem; margin:0; color: var(--cr-slate-700);">You have an active refund request submitted on <?= Yii::$app->formatter->asDate($request->application_date) ?>.</p>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($previousRequests)): ?>
            <div class="cr-notice" style="background: var(--cr-teal-50); border-color: var(--cr-teal-200); margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                <div>
                    <p class="cr-notice__title" style="margin:0;">Previous Not Approved Request<?= count($previousRequests) === 1 ? '' : 's' ?> Found</p>
                    <p style="font-size: 0.85rem; margin:0; color: var(--cr-slate-700);">
                        Review your previous request and use Update Request to make the required corrections.
                    </p>
                </div>
                <?= Html::a('View Previous Requests', '#previous-refund-requests', ['class' => 'cr-btn cr-btn--secondary']) ?>
            </div>
        <?php endif; ?>

        <?php if ($showMainRequestCard): ?>
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
                            $isNotApproved = $s === 'NOT APPROVED';
                            $isApproved = !$isCancelled && ($s === 'APPROVED' || $isWorkflowApproved || $isRefunded);
                            $b = $isCancelled ? 'cr-badge--rejected' : ($isApproved ? 'cr-badge--approved' : ($isNotApproved ? 'cr-badge--rejected' : 'cr-badge--pending'));
                            $statusLabel = $isCancelled ? 'CANCELLED' : ($isNotApproved ? 'NOT APPROVED' : ($isApproved ? 'APPROVED' : $s));
                            ?>
                            <span class="cr-badge <?= $b ?>" style="font-size: 0.9rem; padding: 0.3rem 1rem;"><?= Html::encode($statusLabel) ?></span>
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
                        <?= Html::a('View Application Details', ['track', 'request_id' => $request->request_id], ['class' => 'cr-btn cr-btn--primary']) ?>
                    </div>
                <?php else: ?>
                    <?php if (false): // Requirements checklist rows hidden per UI request. ?>
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
                            <?php 
                            $fb = (float)$balance;
                            $ov = (bool)$overrideEligibility;
                            $fbBadge = ($fb <= 0 || $ov) ? 'cr-badge--approved' : 'cr-badge--rejected';
                            $fbLabel = ($fb <= 0) ? 'CLEARED' : ($ov ? 'OVERRIDDEN' : 'HAS BALANCE');
                            ?>
                            <span class="cr-badge <?= $fbBadge ?>"><?= $fbLabel ?> (<?= Yii::$app->formatter->asCurrency($fb) ?>)</span>
                            <small class="ms-2 text-muted" style="font-size: 0.75rem;">(Required: No balance)</small>
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
                            $ov = (bool)$overrideEligibility;
                            $cpBadge = ($cp >= $ex || $ov) ? 'cr-badge--approved' : 'cr-badge--rejected';
                            $cpLabel = ($cp >= $ex) ? 'PAID' : ($ov ? 'OVERRIDDEN' : 'NOT PAID');
                            ?>
                            <span class="cr-badge <?= $cpBadge ?>"><?= $cpLabel ?></span>
                        </span>
                    </div>
                    <?php endif; ?>

                    <div class="cr-section">
                        <?php if ($mode === 'eligibility'): ?>
                            <div class="cr-notice">
                                <p class="cr-notice__title">Select Refund Type</p>
                                <p style="font-size:0.9rem; color:var(--cr-slate-700);">Choose a refund type to check the applicable requirements before proceeding.</p>
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
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem; color: var(--cr-teal-600);">This is the remaining amount after previous requested or approved Caution Refunds.</p>
                                    <p style="margin: 0.25rem 0 0 0; font-size: 0.72rem; color: var(--cr-teal-600);">Already requested or approved: <strong id="caution-reserved-display"></strong></p>
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
        <?php endif; ?>

        <?php if (!empty($previousRequests)): ?>
            <?php $canUpdateRejectedRequest = isset($eligible) && $eligible; ?>
            <div id="previous-refund-requests" class="cr-card" style="margin-top: 2rem;">
                <div class="cr-card__header" style="display: flex; align-items: center;">
                    <div class="cr-card__header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/>
                        </svg>
                    </div>
                    <h2 class="cr-card__title">Previous Requests</h2>
                </div>
                <div class="cr-card__body">
                    <div class="table-responsive cr-table-responsive">
                        <table class="table table-sm align-middle cr-requests-table">
                            <thead>
                            <tr>
                                <th class="cr-col-reference">Reference No</th>
                                <th class="cr-col-type">Refund Type</th>
                                <th class="cr-col-date">Application Date</th>
                                <th class="cr-col-amount text-end">Amount Requested</th>
                                <th class="cr-col-status">Status</th>
                                <th class="cr-col-comment">Comment</th>
                                <th class="cr-col-action">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $latestRejectedRequestId = $previousRequests[0]->request_id ?? null; ?>
                            <?php foreach ($previousRequests as $previousRequest): ?>
                                <?php $rejection = $previousRequest->approvalProcesses[0] ?? null; ?>
                                <tr>
                                    <td class="cr-nowrap">#REF-<?= str_pad($previousRequest->request_id, 5, '0', STR_PAD_LEFT) ?></td>
                                    <td class="cr-nowrap"><?= Html::encode($previousRequest->refundType->displayName ?? $previousRequest->refundType->refund_type_name ?? 'Refund') ?></td>
                                    <td class="cr-nowrap"><?= Yii::$app->formatter->asDate($previousRequest->application_date) ?></td>
                                    <td class="cr-nowrap text-end"><?= Yii::$app->formatter->asCurrency($previousRequest->amount_requested) ?></td>
                                    <td class="cr-nowrap"><span class="cr-badge cr-badge--rejected">NOT APPROVED</span></td>
                                    <td class="cr-nowrap">
                                        <?= Html::button('<i class="fas fa-comment-dots" aria-hidden="true"></i>', [
                                            'type' => 'button',
                                            'class' => 'cr-btn cr-btn--secondary cr-comment-button',
                                            'data-bs-toggle' => 'popover',
                                            'data-bs-trigger' => 'focus',
                                            'data-bs-placement' => 'left',
                                            'title' => 'Not Approval Comment',
                                            'data-bs-content' => Html::encode($rejection->remarks ?? 'No comment provided'),
                                            'aria-label' => 'View not approval comment',
                                        ]) ?>
                                    </td>
                                    <td>
                                        <div class="cr-row-actions">
                                            <?= Html::a('Track', ['track', 'request_id' => $previousRequest->request_id], ['class' => 'cr-btn cr-btn--secondary']) ?>
                                            <?php if ($canUpdateRejectedRequest && (int)$previousRequest->request_id === (int)$latestRejectedRequestId): ?>
                                                <?= Html::a('Update Request', ['apply', 'rejected_request_id' => $previousRequest->request_id], ['class' => 'cr-btn cr-btn--primary']) ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
