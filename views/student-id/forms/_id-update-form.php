<?php /** @noinspection PhpUnhandledExceptionInspection */

/* @var $this \yii\web\View */
/* @var $model app\models\StudentId */

/* @var $form yii\widgets\ActiveForm */

use kartik\form\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->errorSummary($model); ?>

<?= $form->field($model, 'student_prog_curr_id')->widget(\kartik\widgets\Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\StudentProgramme::find()->orderBy('student_prog_curriculum_id')->asArray()->all(), 'student_prog_curriculum_id', 'student_prog_curriculum_id'),
    'options' => ['placeholder' => 'Choose Smisportal.sm student programme curriculum'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>

<?= $form->field($model, 'issuance_date')->textInput(); ?>

<?= $form->field($model, 'valid_from')->textInput(); ?>

<?= $form->field($model, 'valid_to')->textInput(); ?>

<?= $form->field($model, 'barcode')->textInput(['placeholder' => 'Barcode']) ?>

<?= $form->field($model, 'id_status')->textInput(['maxlength' => true, 'placeholder' => 'Id Status']) ?>


<div class="d-grid gap-2 col-6 mx-auto">
    <?= Html::submitButton('Update ID status', ['class' => 'btn btn-primary btn-lg']) ?>
</div>

<?php ActiveForm::end(); ?>
