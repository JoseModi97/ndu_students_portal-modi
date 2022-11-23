<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var app\models\User $user
 */

use yii\helpers\Url;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
        <h1>Account <i class="fa fa-angle-right" aria-hidden="true"></i> Change name</h1>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2 offset-lg-2">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Create name change request
                        </h3>
                    </div>
                    <div class="card-body">
                        <form id="change-name-form" onsubmit="return false" method="post" action="#">
                            <div class="loader"></div>

                            <div class="error-display alert text-center" role="alert">
                            </div>

                            <div class="form-group row">
                                <label for="surname" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                                    Surname
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <input type="text" disabled class="form-control" id="surname" name="surname" value="<?=strtoupper($user->surname);?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="current-other-names" class="col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                                    Other names
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <input type="text" disabled class="form-control" id="current-other-names" name="otherNames" value="<?=strtoupper($user->other_names);?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="new-surname" class="required-control-label col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                                    New Surname
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <input type="text" class="form-control" id="new-surname" name="newSurname">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="new-other-names" class="required-control-label col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                                    New Other names
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <input type="text" class="form-control" id="new-other-names" name="newOtherNames">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="reason" class="required-control-label col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                                    Reason
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <textarea class="form-control" rows="3" id="reason" name="reason"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="document" class="required-control-label col-sm-3 col-md-3 col-lg-3 offset-md-2 offset-lg-2 text-md-right text-lg-right col-form-label">
                                    Supporting documents
                                </label>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                    <input type="file" class="form-control" id="document" name="document">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-5 col-md-5 col-lg-5 offset-md-5 offset-lg-5">
                                    <button type="submit" id="btn-change-name" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$changeNameUrl = Url::to(['/account/store-name-change']);

$nameChangeJs = <<< JS
const changeNameUrl = '$changeNameUrl'; 
const changeNameForm = $('#change-name-form');

const changeNameLoader = $('#change-name-form > .loader');
changeNameLoader.html(loader);
changeNameLoader.hide();
        
const changeNameErrorDisplay =  $('#change-name-form > .error-display');
changeNameErrorDisplay.hide();

changeNameForm.validate({
    rules: {
        'newSurname': {
            required: true
        },
        'newOtherNames': {
            required: true
        },
        'reason': {
            required: true
        },
        'document': {
            required: true
        }
    },
    messages: {
        'document': {
            required: 'Supporting documents are required.'
        }
    }
});

$('#btn-change-name').click(function (e){
    e.preventDefault();
    if(changeNameForm.valid()){
        if(confirm('Submit request')){
            changeNameErrorDisplay.hide();
            changeNameLoader.show();
            let formData = new FormData(changeNameForm[0]);
            $.ajax({
                url: changeNameUrl,
                type: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                data: formData
            }).done(function (data){
                changeNameLoader.hide();
                if(!data.success){
                    changeNameErrorDisplay.html(data.message);
                    changeNameErrorDisplay.show(); 
                    errorToaster(data.message);
                }
            }).fail(function (data){
                changeNameLoader.hide();
                changeNameErrorDisplay.html(data.responseText)
                changeNameErrorDisplay.show();
            });
        }
    }else{
        changeNameLoader.hide();
        changeNameErrorDisplay.html('There were errors below, correct them and try submitting again.');   
        changeNameErrorDisplay.show();
    }
});

JS;
$this->registerJs($nameChangeJs, yii\web\View::POS_READY);



