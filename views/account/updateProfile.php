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
                        <label for="date-of-birth" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Date of birth
                        </label>
                        <div class="col-sm-3 col-md-3 col-lg-3">
                            <input type="text" name="dateOfBirth" id="date-of-birth" class="form-control" required/>
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-2">
                            <input type="text" class="form-control" disabled value="<?=$user->date_of_birth?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="nationality" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Nationality
                        </label>
                        <div class="col-sm-3 col-md-3 col-lg-3">
                            <select class="custom-select form-control" id="nationality" name="nationality" required>
                                <option value="">-- select --</option>
                                <option value="afghan">Afghan</option>
                                <option value="albanian">Albanian</option>
                                <option value="algerian">Algerian</option>
                                <option value="american">American</option>
                                <option value="andorran">Andorran</option>
                                <option value="angolan">Angolan</option>
                                <option value="antiguans">Antiguans</option>
                                <option value="argentinean">Argentinean</option>
                                <option value="armenian">Armenian</option>
                                <option value="australian">Australian</option>
                                <option value="austrian">Austrian</option>
                                <option value="azerbaijani">Azerbaijani</option>
                                <option value="bahamian">Bahamian</option>
                                <option value="bahraini">Bahraini</option>
                                <option value="bangladeshi">Bangladeshi</option>
                                <option value="barbadian">Barbadian</option>
                                <option value="barbudans">Barbudans</option>
                                <option value="batswana">Batswana</option>
                                <option value="belarusian">Belarusian</option>
                                <option value="belgian">Belgian</option>
                                <option value="belizean">Belizean</option>
                                <option value="beninese">Beninese</option>
                                <option value="bhutanese">Bhutanese</option>
                                <option value="bolivian">Bolivian</option>
                                <option value="bosnian">Bosnian</option>
                                <option value="brazilian">Brazilian</option>
                                <option value="british">British</option>
                                <option value="bruneian">Bruneian</option>
                                <option value="bulgarian">Bulgarian</option>
                                <option value="burkinabe">Burkinabe</option>
                                <option value="burmese">Burmese</option>
                                <option value="burundian">Burundian</option>
                                <option value="cambodian">Cambodian</option>
                                <option value="cameroonian">Cameroonian</option>
                                <option value="canadian">Canadian</option>
                                <option value="cape verdean">Cape Verdean</option>
                                <option value="central african">Central African</option>
                                <option value="chadian">Chadian</option>
                                <option value="chilean">Chilean</option>
                                <option value="chinese">Chinese</option>
                                <option value="colombian">Colombian</option>
                                <option value="comoran">Comoran</option>
                                <option value="congolese">Congolese</option>
                                <option value="costa rican">Costa Rican</option>
                                <option value="croatian">Croatian</option>
                                <option value="cuban">Cuban</option>
                                <option value="cypriot">Cypriot</option>
                                <option value="czech">Czech</option>
                                <option value="danish">Danish</option>
                                <option value="djibouti">Djibouti</option>
                                <option value="dominican">Dominican</option>
                                <option value="dutch">Dutch</option>
                                <option value="east timorese">East Timorese</option>
                                <option value="ecuadorean">Ecuadorean</option>
                                <option value="egyptian">Egyptian</option>
                                <option value="emirian">Emirian</option>
                                <option value="equatorial guinean">Equatorial Guinean</option>
                                <option value="eritrean">Eritrean</option>
                                <option value="estonian">Estonian</option>
                                <option value="ethiopian">Ethiopian</option>
                                <option value="fijian">Fijian</option>
                                <option value="filipino">Filipino</option>
                                <option value="finnish">Finnish</option>
                                <option value="french">French</option>
                                <option value="gabonese">Gabonese</option>
                                <option value="gambian">Gambian</option>
                                <option value="georgian">Georgian</option>
                                <option value="german">German</option>
                                <option value="ghanaian">Ghanaian</option>
                                <option value="greek">Greek</option>
                                <option value="grenadian">Grenadian</option>
                                <option value="guatemalan">Guatemalan</option>
                                <option value="guinea-bissauan">Guinea-Bissauan</option>
                                <option value="guinean">Guinean</option>
                                <option value="guyanese">Guyanese</option>
                                <option value="haitian">Haitian</option>
                                <option value="herzegovinian">Herzegovinian</option>
                                <option value="honduran">Honduran</option>
                                <option value="hungarian">Hungarian</option>
                                <option value="icelander">Icelander</option>
                                <option value="indian">Indian</option>
                                <option value="indonesian">Indonesian</option>
                                <option value="iranian">Iranian</option>
                                <option value="iraqi">Iraqi</option>
                                <option value="irish">Irish</option>
                                <option value="israeli">Israeli</option>
                                <option value="italian">Italian</option>
                                <option value="ivorian">Ivorian</option>
                                <option value="jamaican">Jamaican</option>
                                <option value="japanese">Japanese</option>
                                <option value="jordanian">Jordanian</option>
                                <option value="kazakhstani">Kazakhstani</option>
                                <option value="kenyan">Kenyan</option>
                                <option value="kittian and nevisian">Kittian and Nevisian</option>
                                <option value="kuwaiti">Kuwaiti</option>
                                <option value="kyrgyz">Kyrgyz</option>
                                <option value="laotian">Laotian</option>
                                <option value="latvian">Latvian</option>
                                <option value="lebanese">Lebanese</option>
                                <option value="liberian">Liberian</option>
                                <option value="libyan">Libyan</option>
                                <option value="liechtensteiner">Liechtensteiner</option>
                                <option value="lithuanian">Lithuanian</option>
                                <option value="luxembourger">Luxembourger</option>
                                <option value="macedonian">Macedonian</option>
                                <option value="malagasy">Malagasy</option>
                                <option value="malawian">Malawian</option>
                                <option value="malaysian">Malaysian</option>
                                <option value="maldivan">Maldivan</option>
                                <option value="malian">Malian</option>
                                <option value="maltese">Maltese</option>
                                <option value="marshallese">Marshallese</option>
                                <option value="mauritanian">Mauritanian</option>
                                <option value="mauritian">Mauritian</option>
                                <option value="mexican">Mexican</option>
                                <option value="micronesian">Micronesian</option>
                                <option value="moldovan">Moldovan</option>
                                <option value="monacan">Monacan</option>
                                <option value="mongolian">Mongolian</option>
                                <option value="moroccan">Moroccan</option>
                                <option value="mosotho">Mosotho</option>
                                <option value="motswana">Motswana</option>
                                <option value="mozambican">Mozambican</option>
                                <option value="namibian">Namibian</option>
                                <option value="nauruan">Nauruan</option>
                                <option value="nepalese">Nepalese</option>
                                <option value="new zealander">New Zealander</option>
                                <option value="ni-vanuatu">Ni-Vanuatu</option>
                                <option value="nicaraguan">Nicaraguan</option>
                                <option value="nigerien">Nigerien</option>
                                <option value="north korean">North Korean</option>
                                <option value="northern irish">Northern Irish</option>
                                <option value="norwegian">Norwegian</option>
                                <option value="omani">Omani</option>
                                <option value="pakistani">Pakistani</option>
                                <option value="palauan">Palauan</option>
                                <option value="panamanian">Panamanian</option>
                                <option value="papua new guinean">Papua New Guinean</option>
                                <option value="paraguayan">Paraguayan</option>
                                <option value="peruvian">Peruvian</option>
                                <option value="polish">Polish</option>
                                <option value="portuguese">Portuguese</option>
                                <option value="qatari">Qatari</option>
                                <option value="romanian">Romanian</option>
                                <option value="russian">Russian</option>
                                <option value="rwandan">Rwandan</option>
                                <option value="saint lucian">Saint Lucian</option>
                                <option value="salvadoran">Salvadoran</option>
                                <option value="samoan">Samoan</option>
                                <option value="san marinese">San Marinese</option>
                                <option value="sao tomean">Sao Tomean</option>
                                <option value="saudi">Saudi</option>
                                <option value="scottish">Scottish</option>
                                <option value="senegalese">Senegalese</option>
                                <option value="serbian">Serbian</option>
                                <option value="seychellois">Seychellois</option>
                                <option value="sierra leonean">Sierra Leonean</option>
                                <option value="singaporean">Singaporean</option>
                                <option value="slovakian">Slovakian</option>
                                <option value="slovenian">Slovenian</option>
                                <option value="solomon islander">Solomon Islander</option>
                                <option value="somali">Somali</option>
                                <option value="south african">South African</option>
                                <option value="south korean">South Korean</option>
                                <option value="spanish">Spanish</option>
                                <option value="sri lankan">Sri Lankan</option>
                                <option value="sudanese">Sudanese</option>
                                <option value="surinamer">Surinamer</option>
                                <option value="swazi">Swazi</option>
                                <option value="swedish">Swedish</option>
                                <option value="swiss">Swiss</option>
                                <option value="syrian">Syrian</option>
                                <option value="taiwanese">Taiwanese</option>
                                <option value="tajik">Tajik</option>
                                <option value="tanzanian">Tanzanian</option>
                                <option value="thai">Thai</option>
                                <option value="togolese">Togolese</option>
                                <option value="tongan">Tongan</option>
                                <option value="trinidadian or tobagonian">Trinidadian or Tobagonian</option>
                                <option value="tunisian">Tunisian</option>
                                <option value="turkish">Turkish</option>
                                <option value="tuvaluan">Tuvaluan</option>
                                <option value="ugandan">Ugandan</option>
                                <option value="ukrainian">Ukrainian</option>
                                <option value="uruguayan">Uruguayan</option>
                                <option value="uzbekistani">Uzbekistani</option>
                                <option value="venezuelan">Venezuelan</option>
                                <option value="vietnamese">Vietnamese</option>
                                <option value="welsh">Welsh</option>
                                <option value="yemenite">Yemenite</option>
                                <option value="zambian">Zambian</option>
                                <option value="zimbabwean">Zimbabwean</option>
                            </select>
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-2">
                            <input type="text" class="form-control" disabled value="<?=strtoupper($user->nationality)?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="national-id-no" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            National Id number
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control" id="national-id-no" name="nationalIdNumber" value="<?=$user->national_id?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="birth-cert-no" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Birth certificate number
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control" id="birth-cert-no" name="birthCertificateNumber" value="<?=$user->birth_cert_no?>" required>
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
                        <label for="service" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Service
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <select class="custom-select form-control" id="service" name="service" required>
                                <option value="">-- select --</option>
                                <option value="Kenya army">Kenya army</option>
                                <option value="Kenya navy">Kenya navy</option>
                                <option value="kenya air force">Kenya air force</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="selected-service" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Selected service
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" id="selected-service" class="form-control" disabled value="<?=$user->service?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="service-no" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Service number
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <input type="text" class="form-control" id="service-no" name="serviceNumber" value="<?=$user->service_number?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="sponsor" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Sponsor
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <select class="custom-select form-control" id="sponsor" name="sponsor" required>
                                <option value="">-- select --</option>
                                <?php foreach ($sponsors as $sponsor):?>
                                    <option value="<?=$sponsor['sponsor_id']?>"><?=$sponsor['sponsor_name'] . ' - ' . $sponsor['country_code']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="selected-sponsor" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                            Selected sponsor
                        </label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <?php if(!empty($user->sponsor)):
                                $sponsor = Sponsor::findOne($user->sponsor);
                                if($sponsor):
                            ?>
                            <input type="text" id="selected-sponsor" class="form-control" disabled value="<?=$sponsor->sponsor_name . ' - ' . $sponsor->country_code?>">
                            <?php endif;
                            endif;?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="blood-group" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label required-control-label">
                            Blood group
                        </label>
                        <div class="col-sm-3 col-md-3 col-lg-3">
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
                        <div class="col-sm-2 col-md-2 col-lg-2">
                            <input type="text" class="form-control" disabled value="<?=$user->blood_group?>">
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

$("#date-of-birth").datepicker({
    changeMonth: true,
    changeYear: true,
    maxDate: 0
});
        
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
        },
        'nationalIdNumber': {
           required: true
        },
        'birthCertificateNumber': {
           required: true
        },
        'service': {
            required: true
        },
        'serviceNumber': {
           required: true
        },
        'sponsor': {
           required: true
        },
        'nationality': {
           required: true
        },
        'bloodGroup': {
           required: true
        },
        'dateOfBirth': {
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
