<?php

use app\models\SmWithdrawalRequest;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Breadcrumbs;


/** @var yii\web\View $this */
/** @var app\models\search\SmWithdrawalRequestSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Deferment Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-header">
    <div class="page-header">
        <h1>Deferment  <i class="fa fa-angle-right" aria-hidden="true"></i>  Requests</h1>
    </div>
</div>

<div class="sm-withdrawal-request-index">
    <div class="card">
        <div class="card-body">
            <?php if (empty($pendingRequest)): ?>
                <div class="d-flex justify-content-end mb-3">
                    <?= Html::a('Submit Deferment Request', ['create'], ['class' => 'btn btn-success']) ?>
                </div>
            <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'withdrawal_request_id',

            //'withdrawal_type_id',
                  [
                  'attribute' => 'smWithdrawalType',
                'label' => 'Type',
                'value' => 'smWithdrawalType.withdrawal_type_name'
            ],

            'reason',
//            'request_date',

            [
                'attribute' => 'request_date',
                'value' => function ($model) {
                    return strtoupper(Yii::$app->formatter->asDate($model->request_date, 'php:d-M-Y'));
                },
            ],
//             'supporting_doc_url',
//            [
//                'attribute' => 'supporting_doc_url',
//                'label' => 'Supporting Document',
//                'value' => 'supporting_doc_url'
//            ],

            [
                'label' => 'Supporting Document',
                'attribute' => 'supporting_doc_url',
                'value' => function ($model) {
                 if($model->supporting_doc_url) {
                     return Html::a(' Download', ['download', 'supporting_doc_url' => $model->supporting_doc_url, 'file' => 'filename.pdf'], ['class' => ' bi bi-download btn btn-outline-dark btn-sm']);
                     //  return Yii::$app->urlManager->createUrl(['SmNameChange/download','path'=>$model->document_url,'file'=>'filename.pdf']);
                 }
                 else{
                     return '';
                 }
                },
                'format' => 'raw',
            ],

            'approval_status',
            //'student_id',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, SmWithdrawalRequest $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'withdrawal_request_id' => $model->withdrawal_request_id]);
//                 }
//            ],

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} ',
                'buttons' => [

                    'update' => function ($url, $model, $key) {
                        if($model->approval_status=='PENDING'){
                        return  Html::a(' Update', ['/sm-withdrawal-request/update','withdrawal_request_id' => $model->withdrawal_request_id], ['class' => ' bi bi-pencil-square btn btn-outline-primary btn-sm']);
                  }
                        else{ return '';}
                    },
                ]

            ],

        ],
    ]); ?>


</div>
</div>
</div>
