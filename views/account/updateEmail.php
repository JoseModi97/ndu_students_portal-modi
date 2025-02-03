<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var app\models\User $user
 */

use app\helpers\SmisHelper;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

$primaryEmailVerifiedDate = $user->primary_email_verified_date;
$secondaryEmailVerifiedDate = $user->secondary_email_verified_date;

if(!empty($user->primary_email) && !empty($primaryEmailVerifiedDate)){
    try {
        $primaryEmailVerifiedDate = SmisHelper::formatDate($user->primary_email_verified_date, 'd/m/Y');
    } catch (Exception $ex) {
        $message = $ex->getMessage();
        if(YII_ENV_DEV){
            $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
        }
        throw new ServerErrorHttpException($message, 500);
    }
}

if(!empty($user->alternative_email) && !empty($secondaryEmailVerifiedDate)){
    try {
        $secondaryEmailVerifiedDate = SmisHelper::formatDate($user->secondary_email_verified_date, 'd/m/Y');
    } catch (Exception $ex) {
        $message = $ex->getMessage();
        if(YII_ENV_DEV){
            $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
        }
        throw new ServerErrorHttpException($message, 500);
    }
}
?>

<div class="row">
    <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    Update email
                </h3>
            </div>
            <div class="card-body" >
                <div class="bg-warning text-center" style="margin-bottom: 20px; padding: 20px 0;border-radius: .25rem">
                    Follow the instructions sent to your inbox to verify the email address. If you have not received the instructions, click on the update button again
                </div>

                <form id="update-email-form" onsubmit="return false" method="post" action="#">
                    <div class="loader"></div>

                    <div class="error-display alert text-center" role="alert">
                    </div>

                    <div class="form-group row">
                        <label for="primary-email" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Primary email
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="email" class="form-control profile-email" id="primary-email" name="primaryEmail" value="<?=$user->primary_email?>" required>
                            <small class="text-info">This MUST match the email in your AD account</small>
                            <?php if(!empty($user->primary_email) && empty($primaryEmailVerifiedDate)):?>
                                <small class="text-danger">Not verified</small>
                            <?php elseif(!empty($user->primary_email) && !empty($primaryEmailVerifiedDate)):?>
                                <small class="text-muted">verified on <?= $primaryEmailVerifiedDate; ?></small>
                            <?php endif;?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="secondary-email" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Secondary email
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="email" class="form-control profile-email" id="secondary-email" name="secondaryEmail" value="<?=$user->alternative_email?>">
                            <?php if(!empty($user->alternative_email) && empty($secondaryEmailVerifiedDate)):?>
                                <small class="text-danger">Not verified</small>
                            <?php elseif(!empty($user->alternative_email) && !empty($secondaryEmailVerifiedDate)):?>
                                <small class="text-muted">verified on <?= $secondaryEmailVerifiedDate; ?></small>
                            <?php endif;?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 col-md-5 col-lg-5 offset-md-5 offset-lg-5">
                            <button type="submit" id="btn-update-emails" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$updateEmailUrl = Url::to(['/account/update-email']);

$updateEmailJs = <<< JS
const updateEmailUrl = '$updateEmailUrl'; 
const emailForm = $('#update-email-form');

const emailLoader = $('#update-email-form > .loader');
emailLoader.html(loader);
emailLoader.hide();
        
const emailErrorDisplay =  $('#update-email-form > .error-display');
emailErrorDisplay.hide();

emailForm.validate({
    rules: {
        'primaryEmail': {
            required: true,
            email: true,
            notEqualToGroup: ['.profile-email']
        },
        'secondaryEmail': {
            email: true,
            notEqualToGroup: ['.profile-email']
        }
    },
    messages: {
        'primaryEmail': {
            notEqualToGroup: 'Please enter a unique email address'
        },
        'secondaryEmail': {
            notEqualToGroup: 'Please enter a unique email address'
        }
    }
});

$('#btn-update-emails').click(function (e){
    e.preventDefault();
    if(emailForm.valid()){
        if(confirm('Update emails?')){
            emailErrorDisplay.hide();
            emailLoader.show();
            $.ajax({
                url: updateEmailUrl,
                type: 'POST',
                data: emailForm.serialize()
            }).done(function (data){
                emailLoader.hide();
                if(!data.success){
                    emailErrorDisplay.html(data.message) 
                    emailErrorDisplay.show();
                }
            }).fail(function (data){
                emailLoader.hide();
                emailErrorDisplay.html(data.responseText) 
                emailErrorDisplay.show();
            });
        }
    }else{
        emailLoader.hide();
        emailErrorDisplay.html('There were errors below, correct them and try submitting again.');   
        emailErrorDisplay.show();
    }
});
JS;
$this->registerJs($updateEmailJs, yii\web\View::POS_READY);