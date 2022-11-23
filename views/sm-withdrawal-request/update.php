<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalRequest $model */

$this->title = 'Update Deferment Request';
//$this->params['breadcrumbs'][] = ['label' => 'Deferment Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->withdrawal_request_id, 'url' => ['view', 'withdrawal_request_id' => $model->withdrawal_request_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="content-header">
    <div class="page-header">
        <h3>Deferement Requests <i class="fa fa-angle-right" aria-hidden="true"></i>  Update </h3>
    </div>
</div>
<div class="sm-withdrawal-request-update">
    <div class="card">
        <div class="card-body">
<!--    <h3>--><?php //= Html::encode($this->title) ?><!--</h3>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
