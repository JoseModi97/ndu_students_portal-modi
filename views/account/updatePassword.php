<div class="row">
    <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    Change password
                </h3>
            </div>
            <div class="card-body">
                <form id="update-password-form" onsubmit="return false" method="post" action="#">
                    <div class="loader"></div>

                    <div class="error-display alert text-center" role="alert">
                    </div>

                    <div class="form-group row">
                        <label for="old-password" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Enter old password
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="password" class="form-control" id="old-password" name="oldPassword" value="" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 col-md-5 col-lg-5 offset-md-5 offset-lg-5">
                            <small class="text-muted">Password must be 8 to 20 characters</small><br>
                            <small class="text-muted">Password must contain at least one uppercase</small><br>
                            <small class="text-muted">Password must contain at least one lowercase</small><br>
                            <small class="text-muted">Password must contain at least one digit</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="new-password" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Choose a new password
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="password" class="form-control" id="new-password" name="newPassword" value="" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="confirm-password" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Re-enter new password
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="password" class="form-control" id="confirm-password" name="confirmPassword" value="" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 col-md-5 col-lg-5 offset-md-5 offset-lg-5">
                            <button id="btn-update-password" class="btn btn-success">Change Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
use yii\helpers\Url;

$updatePasswordUrl = Url::to(['/account/update-password']);

$updatePasswordJs = <<< JS
const updatePasswordUrl = '$updatePasswordUrl'; 
const passwordForm = $('#update-password-form');

const passwordLoader = $('#update-password-form > .loader');
passwordLoader.html(loader);
passwordLoader.hide();
        
const passwordErrorDisplay =  $('#update-password-form > .error-display');
passwordErrorDisplay.hide();

passwordForm.validate({
    rules: {
        'oldPassword': {
            required: true
        },
        'newPassword': {
            required: true,
            passwordStrength: true
        },
        'confirmPassword': {
            required: true,
            equalTo: '#new-password'
        }
    },
    messages: {
        'confirmPassword': {
            equalTo: 'Please re-enter the new password again'
        }
    }
});

$('#btn-update-password').click(function (e){
    e.preventDefault();
    if(passwordForm.valid()){
        if(confirm('Change password?')){
            passwordErrorDisplay.hide();
            passwordLoader.show();
            $.ajax({
                 url: updatePasswordUrl,
                 type: 'POST',
                 data: passwordForm.serialize()
            }).done(function (data){
                 passwordLoader.hide();
                 if(data.success){
                     passwordForm.trigger("reset");
                     successToaster(data.message);
                 }else{
                     passwordErrorDisplay.html(data.message) 
                     passwordErrorDisplay.show();
                 }
            }).fail(function (data){
                 passwordLoader.hide();
                 passwordErrorDisplay.html(data.responseText) 
                 passwordErrorDisplay.show();
            });
        }
    }else{
         passwordLoader.hide();
         passwordErrorDisplay.html('There were errors below, correct them and try submitting again.');   
         passwordErrorDisplay.show();
    }
});
JS;
$this->registerJs($updatePasswordJs, yii\web\View::POS_READY);

