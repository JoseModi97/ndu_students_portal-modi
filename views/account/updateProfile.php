<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var app\models\User $user
 * @var app\models\Sponsor $sponsors[]
 */

use app\models\Sponsor;
use yii\helpers\Url;

$nationality = 'non-kenyan';
if(strtolower($user->nationality) === 'kenyan'){
    $nationality = 'kenyan';
}
?>

<div class="row">
    <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    Update profile
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
                            <small class="text-muted"> To change your name, submit a change name request</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="nationality" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Nationality
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" disabled class="form-control" id="nationality" name="nationality" value="<?=strtoupper($user->nationality)?>">
                            <small class="text-muted"> Contact the student records office for update</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="service" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Service
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" disabled class="form-control" id="service" name="service" value="<?=strtoupper($user->service)?>">
                            <small class="text-muted"> Contact the student records office for update</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="service-no" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Service number
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" disabled class="form-control" id="service-no" name="serviceNumber" value="<?=strtoupper($user->service_number)?>">
                            <small class="text-muted"> Contact the student records office for update</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="sponsor" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Sponsor
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <?php
                                $fullSponsor = '';
                                if(!empty($user->sponsor)){
                                    $sponsor = Sponsor::findOne($user->sponsor);
                                    if($sponsor){
                                        $fullSponsor = $sponsor->sponsor_name . ' - ' . $sponsor->country_code;
                                    }
                                }
                            ?>
                            <input type="text" id="sponsor" class="form-control" disabled value="<?=strtoupper($fullSponsor)?>">
                            <small class="text-muted"> Contact the student records office for update</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="date-of-birth" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Date of birth
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" id="date-of-birth" name="dateOfBirth" class="form-control" required/>
                            <small class="text-muted"> Must not be younger than 16 years</small>
                        </div>
                    </div>

                    <?php
                    /**
                     * For Kenyan nationals, National ID is mandatory. Passport is optional.
                     * For Non-Kenyan nationals, National ID is not needed. Passport is mandatory.
                     */
                    if($nationality === 'kenyan'):?>
                    <div class="form-group row">
                        <label for="national-id-no" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            National ID number
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control" id="national-id-no" name="nationalIdNumber" value="<?=$user->national_id?>" required>
                            <small class="text-muted"> Mandatory for Kenya nationals</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="passport-no" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Passport number
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control" id="passport-no" name="passportNumber" value="<?=$user->passport_no?>">
                            <small class="text-muted"> Optional for Kenya nationals</small>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="form-group row">
                        <label for="passport-no" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Passport number
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control" id="passport-no" name="passportNumber" value="<?=$user->passport_no?>" required>
                            <small class="text-muted"> Mandatory for Non-Kenya nationals</small>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="form-group row">
                        <label for="birth-cert-no" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Birth certificate number
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control" id="birth-cert-no" name="birthCertificateNumber" value="<?=$user->birth_cert_no?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="primary-phone" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Primary phone
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control profile-phones" id="primary-phone" name="primaryPhone" value="<?=$user->primary_phone_no?>" required>
                            <small class="text-muted"> Include country code. Example format:</small>
                            <small class="text-muted"> 2547XXXXXXXX</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="secondary-phone" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Secondary phone
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control profile-phones" id="secondary-phone" name="secondaryPhone" value="<?=$user->alternative_phone_no?>">
                            <small class="text-muted"> Include country code. Example format:</small>
                            <small class="text-muted"> 2547XXXXXXXX</small>
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
                        <label for="blood-group" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Blood group
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <select class="custom-select form-control" id="blood-group" name="bloodGroup" required>
                                <option value="">-- select --</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="0+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
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
$dateOfBirth = $user->date_of_birth;
$userBloodGroup = $user->blood_group;

$updateProfileJs = <<< JS
const nationality = '$nationality';
const dateOfBirth = '$dateOfBirth';
const userBloodGroup = '$userBloodGroup';
const updateProfileUrl = '$updateProfileUrl'; 
const profileForm = $('#update-profile-form');

const profileLoader = $('#update-profile-form > .loader');
profileLoader.html(loader);
profileLoader.hide();
        
const profileErrorDisplay =  $('#update-profile-form > .error-display');
profileErrorDisplay.hide();

const today = new Date();
const minDate = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate()); // 16 years ago
$('#date-of-birth').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    endDate: minDate,
    todayHighlight: true
}).datepicker('setDate', dateOfBirth)

$('#blood-group').val(userBloodGroup);

let validationRules = {
    'primaryPhone': {
        required: true,
        digitsOnly: true,
        notEqualToGroup: ['.profile-phones']
    },
    'secondaryPhone': {
        digitsOnly: true,
        notEqualToGroup: ['.profile-phones']
    },
    'postCode': {
        required: true,
        digitsOnly: true
    },
    'postAddress': {
        required: true,
        digitsOnly: true
    },
    'town': {
        required: true,
        lettersOnly: true
    },
    'bloodGroup': {
        required: true
    },
    'dateOfBirth': {
        required: true
    }
};

// Conditional validation for National ID and Passport Number
if (nationality === 'kenyan') {
    validationRules['nationalIdNumber'] = {
        required: true,
        digitsOnly: true
    };
    validationRules['passportNumber'] = {
        required: false,
        digitsOnly: true
    };
} else {
   validationRules['nationalIdNumber'] = {
        required: false,
        digitsOnly: true
    };
    validationRules['passportNumber'] = {
        required: true,
        digitsOnly: true
    };
}

console.log(validationRules);
    
profileForm.validate({
    rules: validationRules,
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
