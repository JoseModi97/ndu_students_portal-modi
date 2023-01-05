<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var $this yii\web\View
 * @var $model app\models\LoginForm
 * @var string $title
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = $title;

?>
<div class="login-box">
    <div data-aos="fade-left">
        Login
    </div>

    <div class="card">
        <div class="card-body login-card-body">

            <div style="padding: 20px; ">
                <img class="mx-auto d-block" style="height: 100px;" src="<?=Yii::getAlias('@web');?>/img/ndu-arms.png" alt="Logo">
            </div>

            <div id="login-form">
                <?php
                $form = ActiveForm::begin([
                    'action' => Url::to(['/site/process-login']),
                ]);

                echo $form->field($model, 'username')
                    ->textInput(['class' => 'form-control'])
                    ->label('Username', ['class' => 'required-control-label'])
                    ->hint('Type in your registration/admission reference number', ['id' => 'username-hint', 'tag' => 'small', 'class' => 'text-muted']);

                echo $form->field($model, 'password')
                    ->textInput([
                        'type' => 'password',
                        'class' => 'form-control'
                    ])
                    ->label('Password', ['class' => 'required-control-label']);
                ?>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success btn-block">Sign In</button>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

                <p class="mb-1" style="margin-top: 20px;">
                    <?php
                    echo Html::a('I forgot my password', ['/site/forgot-password'], ['title' => 'I forgot my password', 'class' => 'btn-link']);
                    ?>
                </p>
            </div>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->





