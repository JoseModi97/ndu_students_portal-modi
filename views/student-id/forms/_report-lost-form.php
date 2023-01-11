<?php /** @noinspection PhpUnhandledExceptionInspection */

/* @var $this \yii\web\View */
/* @var $model app\models\StudentId */

/* @var $form yii\widgets\ActiveForm */

use app\models\StudentIdStatus;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->errorSummary($model); ?>

<?= $form->field($model, 'student_prog_curr_id')->widget(Select2::class, [
    'data' => \app\models\extended\StudentProgramme::loadActiveProgramme(),
    'options' => ['placeholder' => 'Choose Smisportal.sm student programme curriculum'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>


<div class="row">
    <div class="col">
        <?= $form->field($model, 'issuance_date')->textInput(['readonly' => !$model->isNewRecord]); ?>
    </div>
    <div class="col">
        <?= $form->field($model, 'valid_from')->textInput(['readonly' => !$model->isNewRecord]); ?>
    </div>
    <div class="col">
        <?= $form->field($model, 'valid_to')->textInput(['readonly' => !$model->isNewRecord]); ?>
    </div>
</div>
<?= $form->field($model, 'barcode')->textInput(['placeholder' => 'Barcode', 'readonly' => !$model->isNewRecord]) ?>

<?= $form->field($model, 'id_status')->widget(Select2::class, [
    'data' => [
        StudentIdStatus::ID_LOST => StudentIdStatus::ID_LOST
    ],
    'options' => ['placeholder' => 'Select ID status'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>


<div class="row">
    <div class="col">
        <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-success btn-lg']) ?>
    </div>
    <div class="col">
        <?= Html::submitButton('Report as lost', [
            'class' => 'btn btn-danger btn-lg btn-block',
            'data' => [
                'confirm' => 'Are you sure want to Create/Update this message?'
            ]
        ]) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
