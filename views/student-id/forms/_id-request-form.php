<?php
/** @noinspection PhpUnhandledExceptionInspection */

use app\models\IdRequestStatus;
use app\models\IdRequestType;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StudentIdRequest */
/* @var $form kartik\widgets\ActiveForm */
?>

<div class="student-id-request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'request_type_id')->widget(Select2::class, [
        'data' => IdRequestType::loadRequestTypeByName(),
        'options' => ['placeholder' => 'Choose Smisportal.sm id request type'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'student_prog_curr_id')->widget(Select2::class, [
        'data' => \app\models\extended\StudentProgramme::loadActiveProgramme(),
        'options' => ['placeholder' => 'Choose Smisportal.sm student programme curriculum'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'request_date')->hiddenInput(['readonly' => true])->label(false) ?>

    <?= $form->field($model, 'status_id')->widget(Select2::class, [
        'data' =>
            $model->isNewRecord ?
                IdRequestStatus::loadRequestStatusByName() :
                IdRequestStatus::loadRequestStatusByName($model->status->status_name),
        'options' => ['placeholder' => 'Select ID status'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'source')->textInput(['maxlength' => true]) ?>


    <div class="row">
        <div class="col">
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-danger btn-lg']) ?>
        </div>
        <div class="col">
            <?= Html::submitButton('Report as lost', [
                'class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block',
                'data' => [
                    'confirm' => 'Are you sure want to request a new id, your student account will be charged a replacement fee?'
                ]
            ]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
