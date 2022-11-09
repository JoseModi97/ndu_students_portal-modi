<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var app\models\User $user
 * @var string[] $documents
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

            <div class="col-sm-12 col-md-7 col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Your profile
                        </h3>
                    </div>

                    <div class="card-body">
                        <form id="reg-documents-form" onsubmit="return false" method="post" action="#" enctype="multipart/form-data">
                            <div class="loader"></div>

                            <div class="error-display alert text-center" role="alert">
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="documents-selector" class="required-control-label">Document type</label>
                                        <select type="text" class="form-control" id="documents-selector" name="documentsSelector">
                                            <option value="">-Select document-</option>
                                            <?php if(!empty($documents)):
                                                foreach ($documents as $document):
                                                    ?>
                                                    <option value="<?=$document['fk_document_id']?>">
                                                        <?=$document['document']['document_name']?>
                                                    </option>
                                                <?php endforeach;
                                            endif;?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="reg-documents">
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <div class="col-12">
                                        <button type="submit" id="btn-upload-docs" class="btn btn-success">Upload</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>

            <div class="col-sm-12 col-md-5 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Uploaded documents
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <dl>
                            <dt>Description lists</dt>
                            <dd>A description list is perfect for defining terms.</dd>
                            <dt>Euismod</dt>
                            <dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
                            <dd>Donec id elit non mi porta gravida at eget metus.</dd>
                            <dt>Malesuada porta</dt>
                            <dd>Etiam porta sem malesuada magna mollis euismod.</dd>
                        </dl>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

        </div>
    </div>
</section>

<?php
$regDocumentUrl = Url::to(['/registration/registration-document']);
$uploadDocumentsUrl = Url::to(['/registration/upload']);

/**
 * @see js/upload_reg_documents.js for additional methods
 */
$uploadFilesJs = <<< JS
const regDocumentUrl = '$regDocumentUrl';
const uploadDocumentsUrl = '$uploadDocumentsUrl';

$('#documents-selector').on('change', function (e){
    addRegDocument.call(this, e, regDocumentUrl);
});

$('.reg-documents').on('click', '.remove-reg-document', function (e){
      removeRegDocument.call(this, e);
});

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

JS;
$this->registerJs($uploadFilesJs, yii\web\View::POS_READY);

