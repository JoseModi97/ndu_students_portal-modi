<?php

use kartik\grid\GridView;
use yii\bootstrap5\Html;

/**
 * @var yii\web\View $this
 * @var string $registrationNumber
 * @var yii\data\ArrayDataProvider $invoiceDataProvider
 * @var yii\base\DynamicModel $invoiceFilterModel
 * @var array $invoiceFilterOptions
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
.ecitizen-invoice-filter-dropdown .select2-results > .select2-results__options {
    max-height: 285px;
}
.ecitizen-invoices-grid-panel {
    box-shadow: none;
}
.ecitizen-invoices-card {
    box-shadow: none;
}
/*
.ecitizen-timestamp-wrap {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    white-space: nowrap;
}
.ecitizen-timestamp-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.15rem;
    height: 1.15rem;
    padding: 0;
    border: 1px solid #0d6efd;
    border-radius: 50%;
    color: #0d6efd;
    font-size: .72rem;
    font-weight: 700;
    line-height: 1;
    text-decoration: none;
}
.ecitizen-timestamp-btn:hover,
.ecitizen-timestamp-btn:focus {
    background: #0d6efd;
    color: #fff;
    text-decoration: none;
}
.ecitizen-timestamp-popover {
    --bs-popover-max-width: 24rem;
}
.ecitizen-timestamp-detail-grid {
    display: grid;
    grid-template-columns: 7.5rem minmax(0, 1fr);
    gap: .4rem .8rem;
}
.ecitizen-timestamp-detail-label {
    color: #6c757d;
    font-weight: 700;
}
.ecitizen-timestamp-detail-value {
    overflow-wrap: anywhere;
}
*/
CSS);
$this->registerJs(<<<JS
/*
var timestampPopoverTriggerList = [].slice.call(document.querySelectorAll('.ecitizen-timestamp-btn[data-bs-toggle="popover"]'));
timestampPopoverTriggerList.forEach(function (popoverTriggerEl) {
    new bootstrap.Popover(popoverTriggerEl, {
        html: true,
        sanitize: false,
        trigger: 'focus',
        placement: 'bottom',
        customClass: 'ecitizen-timestamp-popover'
    });
});
*/

document.addEventListener('submit', function (event) {
    const form = event.target;
    if (!form.classList || !form.classList.contains('ecitizen-complete-payment-form')) {
        return;
    }

    const message = form.getAttribute('data-confirm') || 'Check eCitizen and credit this payment if confirmed?';
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

$settlementClass = static function (?string $status): string {
    return (string) $status === 'Settled' ? 'badge bg-success' : 'badge bg-warning text-dark';
};
$transactionTimestamp = static function (?string $value): string {
    $timestamp = strtotime((string) $value);
    if (!$timestamp) {
        return Html::tag('span', 'Not available', ['class' => 'text-muted']);
    }

    $display = date('M j, Y g:i A', $timestamp);
    return Html::encode($display);

    /*
    $period = date('A', $timestamp);
    $periodDescription = $period === 'AM'
        ? 'AM means before midday, from midnight up to 11:59 in the morning.'
        : 'PM means after midday, from noon up to 11:59 at night.';
    $content = Html::tag(
        'div',
        Html::tag('span', 'Full date', ['class' => 'ecitizen-timestamp-detail-label'])
            . Html::tag('span', date('l, F j, Y', $timestamp), ['class' => 'ecitizen-timestamp-detail-value'])
            . Html::tag('span', '12-hour time', ['class' => 'ecitizen-timestamp-detail-label'])
            . Html::tag('span', date('g:i:s A', $timestamp), ['class' => 'ecitizen-timestamp-detail-value'])
            . Html::tag('span', '24-hour time', ['class' => 'ecitizen-timestamp-detail-label'])
            . Html::tag('span', date('H:i:s', $timestamp), ['class' => 'ecitizen-timestamp-detail-value'])
            . Html::tag('span', 'AM/PM', ['class' => 'ecitizen-timestamp-detail-label'])
            . Html::tag('span', $periodDescription, ['class' => 'ecitizen-timestamp-detail-value']),
        ['class' => 'ecitizen-timestamp-detail-grid']
    );

    return Html::tag(
        'span',
        Html::tag('span', Html::encode($display))
            . Html::button('i', [
                'type' => 'button',
                'class' => 'btn btn-link ecitizen-timestamp-btn',
                'data-bs-toggle' => 'popover',
                'data-bs-title' => 'Transaction timestamp',
                'data-bs-content' => $content,
                'aria-label' => 'View full timestamp details for ' . $display,
            ]),
        ['class' => 'ecitizen-timestamp-wrap']
    );
    */
};
$select2FilterOptions = static function (string $placeholder): array {
    return [
        'options' => [
            'placeholder' => $placeholder,
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'dropdownCssClass' => 'ecitizen-invoice-filter-dropdown',
            'dropdownAutoWidth' => true,
            'minimumResultsForSearch' => 0,
        ],
    ];
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
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline ecitizen-invoices-card">
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $invoiceDataProvider,
                    'filterModel' => $invoiceFilterModel,
                    'columns' => [
                        ['class' => 'kartik\grid\SerialColumn'],
                        [
                            'label' => 'Reference',
                            'attribute' => 'reference',
                            'value' => 'reference',
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => $invoiceFilterOptions['reference'],
                            'filterWidgetOptions' => $select2FilterOptions('All references'),
                        ],
                        [
                            'label' => 'Date',
                            'attribute' => 'deposit_date',
                            'value' => static fn(array $invoice): string => Yii::$app->formatter->asDate($invoice['deposit_date']),
                            'filterType' => GridView::FILTER_DATE,
                            'filterWidgetOptions' => [
                                'options' => [
                                    'placeholder' => 'All dates',
                                ],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'clearBtn' => true,
                                    'format' => 'yyyy-mm-dd',
                                    'todayHighlight' => true,
                                ],
                            ],
                        ],
                        [
                            'label' => 'Transaction Date',
                            'attribute' => 'transaction_date',
                            'format' => 'raw',
                            'value' => static fn(array $invoice): string => $transactionTimestamp($invoice['transaction_date']),
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => $invoiceFilterOptions['transaction_date'],
                            'filterWidgetOptions' => $select2FilterOptions('All transaction dates'),
                        ],
                        [
                            'label' => 'Amount',
                            'attribute' => 'deposit_amount',
                            'value' => static fn(array $invoice): string => Yii::$app->formatter->asCurrency($invoice['deposit_amount']),
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => $invoiceFilterOptions['deposit_amount'],
                            'filterWidgetOptions' => $select2FilterOptions('All amounts'),
                        ],
                        [
                            'label' => 'Settlement',
                            'attribute' => 'settlement_status',
                            'format' => 'raw',
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => $invoiceFilterOptions['settlement_status'],
                            'filterWidgetOptions' => $select2FilterOptions('All settlements'),
                            'value' => static function (array $invoice) use ($settlementClass): string {
                                $status = $invoice['settlement_status'];
                                return Html::tag('span', Html::encode($status), [
                                    'class' => $settlementClass($status),
                                ]);
                            },
                        ],
                        [
                            'label' => 'Comment',
                            'attribute' => 'post_comment',
                            'value' => static fn(array $invoice): string => (string) $invoice['post_comment'],
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => $invoiceFilterOptions['post_comment'],
                            'filterWidgetOptions' => $select2FilterOptions('All comments'),
                        ],
                        [
                            'label' => 'Action',
                            'attribute' => 'action_status',
                            'format' => 'raw',
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => $invoiceFilterOptions['action_status'],
                            'filterWidgetOptions' => $select2FilterOptions('All actions'),
                            'value' => static function (array $invoice): string {
                                if ($invoice['action_status'] !== 'Pending action') {
                                    return Html::tag('span', Html::encode($invoice['action_status']), ['class' => 'text-muted']);
                                }

                                return Html::tag(
                                    'div',
                                    Html::a('Pay this invoice', ['invoice', 'trans_id' => $invoice['trans_id']], [
                                        'class' => 'btn btn-outline-primary btn-sm',
                                    ])
                                        . Html::beginForm(['complete-payment', 'trans_id' => $invoice['trans_id']], 'post', [
                                            'class' => 'ecitizen-complete-payment-form',
                                            'data-confirm' => 'Check eCitizen for this invoice and credit it only if payment is confirmed?',
                                        ])
                                        . Html::submitButton('Complete payment', [
                                            'class' => 'btn btn-outline-success btn-sm ecitizen-complete-payment-btn',
                                        ])
                                        . Html::endForm(),
                                    ['class' => 'ecitizen-action-buttons']
                                );
                            },
                        ],
                    ],
                    'emptyText' => 'No eCitizen invoices have been created yet.',
                    'export' => false,
                    'hover' => true,
                    'responsiveWrap' => false,
                    'striped' => false,
                    'bordered' => true,
                    'condensed' => true,
                    'tableOptions' => ['class' => 'table table-bordered ecitizen-invoices-table'],
                    'toolbar' => false,
                    'panel' => [
                        'type' => GridView::TYPE_DEFAULT,
                        'options' => ['class' => 'ecitizen-invoices-grid-panel'],
                        'heading' => false,
                        'before' => Html::tag(
                            'div',
                            Html::tag(
                                'div',
                                Html::tag('h3', 'My eCitizen invoices', ['class' => 'm-0'])
                                    . Html::tag('p', 'Registration number: ' . Html::encode($registrationNumber), ['class' => 'text-muted mb-0'])
                            )
                                . Html::tag(
                                    'div',
                                    Html::a('Make new payment', ['index'], ['class' => 'btn btn-outline-primary'])
                                ),
                            ['class' => 'd-flex justify-content-between align-items-center flex-wrap gap-2']
                        ),
                        'after' => false,
                        'footer' => '',
                    ],
                    'summary' => 'Showing {begin}-{end} of {totalCount} invoices',
                ]) ?>
            </div>
        </div>
    </div>
</section>
