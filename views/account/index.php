<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var app\models\User $user
 */

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
        <h1>Account  <i class="fa fa-angle-right" aria-hidden="true"></i>  Account Details</h1>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
       <?= $this->render('updateProfile', ['user' => $user]); ?>

       <?= $this->render('updateEmail', ['user' => $user]); ?>

       <?= $this->render('updatePassword'); ?>
    </div>
</section>