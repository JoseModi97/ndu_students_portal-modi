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
<div class="auth-heading">
    <h1>Welcome back</h1>
    <p class="auth-subtitle">Sign in with your registration or admission number to continue.</p>
</div>

<div id="login-form">
    <?php
    $form = ActiveForm::begin([
        'action' => Url::to(['/site/process-login']),
    ]);

    echo $form->field($model, 'username', [
        'template' => "{label}\n<div class=\"auth-field\">\n<i class=\"fa fa-user auth-field-icon\" aria-hidden=\"true\"></i>\n{input}\n</div>\n{hint}\n{error}",
    ])
        ->textInput(['class' => 'form-control'])
        ->label('Username', ['class' => 'required-control-label'])
        ->hint('Type in your registration/admission reference number', ['id' => 'username-hint', 'tag' => 'small', 'class' => 'text-muted']);

    echo $form->field($model, 'password', [
        'template' => "{label}\n<div class=\"auth-field auth-field-has-toggle\">\n<i class=\"fa fa-lock auth-field-icon\" aria-hidden=\"true\"></i>\n{input}\n<button type=\"button\" class=\"auth-field-toggle\" data-toggle-password=\"login-password\" aria-label=\"Show password\"><i class=\"fa fa-eye\" aria-hidden=\"true\"></i></button>\n</div>\n{error}",
    ])
        ->textInput([
            'id' => 'login-password',
            'type' => 'password',
            'class' => 'form-control'
        ])
        ->label('Password', ['class' => 'required-control-label']);
    ?>

    <div class="auth-row">
        <label class="auth-remember">
            <input type="checkbox" id="login-remember">
            <span>Remember me</span>
        </label>
        <?= Html::a('Forgot password?', ['/site/forgot-password'], ['title' => 'I forgot my password', 'class' => 'btn-link']); ?>
    </div>

    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-success btn-block">Sign In</button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <p class="auth-support-note">
        Having trouble signing in?
        <?= Html::a('Contact support', 'mailto:smis_support@ndu.ac.ke', ['title' => 'Contact support']); ?>
    </p>
</div>

<?php
$this->registerJs(<<<JS
$(document).on('click', '[data-toggle-password]', function () {
    var targetId = $(this).data('toggle-password');
    var \$input = $('#' + targetId);
    var isHidden = \$input.attr('type') === 'password';
    \$input.attr('type', isHidden ? 'text' : 'password');
    $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    $(this).attr('aria-label', isHidden ? 'Hide password' : 'Show password');
});
JS
);
?>





