	<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\modules\refund_requests\models\RefundRequest $model */
/** @var app\modules\refund_requests\models\Bank[] $banks */
/** @var app\modules\refund_requests\models\RefundType[] $refundTypes */
/** @var string $regNumber */
/** @var app\modules\refund_requests\models\RefundRequest|null $rejectedRequest */
/** @var app\modules\refund_requests\models\ApprovalProcess|null $latestRejection */
/** @var bool $isPostingRejection */
/** @var array|null $refundedRequestDetails */
/** @var array|null $activeRequestDetails */

$this->title = 'Apply for Refund Request';
$this->registerCssFile('@web/css/refund-requests.css');

$refundIndexUrl = Url::to(['index']);
$refreshRedirectJs = <<<JS
(function () {
    var navigationEntries = window.performance && window.performance.getEntriesByType
        ? window.performance.getEntriesByType('navigation')
        : [];
    var isReload = navigationEntries.length
        ? navigationEntries[0].type === 'reload'
        : window.performance
            && window.performance.navigation
            && window.performance.navigation.type === 1;

    if (isReload) {
        window.location.replace('$refundIndexUrl');
    }
})();
JS;
$this->registerJs($refreshRedirectJs, yii\web\View::POS_HEAD);

$fieldConfig = [
    'options' => ['class' => 'cr-field'],
    'template' => "{label}\n{input}\n{error}",
    'labelOptions' => ['class' => null],
    'errorOptions' => ['class' => 'cr-error'],
    'validateOnChange' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
];

$refundedRequestDetails = $refundedRequestDetails ?? null;
$activeRequestDetails = $activeRequestDetails ?? null;
$selectedRefundTypeLabel = 'N/A';
foreach ($refundTypes as $type) {
    if ((string)$type->refund_type_id === (string)$model->refund_type) {
        $selectedRefundTypeLabel = $type->displayName;
        break;
    }
}
$selectedBranchData = [];
if ($model->branch_id && $model->branch) {
    $selectedBranchData = [$model->branch_id => $model->branch->branch_name];
}
$reinstatementLevelLabel = !empty($isPostingRejection) ? 'posting' : 'Level 1 approval';
$hasRefundedDetails = !empty($refundedRequestDetails);
$hasActiveRequestDetails = !empty($activeRequestDetails);
$headerSub = $hasRefundedDetails
    ? 'Refund Completed'
    : ($hasActiveRequestDetails ? 'Application Tracking' : 'Please provide your disbursement details below');
$breadcrumbActionLabel = $rejectedRequest
    ? 'Update Request'
    : ($hasRefundedDetails ? 'Refund Completed' : ($hasActiveRequestDetails ? 'Application Summary' : 'Apply'));
$breadcrumbTypeLabel = $selectedRefundTypeLabel !== 'N/A' ? $selectedRefundTypeLabel : 'Refund Type';
?>

<div class="cr-page">
    <div class="cr-container">
        <nav class="cr-breadcrumb" aria-label="Breadcrumb">
            <?= Html::a('Refund Requests', ['index']) ?>
            <span class="cr-breadcrumb__separator">/</span>
            <span class="cr-breadcrumb__item"><?= Html::encode($breadcrumbTypeLabel) ?></span>
            <span class="cr-breadcrumb__separator">/</span>
            <span class="cr-breadcrumb__current"><?= Html::encode($breadcrumbActionLabel) ?></span>
        </nav>
        
        <!-- <div class="cr-header">
            <span class="cr-header__badge">National Defence University of Kenya</span>
            <h1 class="cr-header__title">Refund Application</h1>
            <p class="cr-header__sub">
                <?php Html::encode($headerSub) ?>
            </p>
            <div class="cr-header__meta">
                <span>Payment Refund Type</span>
                <strong><?= Html::encode($selectedRefundTypeLabel) ?></strong>
            </div>
	    </div> -->
	
        <?php if ($hasRefundedDetails): ?>
            <div class="cr-card" style="margin-bottom: 2rem; border-color: var(--cr-teal-400);">
                <div class="cr-card__header" style="display: flex; align-items: center;">
                    <div class="cr-card__header-icon" style="background: var(--cr-teal-400);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6L9 17l-5-5"/><path d="M21 12a9 9 0 1 1-3.2-6.9"/>
                        </svg>
                    </div>
                    <h2 class="cr-card__title" style="color: var(--cr-teal-800);">Refund Completed</h2>
                </div>
                <div class="cr-card__body">
                    <div class="cr-notice" style="background: var(--cr-teal-50); border-color: var(--cr-teal-400); margin-bottom: 1.5rem;">
                        <p class="cr-notice__title" style="color: var(--cr-teal-800);">You have been refunded.</p>
                        <p style="font-size:0.9rem; color:var(--cr-slate-700); margin: 0;">
                            Your <?= Html::encode($refundedRequestDetails['refundType'] ?? $selectedRefundTypeLabel) ?> request has been processed and marked as refunded by Finance.
                        </p>
                    </div>

                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Current Status</span>
                        <span class="cr-status-row__value"><span class="cr-badge cr-badge--approved">REFUNDED</span></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Reference No</span>
                        <span class="cr-status-row__value"><?= Html::encode($refundedRequestDetails['referenceNo'] ?? '') ?></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Refund Type</span>
                        <span class="cr-status-row__value"><span class="badge bg-info text-dark" style="font-weight: 700;"><?= Html::encode($refundedRequestDetails['refundType'] ?? $selectedRefundTypeLabel) ?></span></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Amount Refunded</span>
                        <span class="cr-status-row__value"><strong><?= Yii::$app->formatter->asCurrency((float)($refundedRequestDetails['amount'] ?? 0)) ?></strong></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Voucher No</span>
                        <span class="cr-status-row__value"><?= Html::encode($refundedRequestDetails['voucherNo'] ?? 'Not recorded') ?></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Payment Mode</span>
                        <span class="cr-status-row__value">
                            <span class="badge bg-success mb-1"><?= Html::encode($refundedRequestDetails['paymentLabel'] ?? 'Payment Method') ?></span>
                            <?php if (!empty($refundedRequestDetails['paymentDetail'])): ?>
                                <br><span style="font-size: 0.85rem; color: var(--cr-slate-400);"><?= Html::encode($refundedRequestDetails['paymentDetail']) ?></span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="cr-form-actions" style="margin-top:2rem;">
                        <?= Html::a('Back to Refund Requests', ['index'], ['class' => 'cr-btn cr-btn--secondary']) ?>
                        <?= Html::a('View Refund Details', ['track', 'request_id' => (int)($refundedRequestDetails['requestId'] ?? 0)], ['class' => 'cr-btn cr-btn--primary']) ?>
                    </div>
                </div>
            </div>
        <?php elseif ($hasActiveRequestDetails): ?>
            <?php
            $activeStatus = strtoupper((string)($activeRequestDetails['statusLabel'] ?? 'PENDING'));
            $activeBadgeClass = $activeStatus === 'APPROVED' ? 'cr-badge--approved' : 'cr-badge--pending';
            ?>
            <div class="cr-card" style="margin-bottom: 2rem;">
                <div class="cr-card__header" style="display: flex; align-items: center;">
                    <div class="cr-card__header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <h2 class="cr-card__title">Application Summary</h2>
                </div>
                <div class="cr-card__body">
                    <div class="cr-notice" style="background: var(--cr-blue-50); border-color: var(--cr-blue-400); margin-bottom: 1.5rem;">
                        <p class="cr-notice__title">Active Application Found</p>
                        <p style="font-size:0.9rem; color:var(--cr-slate-700); margin: 0;">
                            Your <?= Html::encode($activeRequestDetails['refundType'] ?? $selectedRefundTypeLabel) ?> request has already been submitted and is being processed.
                        </p>
                    </div>

                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Current Status</span>
                        <span class="cr-status-row__value"><span class="cr-badge <?= $activeBadgeClass ?>"><?= Html::encode($activeStatus) ?></span></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Refund Type</span>
                        <span class="cr-status-row__value"><span class="badge bg-info text-dark" style="font-weight: 700;"><?= Html::encode($activeRequestDetails['refundType'] ?? $selectedRefundTypeLabel) ?></span></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Reference No</span>
                        <span class="cr-status-row__value"><?= Html::encode($activeRequestDetails['referenceNo'] ?? '') ?></span>
                    </div>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label"><?= Html::encode($activeRequestDetails['amountLabel'] ?? 'Requested Amount') ?></span>
                        <span class="cr-status-row__value"><strong><?= Yii::$app->formatter->asCurrency((float)($activeRequestDetails['amount'] ?? 0)) ?></strong></span>
                    </div>
                    <?php if (!empty($activeRequestDetails['voucherNo'])): ?>
                        <div class="cr-status-row">
                            <span class="cr-status-row__label">Voucher No</span>
                            <span class="cr-status-row__value"><?= Html::encode($activeRequestDetails['voucherNo']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="cr-status-row">
                        <span class="cr-status-row__label">Payment Mode</span>
                        <span class="cr-status-row__value">
                            <span class="badge bg-secondary mb-1"><?= Html::encode($activeRequestDetails['paymentLabel'] ?? 'Payment Method') ?></span>
                            <?php if (!empty($activeRequestDetails['paymentDetail'])): ?>
                                <br><span style="font-size: 0.85rem; color: var(--cr-slate-400);"><?= Html::encode($activeRequestDetails['paymentDetail']) ?></span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php if (!empty($activeRequestDetails['applicationDate'])): ?>
                        <div class="cr-status-row">
                            <span class="cr-status-row__label">Application Date</span>
                            <span class="cr-status-row__value"><?= Yii::$app->formatter->asDate($activeRequestDetails['applicationDate']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="cr-form-actions" style="margin-top:2rem;">
                        <?= Html::a('Back to Refund Requests', ['index'], ['class' => 'cr-btn cr-btn--secondary']) ?>
                        <?= Html::a('View Application Details', ['track', 'request_id' => (int)($activeRequestDetails['requestId'] ?? 0)], ['class' => 'cr-btn cr-btn--primary']) ?>
                    </div>
                </div>
            </div>
        <?php else: ?>

        <?php $form = ActiveForm::begin([
            'id' => 'refund-requests-form',
            'fieldConfig' => $fieldConfig,
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
        ]); ?>

        <?php if ($rejectedRequest): ?>
            <div class="cr-flash" style="margin-bottom: 1.5rem; border-color: #f59e0b; background: #fffbeb; color: #92400e;">
                <p style="font-weight: 800; margin-bottom: 0.5rem; text-transform: uppercase; font-size: 0.85rem;">Update Rejected Request</p>
                <p style="margin-bottom: 0.35rem;">
                    You are updating request <strong>#REF-<?= str_pad((string)$rejectedRequest->request_id, 5, '0', STR_PAD_LEFT) ?></strong>.
                    Submitting this form will return the same request to <?= Html::encode($reinstatementLevelLabel) ?>.
                </p>
                <p style="margin-bottom: 0;">
                    <strong>Latest rejection comment:</strong>
                    <?= Html::encode($latestRejection->remarks ?? 'No comment provided') ?>
                </p>
            </div>
            <?= Html::hiddenInput('rejected_request_id', $rejectedRequest->request_id) ?>
        <?php endif; ?>

        <div id="validation-error-summary" class="cr-flash cr-flash--danger" style="display: none; margin-bottom: 2rem;">
            <p style="font-weight: 800; margin-bottom: 0.5rem; text-transform: uppercase; font-size: 0.85rem;">Please correct the following errors:</p>
            <div class="error-list"></div>
        </div>

        <?= Html::activeHiddenInput($model, 'refund_type') ?>
        <?= Html::activeHiddenInput($model, 'amount_requested', ['title' => 'Autofilled Amount Requested']) ?>

        <div class="cr-card">
            <div class="cr-card__header">
                <div class="cr-card__header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3"/>
                    </svg>
                </div>
                <h2 class="cr-card__title">Disbursement Details</h2>
            </div>
            <div class="cr-card__body">
                <?= $form->field($model, 'payment_method', [
                    'template' => "{label}\n<div class='cr-radio-list'>{input}</div>\n{error}",
                    'labelOptions' => ['class' => 'cr-label--bold'],
                ])->radioList(
                    ['bank' => 'Bank Account Details', 'mpesa' => 'M-PESA'],
                    [
                        'item' => function($index, $label, $name, $checked, $value) {
                            $id = 'payment-option-' . $value;
                            $checkedAttr = $checked ? 'checked' : '';
                            return "
                                <div class='cr-radio-item'>
                                    <input type='radio' name='$name' value='$value' id='$id' class='payment-option-radio' $checkedAttr>
                                    <label for='$id'>$label</label>
                                </div>";
                        },
                    ]
                )->label('Select Payment Option') ?>

                <div id="bank-details-fields" class="cr-form-grid">
                    <?= $form->field($model, 'bank_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map($banks, 'brank_id', 'bank_name'),
                        'options' => [
                            'placeholder' => 'Select Bank',
                            'id' => 'bank-selector'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]) ?>
                    <?= $form->field($model, 'branch_id')->widget(Select2::class, [
                        'data' => $selectedBranchData,
                        'options' => [
                            'placeholder' => 'Select Branch',
                            'id' => 'branch-selector'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]) ?>
                    <div class="cr-form-grid--full">
                        <?= $form->field($model, 'account_no')->textInput(['maxlength' => true, 'placeholder' => 'Enter bank account number']) ?>
                    </div>
                </div>

                <div id="mpesa-details-fields" style="display: none;">
                    <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => true, 'placeholder' => '07XXXXXXXX']) ?>
                    <p style="font-size:0.8rem; color:var(--cr-slate-400); margin-top:0.5rem;">Ensure the number is registered in your name as per University records.</p>
                </div>

                <div style="border-top: 1px solid var(--cr-blue-50); margin-top: 2rem; padding-top: 1.5rem;">
                    <div class="cr-card__header" style="padding: 0; border-bottom: 0; margin-bottom: 1rem;">
                        <div class="cr-card__header-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                        </div>
                        <h2 class="cr-card__title">Declaration</h2>
                    </div>
                    <div class="cr-declaration">
                        <?= $form->field($model, 'declaration_status', [
                            'template' => "{input}{label}\n{error}",
                            'options' => ['class' => ''],
                        ])->checkbox([
                            'value' => '1',
                            'uncheck' => '0',
                            'label' => 'I hereby declare that the information provided in this application is true, accurate, and complete to the best of my knowledge. I understand that any false or misleading information provided may lead to the rejection of this application, forfeiture of the refund, or disciplinary action by the University according to the student code of conduct.',
                            'labelOptions' => ['style' => 'font-size: 0.87rem; line-height: 1.6; color: var(--cr-slate-700); cursor: pointer; margin: 0;'],
                        ]) ?>
                    </div>
                </div>

                <div class="cr-form-actions" style="margin-top:1.5rem;">
                    <?= Html::a('← Cancel', ['index'], [
                        'style' => 'display:inline-flex; align-items:center; padding:.65rem 1.5rem; font-family:var(--cr-font); font-size:.88rem; font-weight:700; border-radius:999px; border:1.5px solid var(--cr-blue-200); color:var(--cr-blue-600); background:transparent; text-decoration:none;'
                    ]) ?>
                    <?= Html::submitButton('Submit Application', [
                        'class' => 'cr-btn cr-btn--primary',
                        'id' => 'submit-application-button',
                    ]) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
        <?php endif; ?>
    </div>
</div>

	<?php if (!$hasRefundedDetails && !$hasActiveRequestDetails): ?>
	<?php
	$branchUrl = Url::to(['branches']);

$js = <<<JS
var refundForm = $('#refund-requests-form');
var submitApplicationRequested = false;
var refundFormElement = document.getElementById('refund-requests-form');

// Stop change handlers, Select2, and Enter presses from starting whole-form
// validation. Only the explicit submit button may begin submission.
if (refundFormElement) {
    refundFormElement.addEventListener('submit', function(event) {
        if (!submitApplicationRequested) {
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    }, true);
}

$('#submit-application-button').on('click', function() {
    submitApplicationRequested = true;
});

// A refund application may only be submitted through the explicit action
// button. This blocks implicit Enter/Select2 and unrelated change submissions.
refundForm.on('beforeSubmit', function() {
    if (!submitApplicationRequested) {
        return false;
    }

    submitApplicationRequested = false;
    return true;
});

function togglePaymentOptionFields(value) {
    var form = refundForm;
    if (value === 'mpesa') {
        $('#bank-details-fields').hide();
        $('#mpesa-details-fields').show();
        
        // Clear bank errors
        form.yiiActiveForm('updateAttribute', 'refundrequest-bank_id', '');
        form.yiiActiveForm('updateAttribute', 'refundrequest-branch_id', '');
        form.yiiActiveForm('updateAttribute', 'refundrequest-account_no', '');
        
        $('#bank-selector').val(null).trigger('change');
        $('#branch-selector').val(null).trigger('change');
        $('#refundrequest-account_no').val('');
    } else if (value === 'bank') {
        $('#bank-details-fields').show();
        $('#mpesa-details-fields').hide();
        
        // Clear mpesa errors
        form.yiiActiveForm('updateAttribute', 'refundrequest-mobile_no', '');
        $('#refundrequest-mobile_no').val('');
    }
    
    // Hide summary if it was showing
    $('#validation-error-summary').hide();
}

togglePaymentOptionFields($('.payment-option-radio:checked').val());

$('.payment-option-radio').on('change', function() {
    togglePaymentOptionFields($(this).val());
});

// Load branches dynamically
$('#bank-selector').on('change', function(e) {
    e.stopPropagation(); // Prevent auto-submit if a parent listener exists
    
    var bankId = $(this).val();
    var branchSelector = $('#branch-selector');
    
    branchSelector
        .empty()
        .append(new Option('Select Branch', '', true, false))
        .prop('disabled', !bankId)
        .trigger('change.select2');
    
    if (bankId) {
        branchSelector
            .empty()
            .append(new Option('Loading branches...', '', true, false))
            .prop('disabled', true)
            .trigger('change.select2');

        $.getJSON('$branchUrl', {bankId: bankId})
        .done(function(data) {
            branchSelector.empty();

            if (!data.length) {
                branchSelector
                    .append(new Option('No branches available for this bank', '', true, false))
                    .prop('disabled', true)
                    .trigger('change.select2');
                return;
            }

            branchSelector.append(new Option('Select Branch', '', true, false));
            $.each(data, function(index, branch) {
                branchSelector.append(new Option(branch.branch_name, branch.branch_id, false, false));
            });

            branchSelector
                .prop('disabled', false)
                .trigger('change.select2');
        })
        .fail(function() {
            branchSelector
                .empty()
                .append(new Option('Unable to load branches. Please try again.', '', true, false))
                .prop('disabled', true)
                .trigger('change.select2');
        });
    }
});

$('#branch-selector').on('change', function(e) {
    e.stopPropagation(); // Prevent auto-submit on branch selection
});

$('#refund-requests-form').on('afterValidate', function (event, messages, errorAttributes) {
    var summary = $('#validation-error-summary');
    var list = summary.find('.error-list');
    
    if (errorAttributes.length > 0) {
        submitApplicationRequested = false;
        var errorHtml = '<ul style=\"margin-bottom: 0; padding-left: 1.5rem;\">';
        $.each(messages, function(field, errors) {
            if (errors.length > 0) {
                var label = $('label[for=\"' + field + '\"]').text().replace('*', '').trim() || field;
                
                // Make declaration label more user-friendly and concise
                if (field === 'refundrequest-declaration_status') {
                    label = 'Declaration Confirmation';
                }
                
                errorHtml += '<li style=\"font-size: 0.88rem; margin-bottom: 0.25rem;\"><b>' + label + '</b>: ' + errors[0] + '</li>';
            }
        });
        errorHtml += '</ul>';

        list.html(errorHtml);
        summary.fadeIn();
        
        $('html, body').animate({
            scrollTop: summary.offset().top - 100
        }, 500);
    } else {
        summary.hide();
    }
});
JS;
	$this->registerJs($js);
	?>
    <?php endif; ?>
