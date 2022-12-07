<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StudentIdRequest */

$this->title = 'Update Student Id Request: ' . ' ' . $model->request_id;
$this->params['breadcrumbs'][] = ['label' => 'Student Id Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->request_id, 'url' => ['view', 'id' => $model->request_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
        <h1>Student ID <i class="fa fa-angle-right" aria-hidden="true"></i> <?= Html::encode($this->title) ?></h1>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <?= $this->render('forms/_id-request-form', ['model' => $model]); ?>
            </div>
        </div>
    </div>
</section>
