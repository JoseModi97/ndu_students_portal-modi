<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalRequest $model */

$this->title = 'Deferment request #' . $model->withdrawal_request_id;
$this->params['breadcrumbs'][] = ['label' => 'Deferment Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->withdrawal_request_id;
\yii\web\YiiAsset::register($this);
?>
<div class="content-header">
    <div class="page-header">
        <h1>Deferment  <i class="fa fa-angle-right" aria-hidden="true"></i>  Request Details</h1>
    </div>
</div>
<div class="sm-withdrawal-request-view">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0"><?= Html::encode($this->title) ?></h3>
            <div>
                <?= Html::a('Update', ['update', 'withdrawal_request_id' => $model->withdrawal_request_id], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                <?= Html::a('Delete', ['delete', 'withdrawal_request_id' => $model->withdrawal_request_id], [
                    'class' => 'btn btn-outline-danger btn-sm',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'withdrawal_request_id',
                    'withdrawal_type_id',
                    'request_date',
                    'reason',
                    'approval_status',
                    'student_id',
                ],
            ]) ?>
        </div>
    </div>
</div>
