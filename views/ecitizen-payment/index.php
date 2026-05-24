<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/**
 * @var yii\web\View $this
 * @var app\models\forms\EcitizenPaymentForm $model
 * @var array $studentContext
 * @var bool $paymentModeReady
 * @var array $paymentTypes
 * @var array $bankAccounts
 * @var string|null $configuredBankAccountId
 * @var array $recentRequests
 */

$this->title = $title;
$student = $studentContext['student'];
$registrationNumber = $studentContext['registrationNumber'];
$studentName = trim(($student['surname'] ?? '') . ' ' . ($student['other_names'] ?? ''));
$studentPopover = '<div class="ecitizen-student-detail-grid">'
    . '<span class="ecitizen-detail-label">Registration number</span><span class="ecitizen-detail-value">' . Html::encode($registrationNumber) . '</span>'
    . '<span class="ecitizen-detail-label">Name</span><span class="ecitizen-detail-value">' . Html::encode($studentName) . '</span>'
    . '<span class="ecitizen-detail-label">Email</span><span class="ecitizen-detail-value">' . Html::encode($student['primary_email'] ?? '') . '</span>'
    . '<span class="ecitizen-detail-label">Phone</span><span class="ecitizen-detail-value">' . Html::encode($student['primary_phone_no'] ?? '') . '</span>'
    . '</div>';
$this->registerCss(<<<CSS
.ecitizen-payment-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    width: 100%;
}
.ecitizen-student-popover {
    --bs-popover-max-width: 34rem;
    min-width: min(34rem, calc(100vw - 2rem));
}
.ecitizen-student-popover .popover-body {
    padding: .85rem 1rem;
}
.ecitizen-student-detail-grid {
    display: grid;
    grid-template-columns: 10.5rem minmax(14rem, 1fr);
    gap: .45rem 1rem;
    align-items: start;
}
.ecitizen-detail-label {
    font-weight: 700;
    white-space: nowrap;
}
.ecitizen-detail-value {
    min-width: 0;
    overflow-wrap: anywhere;
    word-break: normal;
}
@media (max-width: 575.98px) {
    .ecitizen-student-popover {
        min-width: calc(100vw - 2rem);
    }
    .ecitizen-student-detail-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: .1rem;
    }
    .ecitizen-detail-value {
        margin-bottom: .45rem;
    }
}
CSS);
$this->registerJs(<<<JS
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
popoverTriggerList.forEach(function (popoverTriggerEl) {
    new bootstrap.Popover(popoverTriggerEl, {
        html: true,
        sanitize: false,
        trigger: 'focus',
        placement: 'bottom',
        customClass: 'ecitizen-student-popover'
    });
});
JS);

?>

<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?= Html::a('Home', ['/site/index']) ?></li>
                <li class="breadcrumb-item active" aria-current="page">eCitizen fee payment</li>
            </ol>
        </nav>
        <h1 class="m-0">eCitizen fee payment</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <?php if (!$paymentModeReady): ?>
            <div class="alert alert-danger">
                eCitizen payment mode 12 is not configured in SMIS.
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <div class="ecitizen-payment-title">
                            <h3 class="card-title mb-0">Payment request</h3>
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-info"
                                data-bs-toggle="popover"
                                data-bs-title="Student details"
                                data-bs-content="<?= Html::encode($studentPopover) ?>"
                                aria-label="View student details">
                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php $form = ActiveForm::begin([
                            'id' => 'ecitizen-payment-form',
                            'action' => ['checkout'],
                            'method' => 'post',
                        ]); ?>

                        <?= $form->field($model, 'amount')->input('number', [
                            'min' => 1,
                            'step' => '0.01',
                            'placeholder' => 'Amount in KES',
                        ]) ?>

                        <?= $form->field($model, 'payment_type_id')->dropDownList($paymentTypes, [
                            'prompt' => 'Select payment type',
                        ]) ?>

                        <?php if (!empty($configuredBankAccountId)): ?>
                            <?= Html::activeHiddenInput($model, 'bank_account_id') ?>
                        <?php else: ?>
                            <?= $form->field($model, 'bank_account_id')->dropDownList($bankAccounts, [
                                'prompt' => 'Select eCitizen settlement account',
                            ]) ?>
                        <?php endif; ?>

                        <?= $form->field($model, 'narration')->textInput([
                            'maxlength' => true,
                            'placeholder' => 'e.g. Tuition fee payment',
                        ]) ?>

                        <?= Html::submitButton('Continue to eCitizen', [
                            'class' => 'btn btn-success',
                            'disabled' => !$paymentModeReady,
                        ]) ?>
                        <?= Html::a('Previous invoices', ['invoices'], ['class' => 'btn btn-outline-primary']) ?>
                        <?= Html::a('Workflow report', ['report'], ['class' => 'btn btn-outline-secondary']) ?>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
