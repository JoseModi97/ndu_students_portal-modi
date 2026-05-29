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

$this->title = 'Apply for Refund Request';
$this->registerCssFile('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');
$this->registerCssFile('@web/css/refund-requests.css');

$fieldConfig = [
    'options' => ['class' => 'cr-field'],
    'template' => "{label}\n{input}\n{error}",
    'labelOptions' => ['class' => null],
    'errorOptions' => ['class' => 'cr-error'],
];

$selectedRefundTypeLabel = 'N/A';
foreach ($refundTypes as $type) {
    if ((string)$type->refund_type_id === (string)$model->refund_type) {
        $selectedRefundTypeLabel = $type->displayName;
        break;
    }
}
?>

<div class="cr-page">
    <div class="cr-container">
        
        <div class="cr-header">
            <span class="cr-header__badge">National Defence University of Kenya</span>
            <h1 class="cr-header__title">Refund Application</h1>
            <p class="cr-header__sub">Please provide your disbursement details below</p>
            <div class="cr-header__meta">
                <span>Payment Refund Type</span>
                <strong><?= Html::encode($selectedRefundTypeLabel) ?></strong>
            </div>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'refund-requests-form',
            'fieldConfig' => $fieldConfig,
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
        ]); ?>

        <div id="validation-error-summary" class="cr-flash cr-flash--danger" style="display: none; margin-bottom: 2rem;">
            <p style="font-weight: 800; margin-bottom: 0.5rem; text-transform: uppercase; font-size: 0.85rem;">Please correct the following errors:</p>
            <div class="error-list"></div>
        </div>

        <div class="cr-card">
            <div class="cr-card__header">
                <div class="cr-card__header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                    </svg>
                </div>
                <h2 class="cr-card__title">Refund Type & Amount</h2>
            </div>
            <div class="cr-card__body">
                <?= Html::activeHiddenInput($model, 'refund_type') ?>

                <div class="cr-form-grid">
                    <div class="cr-field">
                        <label>Registration Number</label>
                        <input type="text" class="form-control" value="<?= Html::encode($regNumber) ?>" readonly>
                    </div>
                    <?= $form->field($model, 'amount_requested')->textInput(['type' => 'number', 'step' => '0.01', 'placeholder' => 'Enter amount (e.g. 5000)']) ?>
                    <?= $form->field($model, 'voucher_no')->textInput(['type' => 'number', 'placeholder' => 'Voucher No. (Optional)']) ?>
                </div>
            </div>
        </div>

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
                        'data' => [],
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
            </div>
        </div>

        <div class="cr-card">
            <div class="cr-card__header">
                <div class="cr-card__header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <h2 class="cr-card__title">Declaration</h2>
            </div>
            <div class="cr-card__body">
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
        </div>

        <details class="cr-sql-preview">
            <summary>SQL Preview</summary>
            <div class="cr-sql-preview__body">
                <div class="cr-sql-preview__section">
                    <h3>Fetch Data</h3>
                    <pre id="sql-fetch-preview"></pre>
                </div>
                <div class="cr-sql-preview__section">
                    <h3>On Submit</h3>
                    <pre id="sql-submit-preview"></pre>
                </div>
            </div>
        </details>

        <div style="display:flex; gap:1rem; align-items:center; justify-content:center; margin-top:1.5rem; margin-bottom:3rem;">
            <?= Html::a('← Cancel', ['index'], [
                'style' => 'display:inline-flex; align-items:center; padding:.65rem 1.5rem; font-family:var(--cr-font); font-size:.88rem; font-weight:700; border-radius:999px; border:1.5px solid var(--cr-blue-200); color:var(--cr-blue-600); background:transparent; text-decoration:none;'
            ]) ?>
            <?= Html::submitButton('Submit Application', ['class' => 'cr-btn cr-btn--primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$branchUrl = Url::to(['branches']);
$studentProgCurriculumId = (int)$model->student_prog_curriculum_id;
$refundTypeId = (int)$model->refund_type;
$defaultEmail = \yii\helpers\Json::htmlEncode($model->email);
$defaultPassportId = \yii\helpers\Json::htmlEncode($model->passport_id);
$defaultAccountName = \yii\helpers\Json::htmlEncode($model->account_name);
$js = <<<JS
function togglePaymentOptionFields(value) {
    var form = $('#refund-requests-form');
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

var sqlPreviewDefaults = {
    studentProgCurriculumId: $studentProgCurriculumId,
    refundTypeId: $refundTypeId,
    email: $defaultEmail,
    passportId: $defaultPassportId,
    accountName: $defaultAccountName
};

function sqlValue(value) {
    if (value === null || value === undefined || value === '') {
        return 'NULL';
    }

    return "'" + String(value).replace(/'/g, "''") + "'";
}

function sqlNumber(value) {
    if (value === null || value === undefined || value === '') {
        return 'NULL';
    }

    return value;
}

function updateSqlPreview() {
    var paymentOption = $('.payment-option-radio:checked').val() || 'bank';
    var bankId = $('#bank-selector').val();
    var branchId = $('#branch-selector').val();
    var accountNo = $('#refundrequest-account_no').val();
    var mobileNo = $('#refundrequest-mobile_no').val();
    var amountRequested = $('#refundrequest-amount_requested').val();
    var voucherNo = $('#refundrequest-voucher_no').val();
    var declarationStatus = $('#refundrequest-declaration_status').is(':checked') ? '1' : '0';
    var isBank = paymentOption === 'bank';

    var fetchSql = [
        '-- Refund type selected on the previous page',
        'SELECT *',
        'FROM smisportal.fss_refund_types',
        'WHERE refund_type_status = TRUE',
        '  AND refund_type_id = ' + sqlPreviewDefaults.refundTypeId + ';',
        '',
        '-- Banks shown for Bank Account Details',
        'SELECT *',
        'FROM smisportal.fss_banks',
        'ORDER BY bank_name;'
    ];

    if (isBank) {
        fetchSql.push(
            '',
            '-- Branches fetched after selecting a bank',
            'SELECT bb.*',
            'FROM smisportal.fss_bank_branches bb',
            'INNER JOIN smisportal.fss_banks b ON b.bank_code = bb.bank_code',
            'WHERE b.brank_id = ' + sqlNumber(bankId) + ';'
        );
    }

    var submitSql = [
        '-- Portal insert',
        'INSERT INTO smisportal.fss_refund_requests (',
        '    request_id, student_prog_curriculum_id, mobile_no, email, application_date,',
        '    refund_status, account_name, bank_id, branch_id, account_no,',
        '    passport_id, declaration_status, amount_requested, approval_status, refund_type, payment_method, voucher_no',
        ') VALUES (',
        '    :next_request_id, ' + sqlPreviewDefaults.studentProgCurriculumId + ', ' + sqlValue(mobileNo) + ', ' + sqlValue(sqlPreviewDefaults.email) + ', NOW(),',
        "    'PENDING', " + sqlValue(sqlPreviewDefaults.accountName) + ', ' + (isBank ? sqlNumber(bankId) : 'NULL') + ', ' + (isBank ? sqlNumber(branchId) : 'NULL') + ', ' + (isBank ? sqlValue(accountNo) : 'NULL') + ',',
        '    ' + sqlValue(sqlPreviewDefaults.passportId) + ', ' + declarationStatus + ', ' + sqlNumber(amountRequested) + ", 'PENDING', " + sqlPreviewDefaults.refundTypeId + ', ' + sqlValue(paymentOption) + ', ' + sqlNumber(voucherNo),
        ');',
        '',
        '-- SMIS sync insert uses the saved portal values',
        'INSERT INTO smis.fss_refund_requests (...)',
        'SELECT ...',
        'FROM smisportal.fss_refund_requests',
        'WHERE request_id = :next_request_id;'
    ];

    $('#sql-fetch-preview').text(fetchSql.join('\\n'));
    $('#sql-submit-preview').text(submitSql.join('\\n'));
}

togglePaymentOptionFields($('.payment-option-radio:checked').val());
updateSqlPreview();

$('.payment-option-radio').on('change', function() {
    togglePaymentOptionFields($(this).val());
    updateSqlPreview();
});

$('#refundrequest-account_no, #refundrequest-mobile_no, #refundrequest-amount_requested, #refundrequest-voucher_no, #refundrequest-declaration_status, #branch-selector').on('input change', updateSqlPreview);

// Load branches dynamically
$('#bank-selector').on('change', function(e) {
    e.stopPropagation(); // Prevent auto-submit if a parent listener exists
    
    var bankId = $(this).val();
    var branchSelector = $('#branch-selector');
    
    // Clear and reset branch selector
    branchSelector.val(null).trigger('change');
    branchSelector.empty();
    updateSqlPreview();
    
    if (bankId) {
        $.get('$branchUrl', {bankId: bankId}, function(data) {
            var options = [];
            $.each(data, function(index, branch) {
                options.push({id: branch.branch_id, text: branch.branch_name});
            });
            
            branchSelector.select2({
                data: options,
                placeholder: 'Select Branch',
                allowClear: true,
                theme: 'krajee-bs5',
                width: '100%'
            });
            updateSqlPreview();
        });
    }
});

$('#branch-selector').on('change', function(e) {
    e.stopPropagation(); // Prevent auto-submit on branch selection
    updateSqlPreview();
});

$('#refund-requests-form').on('afterValidate', function (event, messages, errorAttributes) {
    var summary = $('#validation-error-summary');
    var list = summary.find('.error-list');
    
    if (errorAttributes.length > 0) {
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
