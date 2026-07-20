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

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<!--    --><?php //= $form->field($model, 'withdrawal_type_id')->textInput() ?>
    <div class="row mb-2">
        <div class="col-md-12">
            <?php
            $progs = SmWithdrawalType::find()->select(['withdrawal_type_id', 'withdrawal_type_name'])->asArray()->all();
            $data = ArrayHelper::map($progs, 'withdrawal_type_id', 'withdrawal_type_name');
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


    <?= $form->field($model, 'request_date')->hiddenInput(['value'=>date("Y-m-d")])->label(false); ?>
    <?= $form->field($model, 'reason')->textArea(['maxlength' => true,'rows' => 5,])->label('Reason for Withdrawal/ Deferment'); ?>

    <?= $form->field($model, 'student_id')->hiddenInput(['value'=>142])->label(false); ?>
    <?= $form->field($model, 'approval_status')->hiddenInput(['value'=>'PENDING'])->label(false) ;?>

    <?= $form->field($model, 'supporting_doc_url')->fileInput(['maxlength' => true])
        ->hint('Attach any document that supports your request (e.g. a letter or medical note).', ['tag' => 'small', 'class' => 'text-muted']); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
