<?php
/* @var $this View */

/* @var $model StudentId */

use app\models\StudentId;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Report ID as lost: ' . ' ' . $model->student_id_serial_no;
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
                <?= $this->render('forms/_report-lost-form', ['model' => $model]); ?>
            </div>
        </div>
    </div>
</section>
