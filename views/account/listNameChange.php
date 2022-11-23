<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var app\models\NameChange $model
 * @var app\models\search\NameChangeRequests $requestsSearchModel
 * @var yii\data\ActiveDataProvider $requestsDataProvider
 * @var bool $canCreateRequest
 */

use app\helpers\SmisHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

$this->title = $title;

?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
        <h1>Account <i class="fa fa-angle-right" aria-hidden="true"></i> Name change</h1>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 requests-status">
                <div class="loader"></div>
                <div class="error-display alert text-center" role="alert"></div>
            </div>
            <div class="col-12">
                <?php
                $surnameCol = [
                    'attribute' => 'current_surname',
                    'label' => 'Current surname',
                    'vAlign' => 'middle',
                ];
                $otherNamesCol = [
                    'attribute' => 'current_othernames',
                    'label' => 'Current other names',
                    'vAlign' => 'middle',
                ];
                $newSurnameCol = [
                    'attribute' => 'new_surname',
                    'label' => 'New surname',
                    'vAlign' => 'middle',
                ];
                $newOtherNamesCol = [
                    'attribute' => 'new_othernames',
                    'label' => 'New other names',
                    'vAlign' => 'middle',
                ];
                $reasonCol = [
                    'attribute' => 'reason',
                    'label' => 'Reason',
                    'vAlign' => 'middle',
                ];
                $dateCol = [
                    'label' => 'Request date',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return SmisHelper::formatDate($model['request_date'], 'd-m-Y');
                    }
                ];
                $statusCol = [
                    'label' => 'Status',
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'value' => function($model){
                        $status = $model['status'];
                        if($status === 'PENDING'){
                            return '<div class="text-center status-pending">pending</div>';
                        }elseif ($status === 'APPROVED'){
                            return '<div class="text-center status-approved">approved</div>';
                        }elseif ($status === 'REVIEW'){
                            return '<div class="text-center status-review">review</div>';
                        }elseif ($status === 'REJECTED'){
                            return '<div class="text-center status-rejected">rejected</div>';
                        }
                    }
                ];
                $actionsCol = [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{download} {edit} {delete}',
                    'contentOptions' => [
                        'style'=>'white-space:nowrap;',
                        'class'=>'kartik-sheet-style kv-align-middle'
                    ],
                    'buttons' => [
                        'download' => function($url, $model){
                            return Html::a('<i class="fa fa-download" aria-hidden="true"></i> &nbsp;',
                                Url::to([
                                    '/account/download-name-change-doc',
                                    'requestId' => $model['name_change_id']
                                ]),
                                [
                                    'title' => 'Download supporting documents',
                                    'class' => 'btn-link action-text-info',
                                    'target' => '_blank',
                                    'data-pjax' => '0'
                                ]
                            );
                        },
                        'edit' => function($url, $model){
                            if($model['status'] === 'PENDING' || $model['status'] === 'REVIEW'){
                                return Html::a('<i class="fa fa-edit" aria-hidden="true"></i>',
                                    Url::to([
                                        '/account/edit-name-change',
                                        'requestId' => $model['name_change_id']
                                    ]),
                                    [
                                        'title' => 'Edit request',
                                        'class' => 'btn-link action-text-info'
                                    ]
                                );
                            }
                        },
                        'delete' => function($url, $model){
                            if($model['status'] === 'PENDING') {
                                return Html::button('<i class="fa fa-trash" aria-hidden="true"></i>', [
                                    'title' => 'Delete request',
                                    'href' => Url::to(['/account/delete-name-change']),
                                    'data-request-id' => $model['name_change_id'],
                                    'class' => 'btn btn-sm delete-request-btn action-text-danger'
                                ]);
                            }
                        }
                    ],
                    'hAlign' => 'center'
                ];
                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    $surnameCol,
                    $otherNamesCol,
                    $newSurnameCol,
                    $newOtherNamesCol,
                    $reasonCol,
                    $dateCol,
                    $statusCol,
                    $actionsCol
                ];

                if($canCreateRequest){
                    $toolbar = [
                        [
                            'content' =>
                                Html::a('New request',
                                    Url::to(['/account/create-name-change']),
                                    [
                                        'title' => 'Request for a name change',
                                        'class' => 'btn btn-success btn-spacer btn-sm'
                                    ]
                                ),
                            'options' => ['class' => 'btn-group mr-2']
                        ],
                        '{export}',
                        '{toggleData}',
                    ];
                }else{
                    $toolbar = [
                        '{export}',
                        '{toggleData}',
                    ];
                }

                try{
                    echo GridView::widget([
                        'id' => 'name-change-grid',
                        'dataProvider' => $requestsDataProvider,
                        'filterModel' => $requestsSearchModel,
                        'columns' => $gridColumns,
                        'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'pjax' => true,
                        'responsiveWrap' => false,
                        'condensed' => true,
                        'hover' => true,
                        'striped' => false,
                        'bordered' => false,
                        'toolbar' => $toolbar,
                        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                        'export' => [
                            'fontAwesome' => true,
                            'label' => 'Export requests'
                        ],
                        'panel' => [
                            'heading' => 'Name change requests',
                        ],
                        'persistResize' => false,
                        'toggleDataOptions' => ['minCount' => 50],
                        'itemLabelSingle' => 'request',
                        'itemLabelPlural' => 'requests',
                    ]);
                }catch (Throwable $ex) {
                    $message = $ex->getMessage();
                    if(YII_ENV_DEV) {
                        $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
                    }
                    throw new ServerErrorHttpException($message, 500);
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?php
$nameChangeDeleteUrl = Url::to(['/account/delete-name-change']);

$nameChangeJs = <<< JS
const nameChangeDeleteUrl = '$nameChangeDeleteUrl';

const requestsLoader = $('.requests-status > .loader');
requestsLoader.html(loader);
requestsLoader.hide();
        
const requestsErrorDisplay =  $('.requests-status > .error-display');
requestsErrorDisplay.hide();

$('#name-change-grid-pjax').on('click', '.delete-request-btn', function (e){
    e.preventDefault();
    if(confirm('Delete request')){
        requestsErrorDisplay.hide();
        requestsLoader.show();
        $.ajax({
            url: nameChangeDeleteUrl,
            type: 'POST',
            data: {id: $(this).attr('data-request-id')}
        }).done(function (data){
            requestsLoader.hide();
            if(!data.success){
                requestsErrorDisplay.html(data.message) 
                requestsErrorDisplay.show();
            }
        }).fail(function (data){
            requestsLoader.hide();
            requestsErrorDisplay.html(data.responseText) 
            requestsErrorDisplay.show();
        });
    }
});
JS;
$this->registerJs($nameChangeJs, yii\web\View::POS_READY);




