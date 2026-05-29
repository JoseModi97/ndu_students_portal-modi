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
?>

<div class="cr-page">
    <div class="cr-container">
        
        <div class="cr-header">
            <span class="cr-header__badge">National Defence University of Kenya</span>
            <h1 class="cr-header__title">Refund Application</h1>
            <p class="cr-header__sub">Please provide your disbursement details below</p>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'refund-requests-form',
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
                <?= $form->field($model, 'refund_type')->hiddenInput(['id' => 'refund-type-input'])->label(false) ?>

                <?= $form->field($model, 'disbursement_method', [
                    'template' => "{label}\n<div class='cr-radio-list'>{input}</div>\n{error}",
                    'labelOptions' => ['class' => 'cr-label--bold']
                ])->radioList(
                    ['bank' => 'Bank Account', 'mpesa' => 'M-PESA'],
                    [
                        'item' => function($index, $label, $name, $checked, $value) {
                            $id = 'disbursement-method-' . $value;
                            $checkStr = $checked ? 'checked' : '';
                            return "
                                <div class='cr-radio-item'>
                                    <input type='radio' name='$name' value='$value' id='$id' $checkStr class='disbursement-method-radio'>
                                    <label for='$id'>$label</label>
                                </div>";
                        }
                    ]
                )->label('Select Disbursement Method') ?>

                <div class="cr-form-grid">
                    <div class="cr-field">
                        <label>Refund Category</label>
                        <?php
                            $typeName = 'Standard';
                            foreach($refundTypes as $rt) {
                                if($rt->refund_type_id == $model->refund_type) {
                                    $typeName = $rt->displayName;
                                    break;
                                }
                            }
                        ?>
                        <input type="text" class="form-control" value="<?= Html::encode($typeName) ?>" readonly>
                    </div>
                    <div class="cr-field">
                        <label>Registration Number</label>
                        <input type="text" class="form-control" value="<?= Html::encode($regNumber) ?>" readonly>
                    </div>
                    <div class="cr-form-grid--full">
                        <?= $form->field($model, 'amount_requested')->textInput(['type' => 'number', 'step' => '0.01', 'placeholder' => 'Enter amount (e.g. 5000)']) ?>
                    </div>
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
                            'data' => ArrayHelper::map($banks, 'brank_id', 'bank_name'),
                            'options' => [
                                'placeholder' => 'Select Bank',
                                'id' => 'bank-selector'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]) ?>
                    </div>
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
                <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => true, 'placeholder' => '07XXXXXXXX']) ?>
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
                <h2 class="cr-card__title">Declaration</h2>
            </div>
            <div class="cr-card__body">
                <p style="font-size: 0.9rem; color: var(--cr-slate-600);">By submitting this form, I declare that the information provided is true and accurate to the best of my knowledge.</p>
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
function toggleRefundFields(value) {
    if (value == 'mpesa') {
        $('#standard-fields').hide();
        $('#chss-fields').show();
    } else {
        $('#standard-fields').show();
        $('#chss-fields').hide();
    }
}

// Initial toggle
toggleRefundFields($('.disbursement-method-radio:checked').val());

// Handle change
$('.disbursement-method-radio').on('change', function() {
    toggleRefundFields($(this).val());
});

// Load branches dynamically
$('#bank-selector').on('change', function() {
    var bankId = $(this).val();
    var branchSelector = $('#branch-selector');
    
    branchSelector.val(null).trigger('change');
    branchSelector.empty();
    
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
        });
    }
});
JS;
$this->registerJs($js);
?>
