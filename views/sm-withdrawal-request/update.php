<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalRequest $model */

$this->title = 'Update Deferment Request';
$this->params['breadcrumbs'][] = ['label' => 'Deferment Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="content-header">
    <div class="page-header">
        <h1>Deferment  <i class="fa fa-angle-right" aria-hidden="true"></i>  Update Request</h1>
    </div>
</div>
<div class="sm-withdrawal-request-update">
    <div class="card">
        <div class="card-body">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
