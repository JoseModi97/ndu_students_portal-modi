<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\modules\caution_refund\models\CautionRefund $model */
/** @var app\modules\caution_refund\models\Bank[] $banks */

$this->title = 'Apply for Caution Refund';
$this->registerCssFile('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');
$this->registerCssFile('@web/css/caution-refund.css');

$fieldConfig = [
    'options' => ['class' => 'cr-field'],
    'template' => "{label}\n{input}\n{error}",
    'labelOptions' => ['class' => null],
    'errorOptions' => ['class' => 'cr-error'],
];
?>

<div class="cr-page">
    <div class="cr-container">
        
        <div class="cr-header">
            <span class="cr-header__badge">National Defence University of Kenya</span>
            <h1 class="cr-header__title">Refund Application</h1>
            <p class="cr-header__sub">Please provide your disbursement details below</p>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'caution-refund-form',
            'fieldConfig' => $fieldConfig,
        ]); ?>

        <div class="cr-card">
            <div class="cr-card__header">
                <div class="cr-card__header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                    </svg>
                </div>
                <h2 class="cr-card__title">Refund Mode & Amount</h2>
            </div>
            <div class="cr-card__body">
                <div class="cr-notice">
                    <p class="cr-notice__title">Selection</p>
                    <div class="cr-field">
                        <?= $form->field($model, 'refund_type', ['template' => '{input}'])->radioList(
                            ['STANDARD' => 'Standard (Bank Transfer)', 'CHSS' => 'CHSS (M-PESA)'],
                            [
                                'item' => function($index, $label, $name, $checked, $value) {
                                    $id = 'mode-' . strtolower($value);
                                    $checkedAttr = $checked ? 'checked' : '';
                                    $icon = $value === 'CHSS' ? 'fa-mobile-alt' : 'fa-university';
                                    return "
                                        <div class=\"form-check form-check-inline\" style=\"margin-right: 2rem;\">
                                            <input type=\"radio\" class=\"form-check-input\" name=\"$name\" id=\"$id\" value=\"$value\" $checkedAttr>
                                            <label class=\"form-check-label\" for=\"$id\">$label</label>
                                        </div>
                                    ";
                                },
                            ]
                        ) ?>
                    </div>
                </div>

                <div class="cr-form-grid">
                    <?= $form->field($model, 'registration_number')->textInput(['readonly' => true]) ?>
                    <?= $form->field($model, 'refund_amount')->textInput(['type' => 'number', 'step' => '0.01', 'placeholder' => 'Enter amount (e.g. 5000)']) ?>
                </div>
            </div>
        </div>

        <!-- Standard Mode Fields -->
        <div id="standard-fields" class="cr-card">
            <div class="cr-card__header">
                <div class="cr-card__header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3"/>
                    </svg>
                </div>
                <h2 class="cr-card__title">Bank Account Details</h2>
            </div>
            <div class="cr-card__body">
                <div class="cr-form-grid">
                    <div class="cr-field">
                        <label>Bank <span class="required-star">*</span></label>
                        <?= Select2::widget([
                            'name' => 'bank_id',
                            'data' => ArrayHelper::map($banks, 'bank_id', 'bank_name'),
                            'options' => [
                                'placeholder' => 'Select Bank',
                                'id' => 'bank-selector'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]) ?>
                        <p class="cr-error" id="bank-error" style="display:none;"></p>
                    </div>
                    <?= $form->field($model, 'bank_branch_id')->widget(Select2::class, [
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
                        <?= $form->field($model, 'account_number')->textInput(['maxlength' => true, 'placeholder' => 'Enter bank account number']) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHSS Mode Fields -->
        <div id="chss-fields" class="cr-card" style="display: none;">
            <div class="cr-card__header">
                <div class="cr-card__header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>
                    </svg>
                </div>
                <h2 class="cr-card__title">M-PESA Details</h2>
            </div>
            <div class="cr-card__body">
                <?= $form->field($model, 'mobile_number')->textInput(['maxlength' => true, 'placeholder' => '07XXXXXXXX']) ?>
                <p style="font-size:0.8rem; color:var(--cr-slate-400); margin-top:0.5rem;">Ensure the number is registered in your name as per University records.</p>
            </div>
        </div>

        <div class="cr-card">
            <div class="cr-card__header">
                <div class="cr-card__header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <h2 class="cr-card__title">Additional Information</h2>
            </div>
            <div class="cr-card__body">
                <?= $form->field($model, 'remarks')->textarea(['rows' => 3, 'placeholder' => 'Any additional information...']) ?>
            </div>
        </div>

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
$js = <<<JS
// Toggle fields based on mode
$('input[name="CautionRefund[refund_type]"]').on('change', function() {
    if ($(this).val() === 'CHSS') {
        $('#standard-fields').hide();
        $('#chss-fields').show();
        // Clear bank/account info when switching to CHSS
        $('#bank-selector').val(null).trigger('change');
        $('#branch-selector').val(null).trigger('change');
        $('#cautionrefund-account_number').val('');
    } else {
        $('#standard-fields').show();
        $('#chss-fields').hide();
        // Clear mobile info when switching to Standard
        $('#cautionrefund-mobile_number').val('');
    }
}).filter(':checked').trigger('change');

// Load branches dynamically
$('#bank-selector').on('change', function() {
    var bankId = $(this).val();
    var branchSelector = $('#branch-selector');
    
    // Clear the branch selector
    branchSelector.val(null).trigger('change');
    branchSelector.empty();
    
    if (bankId) {
        // Clear bank error if it exists
        $('#caution-refund-form').yiiActiveForm('updateAttribute', 'cautionrefund-bank_branch_id', '');
        
        $.get('$branchUrl', {bankId: bankId}, function(data) {
            var options = [];
            $.each(data, function(index, branch) {
                options.push({id: branch.branch_id, text: branch.branch_name});
            });
            
            // Re-populate Select2
            branchSelector.select2({
                data: options,
                placeholder: 'Select Branch',
                allowClear: true,
                theme: 'krajee-bs5',
                width: '100%'
            });
        });
    } else {
        branchSelector.select2({
            data: [],
            placeholder: 'Select Branch',
            allowClear: true,
            theme: 'krajee-bs5',
            width: '100%'
        });
    }
});
JS;
$this->registerJs($js);
?>


