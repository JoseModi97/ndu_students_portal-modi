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
//$this->params['breadcrumbs'][] = ['label' => 'Deferment', 'url' => ['/']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="content-header">
    <div class="page-header">
        <h3>Deferement  <i class="fa fa-angle-right" aria-hidden="true"></i>  Deferment Requests</h3>
    </div>
</div>

<div class="sm-withdrawal-request-index">

        <div class="card-body">
            <?php

            if(empty($pendingRequest)){
                ?>
            <div class="d-flex justify-content-end">
                <?= Html::a('Submit Deferment Request', ['create'], ['class' => 'btn btn-success']) ?>
            </div>
<?php } ?>
<!--    <p>-->
<!--            <h3>--><?php //= Html::encode($this->title) ?><!--</h3>-->
<!---->
<!--    </p>-->

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
                'value' => 'smWithdrawalType.withrawal_type_name'
            ],

            'reason',
//            'request_date',

            [
                'attribute' => 'request_date',
                'value' => function ($model) {
                    return strtoupper(Yii::$app->formatter->asDate($model->request_date, 'php:d-M-Y'));
                },
            ],
            // 'end_date',

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
