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
<div class="login-box">
    <div class="card">
        <div class="card-body login-card-body">
            <div style="padding: 20px; ">
                <img class="mx-auto d-block" style="height: 100px;" src="<?=Yii::getAlias('@web');?>/img/ndu-arms.png" alt="Logo">
            </div>

            <p class="login-options-title">
                A new password will be sent to your email address
            </p>

            <?php
            $form = ActiveForm::begin([
                'action' => Url::to(['/site/password-reset']),
            ]);

            echo $form->field($model, 'username')
                ->textInput(['class' => 'form-control'])
                ->label('Admission reference number', ['class' => 'required-control-label'])
                ->hint('In the reg no. PI5/UVXYZ/2022, UVXYZ is the admission ref no.', ['tag' => 'small', 'class' => 'text-muted']);

            echo $form->field($model, 'email')
                ->textInput([
                    'type' => 'email',
                    'class' => 'form-control'
                ])
                ->label('Email', ['class' => 'required-control-label']);
            ?>

            <div class="row">
                <div class="col-4"></div>
                <div class="col-8">
                    <button type="submit" class="btn btn-success btn-block">
                        Request new password
                    </button>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

            <p class="mb-1">
                <?= Html::a('Sign in', ['/site/login'], ['title' => 'Sign in', 'class' => 'btn-link']); ?>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

