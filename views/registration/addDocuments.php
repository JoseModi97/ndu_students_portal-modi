<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var app\models\User $user
 * @var string[] $documents
 * @var bool $submitted
 * @var bool $canBeSubmitted
 * @var string[] $submittedDocsIds
 */

use yii\helpers\Url;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
        <h1>Registration  <i class="fa fa-angle-right" aria-hidden="true"></i>  Upload Documents</h1>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Registration documents
                    </h3>
                </div>
            <div class="card-body">
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-12">
                        <div class="bg-warning text-center" style="padding:20px 0; border-radius: .25rem">
                            All mandatory documents must be uploaded before submitting for approval.
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-12">
                        <div class="float-right">
                            <a href="<?=Url::to(['/registration/index'])?>" class="btn view-reg-files-link">
                                View uploaded
                            </a>
                            <?php if(!$submitted && $canBeSubmitted):?>
                                <button id="btn-submit-docs" class="btn btn-success">
                                    Submit for approval
                                </button>
                            <?php else:?>
                                <button disabled class="btn btn-default">
                                    Submit for approval
                                </button>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fa fa-file"></i> &nbsp;
                                        Mandatory documents
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <ul>
                                        <?php if(!empty($documents)):
                                            foreach ($documents as $document):
                                                if($document['document']['required']):
                                                    $docId = $document['document']['document_id'];
                                                    if(in_array($docId, $submittedDocsIds)){
                                                        $uploadStatus = '<small class="text-success">uploaded</small';
                                                    }else{
                                                        $uploadStatus = '<small class="text-danger">missing</small';
                                                    }
                                                    ?>
                                                    <li>
                                                        <?=$document['document']['document_name'] . ' ' . $uploadStatus;?>
                                                    </li>
                                                <?php endif;
                                            endforeach;
                                        endif;?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fa fa-file"></i> &nbsp;
                                        Other documents
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <ul>
                                        <?php if(!empty($documents)):
                                            foreach ($documents as $document):
                                                if(!$document['document']['required']):
                                                    $docId = $document['document']['document_id'];
                                                    if(in_array($docId, $submittedDocsIds)){
                                                        $uploadStatus = '<small class="text-success">uploaded</small';
                                                    }else{
                                                        $uploadStatus = '<small class="text-danger">missing</small';
                                                    }
                                                ?>
                                                <li>
                                                    <?=$document['document']['document_name'] . ' ' . $uploadStatus;?>
                                                </li>
                                            <?php endif;
                                            endforeach;
                                        endif;?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <form id="reg-documents-form" onsubmit="return false" method="post" action="#" enctype="multipart/form-data">
                            <div class="loader"></div>
                            <div class="error-display alert text-center" role="alert"></div>
                            <div class="row">
                                <?php if(!empty($documents)):
                                    foreach ($documents as $document):
                                        $docName = $document['document']['document_name'];
                                        $docDesc = $document['document']['document_desc'];
                                        $labelId = 'document-' . $document['document']['document_id'];
                                        ?>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="<?=$labelId?>"><?=$docName?></label>
                                                <input type="file" class="form-control" id="<?=$labelId?>" name="<?=$labelId?>">
                                                <small id="<?=$labelId?>-desc" class="text-muted"><?=$docDesc?></small>
                                            </div>
                                        </div>
                                    <?php endforeach;
                                endif;?>

                                <div class="form-group">
                                    <div class="col-12">
                                        <button type="submit" id="btn-upload-docs" class="btn btn-success">Upload</button>
                                    </div>
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
$uploadDocumentsUrl = Url::to(['/registration/upload']);
$docsSubmitUrl = Url::to(['/registration/submit-documents']);

$uploadFilesJs = <<< JS
const docsSubmitUrl = '$docsSubmitUrl';
const uploadDocumentsUrl = '$uploadDocumentsUrl';

const regDocumentsForm = $('#reg-documents-form');
regDocumentsForm.validate();

const regDocsLoader = $('#reg-documents-form > .loader');
regDocsLoader.html(loader);
regDocsLoader.hide();

const regDocsErrorDisplay =  $('#reg-documents-form > .error-display');
regDocsErrorDisplay.hide();

$('#btn-upload-docs').click(function (e){
    e.preventDefault();
    if(regDocumentsForm.valid()){
        if(confirm('Upload documents?')){
            regDocsErrorDisplay.hide();
            regDocsLoader.show();
            
            let formData = new FormData(regDocumentsForm[0]);
            
            $.ajax({
                url: uploadDocumentsUrl,
                type: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                data: formData
            }).done(function (data){
                regDocsLoader.hide();
                if(!data.success){
                    regDocsErrorDisplay.html(data.message);
                    regDocsErrorDisplay.show(); 
                    errorToaster(data.message);
                }
            }).fail(function (data){
                regDocsLoader.hide();
                regDocsErrorDisplay.html(data.responseText)
                regDocsErrorDisplay.show();
            });
        }
    }else{
        regDocsLoader.hide();
        regDocsErrorDisplay.html('There were errors below, correct them and try submitting again.')
        regDocsErrorDisplay.show();
    }
});

$('#btn-submit-docs').click(function (e){
    e.preventDefault();
    if(confirm('Submit documents?')){
        regDocsErrorDisplay.hide();
        regDocsLoader.show();
        $.ajax({
            url: docsSubmitUrl,
            type: 'POST',
            data: {}
        }).done(function (data){
            regDocsLoader.hide();
            if(!data.success){
                regDocsErrorDisplay.html(data.message) 
                regDocsErrorDisplay.show();
                errorToaster(data.message);
            }
        }).fail(function (data){
            regDocsLoader.hide();
            regDocsErrorDisplay.html(data.responseText) 
            regDocsErrorDisplay.show();
        });
    }
});

JS;
$this->registerJs($uploadFilesJs, yii\web\View::POS_READY);


