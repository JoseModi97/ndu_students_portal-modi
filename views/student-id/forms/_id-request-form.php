<?php
/** @noinspection PhpUnhandledExceptionInspection */

use app\models\IdRequestStatus;
use app\models\IdRequestType;
use app\models\StudentProgramme;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
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
        'data' => ArrayHelper::map(StudentProgramme::find()->orderBy('student_prog_curriculum_id')->asArray()->all(), 'student_prog_curriculum_id', 'student_prog_curriculum_id'),
        'options' => ['placeholder' => 'Choose Smisportal.sm student programme curriculum'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'request_date')->hiddenInput(['readonly' => true])->label(false) ?>

    <?= $form->field($model, 'status_id')->widget(Select2::class, [
        'data' =>
            $model->isNewRecord ? IdRequestStatus::loadRequestStatusByName() : IdRequestStatus::loadRequestStatusByName($model->status->status_name),
        'options' => ['placeholder' => 'Choose Smisportal.sm id request status'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'receipt_number')->textInput(['placeholder' => 'Receipt Number']) ?>

    <?= $form->field($model, 'source')->textInput(['maxlength' => true]) ?>

    <div class="d-grid gap-2 col-6 mx-auto">
        <?= Html::submitButton($model->isNewRecord ? 'Submit request' : 'Update request', [
            'class' => $model->isNewRecord ? 'btn btn-success btn-lg' : 'btn btn-primary btn-lg'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
