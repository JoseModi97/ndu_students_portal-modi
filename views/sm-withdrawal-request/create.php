<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalRequest $model */

$this->title = 'Submit Deferment Request';
$this->params['breadcrumbs'][] = ['label' => 'Deferment Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-header">
    <div class="page-header">
        <h3>Deferement  <i class="fa fa-angle-right" aria-hidden="true"></i>  Deferment Requests</h3>
    </div>
</div>
<div class="sm-withdrawal-request-create">
    <div class="card">
        <div class="card-body">

<!--    <h3>--><?php //= Html::encode($this->title) ?><!--</h3>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
