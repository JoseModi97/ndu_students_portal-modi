<?php

use app\models\IdRequestStatus;
use app\models\IdRequestType;
use app\models\StudentProgramme;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StudentIdRequest */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="student-id-request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'request_type_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(IdRequestType::find()->orderBy('request_type_id')->asArray()->all(), 'request_type_id', 'request_type_id'),
        'options' => ['placeholder' => 'Choose Smisportal.sm id request type'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'student_prog_curr_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(StudentProgramme::find()->orderBy('student_prog_curriculum_id')->asArray()->all(), 'student_prog_curriculum_id', 'student_prog_curriculum_id'),
        'options' => ['placeholder' => 'Choose Smisportal.sm student programme curriculum'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'request_date')->textInput(['placeholder' => 'Request Date']) ?>

    <?= $form->field($model, 'status_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(IdRequestStatus::find()->orderBy('status_id')->asArray()->all(), 'status_id', 'status_id'),
        'options' => ['placeholder' => 'Choose Smisportal.sm id request status'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'receipt_number')->textInput(['placeholder' => 'Receipt Number']) ?>

    <?= $form->field($model, 'source')->textInput(['maxlength' => true, 'placeholder' => 'Source']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
