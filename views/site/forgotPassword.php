<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/* @var $this yii\web\View */
/* @var $model app\models\ForgotPasswordForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<div class="auth-heading">
    <h1>Reset your password</h1>
    <p class="auth-subtitle">A new password will be sent to your email address.</p>
</div>

<div id="login-form">
    <?php
    $form = ActiveForm::begin([
        'action' => Url::to(['/site/password-reset']),
    ]);

    echo $form->field($model, 'username', [
        'template' => "{label}\n<div class=\"auth-field\">\n<i class=\"fa fa-id-card auth-field-icon\" aria-hidden=\"true\"></i>\n{input}\n</div>\n{hint}\n{error}",
    ])
        ->textInput(['class' => 'form-control'])
        ->label('Admission reference number', ['class' => 'required-control-label'])
        ->hint('In the reg no. PI5/UVXYZ/2022, UVXYZ is the admission ref no.', ['tag' => 'small', 'class' => 'text-muted']);

    echo $form->field($model, 'email', [
        'template' => "{label}\n<div class=\"auth-field\">\n<i class=\"fa fa-envelope auth-field-icon\" aria-hidden=\"true\"></i>\n{input}\n</div>\n{error}",
    ])
        ->textInput([
            'type' => 'email',
            'class' => 'form-control'
        ])
        ->label('Email', ['class' => 'required-control-label']);
    ?>

    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-success btn-block">
                Request new password
            </button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <p class="auth-support-note">
        <?= Html::a('Back to sign in', ['/site/login'], ['title' => 'Sign in', 'class' => 'btn-link']); ?>
    </p>
</div>
