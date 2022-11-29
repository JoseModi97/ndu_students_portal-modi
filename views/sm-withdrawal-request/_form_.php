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

    <div class="container-fluid">
        <div class="row">
<div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
    <div class="card card-primary card-outline">
        <div class="card-header">

        </div>
        <div class="card-body">
<div class="sm-withdrawal-request-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>


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

    <?= $form->field($model, 'reason')->textarea(['maxlength' => true, 'rows' => 10,])->label('Reason for Withdrawal/ Deferment'); ?>
    <?php echo
    $form->field($model, 'file')->label('Upload Supporting Document', ['class'=>'mb-2 fw-bold form-label'])
        ->fileInput(['class' => 'form-control']);
    ?>

    <?= $form->field($model, 'student_id')->hiddenInput(['value'=>142])->label(false); ?>
    <?= $form->field($model, 'approval_status')->hiddenInput(['value'=>'PENDING'])->label(false) ;?>



    <div class="row mb-3">
        <div class="col-md-12">



        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
</div>
</div>
</div>


