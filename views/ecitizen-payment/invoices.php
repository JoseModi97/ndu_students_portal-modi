<?php

use yii\bootstrap5\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var string $registrationNumber
 * @var array $invoices
 */

$this->title = $title;
$this->registerCss(<<<CSS
.ecitizen-invoices-table th,
.ecitizen-invoices-table td {
    vertical-align: middle;
}
.ecitizen-action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: .35rem;
}
.ecitizen-complete-payment-form {
    display: inline-flex;
    margin: 0;
}
.ecitizen-complete-payment-btn.is-loading {
    opacity: .75;
    cursor: wait;
}
CSS);
$this->registerJs(<<<JS
document.addEventListener('submit', function (event) {
    const form = event.target;
    if (!form.classList || !form.classList.contains('ecitizen-complete-payment-form')) {
        return;
    }

    const message = form.getAttribute('data-confirm') || 'Check eCitizen and complete this payment if confirmed?';
    if (!window.confirm(message)) {
        event.preventDefault();
        return;
    }

    const button = form.querySelector('.ecitizen-complete-payment-btn');
    if (button) {
        button.classList.add('is-loading');
        button.disabled = true;
        button.textContent = 'Checking...';
    }
}, true);
JS);

$statusClass = static function (?string $status): string {
    return strtoupper((string) $status) === 'POSTED' ? 'badge bg-success' : 'badge bg-warning text-dark';
};
?>

<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?= Html::a('Home', ['/site/index']) ?></li>
                <li class="breadcrumb-item"><?= Html::a('eCitizen Payment', ['index']) ?></li>
                <li class="breadcrumb-item active" aria-current="page">My eCitizen invoices</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="m-0">My eCitizen invoices</h1>
                <p class="text-muted mb-0">Registration number: <?= Html::encode($registrationNumber) ?></p>
            </div>
            <div>
                <?= Html::a('Workflow report', ['report'], ['class' => 'btn btn-outline-secondary']) ?>
                <?= Html::a('Make new payment', ['index'], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Previous eCitizen requests</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped ecitizen-invoices-table">
                    <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Comment</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($invoices)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No eCitizen invoices have been created yet.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($invoices as $invoice): ?>
                        <?php
                        $isPosted = strtoupper((string) $invoice['post_status']) === 'POSTED';
                        $reference = $invoice['source_reference'] ?: $invoice['trans_reference'];
                        ?>
                        <tr>
                            <td><?= Html::encode($reference) ?></td>
                            <td><?= Yii::$app->formatter->asDate($invoice['deposit_date']) ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($invoice['deposit_amount']) ?></td>
                            <td><span class="<?= Html::encode($statusClass($invoice['post_status'])) ?>"><?= Html::encode($invoice['post_status'] ?: 'PENDING') ?></span></td>
                            <td><?= Html::encode($invoice['post_comment']) ?></td>
                            <td>
                                <div class="ecitizen-action-buttons">
                                    <?php if (!$isPosted): ?>
                                        <?= Html::a('Pay this invoice', ['invoice', 'trans_id' => $invoice['trans_id']], [
                                            'class' => 'btn btn-outline-primary btn-sm',
                                        ]) ?>
                                        <?= Html::beginForm(['complete-payment', 'trans_id' => $invoice['trans_id']], 'post', [
                                            'class' => 'ecitizen-complete-payment-form',
                                            'data-confirm' => 'Check eCitizen for this invoice and post it only if payment is confirmed?',
                                        ]) ?>
                                        <?= Html::submitButton('Complete payment', [
                                            'class' => 'btn btn-outline-success btn-sm ecitizen-complete-payment-btn',
                                        ]) ?>
                                        <?= Html::endForm() ?>
                                    <?php else: ?>
                                        <span class="text-muted">Posted</span>
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
</section>
