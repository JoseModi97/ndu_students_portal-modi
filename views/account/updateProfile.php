<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var app\models\User $user
 */

use yii\helpers\Url;
?>

<div class="row">
    <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    Your profile
                </h3>
            </div>
            <div class="card-body">
                <form id="update-profile-form" onsubmit="return false" method="post" action="#">
                    <div class="loader"></div>

                    <div class="error-display alert text-center" role="alert">
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Name
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" disabled class="form-control" id="name" name="name" value="<?=strtoupper($user->surname . ' ' . $user->other_names);?>">
                            <small class="text-muted"> To change your name, submit a change name request.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="primary-phone" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Primary phone
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control profile-phones" id="primary-phone" name="primaryPhone" value="<?=$user->primary_phone_no?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="secondary-phone" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Secondary phone
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control profile-phones" id="secondary-phone" name="secondaryPhone" value="<?=$user->alternative_phone_no?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="post-address" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2text-md-right text-lg-right col-form-label required-control-label">
                            Post address
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control" id="post-address" name="postAddress" value="<?=$user->post_address?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="post-code" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Post code
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control" id="post-code" name="postCode" value="<?=$user->post_code?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="town" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Town
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control" id="town" name="town" value="<?=strtoupper($user->town)?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="national-id-no" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            National Id number
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control" id="national-id-no" name="nationalIdNumber" value="<?=$user->national_id?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="birth-cert-no" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Birth certificate number
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control" id="birth-cert-no" name="birthCertificateNumber" value="<?=$user->birth_cert_no?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="passport-no" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Passport number
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control" id="passport-no" name="passportNumber" value="<?=$user->passport_no?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 col-md-5 col-lg-5 offset-md-5 offset-lg-5">
                            <button type="submit" id="btn-update-profile" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$updateProfileUrl = Url::to(['/account/update-profile']);

$updateProfileJs = <<< JS
const updateProfileUrl = '$updateProfileUrl'; 
const profileForm = $('#update-profile-form');

const profileLoader = $('#update-profile-form > .loader');
profileLoader.html(loader);
profileLoader.hide();
        
const profileErrorDisplay =  $('#update-profile-form > .error-display');
profileErrorDisplay.hide();

profileForm.validate({
    rules: {
       'primaryPhone': {
        required: true,
        notEqualToGroup: ['.profile-phones']
    },
    'secondaryPhone': {
        notEqualToGroup: ['.profile-phones']
    },
    'postCode': {
        required: true
    },
    'postAddress': {
        required: true
    },
    'town': {
        required: true
    }  
    },
    messages: {
        'primaryPhone': {
        notEqualToGroup: 'Please enter a unique phone number'
    },
    'secondaryPhone': {
        notEqualToGroup: 'Please enter a unique phone number'
    } 
    }
});

$('#btn-update-profile').click(function (e){
    e.preventDefault();
    if(profileForm.valid()){
        if(confirm('Update profile?')){
            profileErrorDisplay.hide();
            profileLoader.show();
            $.ajax({
                url: updateProfileUrl,
                type: 'POST',
                data: $('#update-profile-form').serialize()
            }).done(function (data){
                profileLoader.hide();
                if(!data.success){
                    profileErrorDisplay.html(data.message) 
                    profileErrorDisplay.show();
                }
            }).fail(function (data){
                profileLoader.hide();
                profileErrorDisplay.html(data.responseText) 
                profileErrorDisplay.show();
            });
        }
    }else{
        profileLoader.hide();
        profileErrorDisplay.html('There were errors below, correct them and try submitting again.');   
        profileErrorDisplay.show();
    }
});
JS;
$this->registerJs($updateProfileJs, yii\web\View::POS_READY);
