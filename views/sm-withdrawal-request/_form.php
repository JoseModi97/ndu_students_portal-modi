<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SmWithdrawalType;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\models\SmWithdrawalRequest $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sm-withdrawal-request-form">

    <?php $form = ActiveForm::begin(); ?>


       <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $progs = SmWithdrawalType::find()->select(['withdrawal_type_id', 'withrawal_type_name'])->asArray()->all();
            $data = ArrayHelper::map($progs, 'withdrawal_type_id', 'withrawal_type_name');
            echo $form
                ->field($model, 'withdrawal_type_id')
                ->label('Withdrawal Type', ['class'=>'mb-2 fw-bold'])
                ->widget(Select2::classname(), [
                    'data' => $data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select withdrawal type...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

    <?= $form->field($model, 'request_date')->hiddenInput(['value'=>date("Y-m-d")])->label(false) ?>

    <?= $form->field($model, 'reason')->textarea(['maxlength' => true, 'rows' => 10,])->label('Reason for Withdrawal/ Deferment') ?>
<!--    <?php //= $form->field($model, 'student_id')->hiddenInput(['value'=>Yii::$app->user->getId()])->label(false) ?>-->
    <?= $form->field($model, 'student_id')->hiddenInput(['value'=>142])->label(false) ?>
    <?= $form->field($model, 'approval_status')->hiddenInput(['value'=>'PENDING'])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
