<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\StudentIdRequest */

$this->title = 'New ID Replacement request';
//$this->params['breadcrumbs'][] = ['label' => 'New student ID Requests', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
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
                <?= $this->render('_form', ['model' => $model]); ?>
            </div>
        </div>
    </div>
</section>