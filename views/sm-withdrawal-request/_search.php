<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\search\SmWithdrawalRequestSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-withdrawal-request-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'withdrawal_request_id') ?>

    <?= $form->field($model, 'withdrawal_type_id') ?>

    <?= $form->field($model, 'request_date') ?>

    <?= $form->field($model, 'reason') ?>

    <?= $form->field($model, 'approval_status') ?>

    <?php // echo $form->field($model, 'student_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
