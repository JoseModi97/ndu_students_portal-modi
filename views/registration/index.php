<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var app\models\User $user
 * @var string[] $submittedDocs
 * @var bool $submitted
 */

use yii\helpers\Url;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
        <h1>Registration  <i class="fa fa-angle-right" aria-hidden="true"></i>  Uploaded Documents</h1>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-12 doc-status">
            <div class="loader"></div>
            <div class="error-display alert text-center" role="alert">
            </div>
        </div>

        <?php if(!empty($submittedDocs)):
            if($submitted):?>
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-12">
                    <div class="bg-info text-center" style="padding:10px 0; border-radius: .25rem">
                        Your documents have been submitted for approval
                    </div>
                </div>
            </div>
        <?php else:?>
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-12">
                    <button id="btn-submit-docs" class="btn btn-success float-right">
                        Submit documents for approval
                    </button>
                </div>
            </div>
        <?php endif;
        endif;?>

        <div class="row">
            <?php if(!empty($submittedDocs)):
                foreach ($submittedDocs as $submittedDoc):
                    $docId = $submittedDoc['student_document_id'];
                    $docStatus = $submittedDoc['verify_status'];

                    $docIcon = '';
                    $docClass = '';
                    if($docStatus === 'PENDING'){
                        $docIcon = '<i class="fa fa-clock-o text-info" aria-hidden="true"></i>';
                        $docClass = 'text-info';
                    }elseif ($docStatus === 'APPROVED'){
                        $docIcon = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
                        $docClass = 'text-success';
                    }elseif ($docStatus === 'NOT_APPROVED'){
                        $docIcon = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
                        $docClass = 'text-danger';
                    }
                    ?>
                    <!-- ./col -->
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fa fa-file"></i>
                                    <?= $submittedDoc['requiredDocument']['document']['document_name']?>
                                    <?=$docIcon?>
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Status</dt>
                                    <dd class="col-sm-8 <?=$docClass?> ">
                                        <?= str_replace('_', ' ', $docStatus)?>
                                    </dd>
                                    <dt class="col-sm-4">Comments</dt>
                                    <dd class="col-sm-8"><?= $submittedDoc['doc_comments']?></dd>
                                    <dt class="col-sm-4">Actions</dt>
                                    <dd class="col-sm-8">
                                        <div class="row">
                                            <div class="col-6">
                                                <a class="btn reg-file-download-btn" href="<?= Url::to(['/registration/download-document', 'id' => $docId])?>">download</a>
                                            </div>

                                            <?php if(!$submitted):?>
                                                <div class="col-6">
                                                    <button class="btn reg-file-delete-btn btn-delete-doc" data-id="<?=$docId?>">delete</button>
                                                </div>
                                            <?php endif;?>
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- ./col -->
                <?php endforeach;
            endif;?>
        </div>
    </div>
</section>

<?php
$docDeleteUrl = Url::to(['/registration/delete-document']);
$docsSubmitUrl = Url::to(['/registration/submit-documents']);

$regDocsJs = <<< JS
const docDeleteUrl = '$docDeleteUrl';
const docsSubmitUrl = '$docsSubmitUrl';

const uploadedDocsLoader = $('.doc-status > .loader');
uploadedDocsLoader.html(loader);
uploadedDocsLoader.hide();
        
const uploadedDocsErrorDisplay =  $('.doc-status > .error-display');
uploadedDocsErrorDisplay.hide();

$('.btn-delete-doc').click(function (e){
    e.preventDefault();
    if(confirm('Delete document?')){
        uploadedDocsErrorDisplay.hide();
        uploadedDocsLoader.show();
        $.ajax({
            url: docDeleteUrl,
            type: 'POST',
            data: {id: $(this).attr('data-id')}
        }).done(function (data){
            uploadedDocsLoader.hide();
            if(!data.success){
                uploadedDocsErrorDisplay.html(data.message) 
                uploadedDocsErrorDisplay.show();
            }
        }).fail(function (data){
            uploadedDocsLoader.hide();
            uploadedDocsErrorDisplay.html(data.responseText) 
            uploadedDocsErrorDisplay.show();
        });
    }
});

$('#btn-submit-docs').click(function (e){
    e.preventDefault();
    if(confirm('Submit documents?')){
        uploadedDocsErrorDisplay.hide();
        uploadedDocsLoader.show();
        $.ajax({
            url: docsSubmitUrl,
            type: 'POST',
            data: {}
        }).done(function (data){
            uploadedDocsLoader.hide();
            if(!data.success){
                uploadedDocsErrorDisplay.html(data.message) 
                uploadedDocsErrorDisplay.show();
            }
        }).fail(function (data){
            uploadedDocsLoader.hide();
            uploadedDocsErrorDisplay.html(data.responseText) 
            uploadedDocsErrorDisplay.show();
        });
    }
});
JS;
$this->registerJs($regDocsJs, yii\web\View::POS_READY);




