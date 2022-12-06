<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\StudentIdRequest */

$this->title = 'Replacement request';
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
        <?= $this->render('_form', ['model' => $model]); ?>
    </div>
</section>