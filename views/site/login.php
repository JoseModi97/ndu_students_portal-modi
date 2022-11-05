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

            <div id="login-options">
                <div class="row">
                    <div class="col-12">
                        <p class="login-options-title">Welcome! How would you like to continue?</p>
                    </div>

                    <div class="col-12 registered-student">
                        <button type="submit"id="reg-student-btn" class="btn reg-student-btn btn-block">
                            Login as a registered student &nbsp;
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="col-12 complete-registration">
                        <button type="submit" id="complete-reg-btn" class="btn complete-reg-btn btn-block">
                            Login to complete registration &nbsp;
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div id="login-form">
                <?php
                $form = ActiveForm::begin([
                    'action' => Url::to(['/site/process-login']),
                ]);

                echo $form->field($model, 'username')
                    ->textInput(['class' => 'form-control'])
                    ->label('Username', ['class' => 'required-control-label'])
                    ->hint('Username', ['id' => 'username-hint', 'tag' => 'small', 'class' => 'text-muted']);

                echo $form->field($model, 'password')
                    ->textInput([
                        'type' => 'password',
                        'class' => 'form-control'
                    ])
                    ->label('Password', ['class' => 'required-control-label']);

                echo $form->field($model, 'option')
                    ->hiddenInput(['id' => 'option'])->label(false);
                ?>

                <div class="row">
                    <div class="col-5">
                        <button id="btn-cancel-login" class="btn btn-cancel-login btn-block">
                            <i class="fa fa-angle-left" aria-hidden="true"></i>
                            &nbsp;
                            Cancel
                        </button>
                    </div>
                    <div class="col-5 offset-2">
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

<?php
$loginJs = <<< JS
$('#login-options').show();
$('#login-form').hide();

$('#reg-student-btn').click(function (e){
    e.preventDefault(); 
    $('#option').val('registered');
    $('#username-hint').text('Type in your registration number');
    $('#login-options').hide();
    $('#login-form').show();
});

$('#complete-reg-btn').click(function (e){
    e.preventDefault(); 
    $('#option').val('admitted');
    $('#username-hint').text('Type in your admission reference number');
    $('#login-options').hide();
    $('#login-form').show();
});

$('#btn-cancel-login').click(function (e){
    e.preventDefault(); 
    $('#login-options').show();
    $('#login-form').hide();
});
JS;
$this->registerJs($loginJs, yii\web\View::POS_READY);




