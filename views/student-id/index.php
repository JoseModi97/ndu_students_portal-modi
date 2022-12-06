<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\StudentIdRequestSearch */

/* @var $dataProvider yii\data\ActiveDataProvider */

use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'My ID requests';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-header">
    <div class="page-header">
        <h3>Student ID <i class="fa fa-angle-right" aria-hidden="true"></i> <?= Html::encode($this->title) ?> </h3>
    </div>
</div>


<?php
$gridColumn = [
    ['class' => 'kartik\grid\SerialColumn'],
    'request_id',
    [
        'attribute' => 'request_type_id',
        'label' => 'Request Type',
        'value' => function ($model) {
            return $model->requestType->request_type_id;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => \yii\helpers\ArrayHelper::map(\app\models\IdRequestType::find()->asArray()->all(), 'request_type_id', 'request_type_id'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'id request type', 'id' => 'grid-student-id-request-search-request_type_id']
    ],
    [
        'attribute' => 'student_prog_curr_id',
        'label' => 'Student Prog Curr',
        'value' => function ($model) {
            return $model->studentProgCurr->student_prog_curriculum_id;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => \yii\helpers\ArrayHelper::map(\app\models\StudentProgramme::find()->asArray()->all(), 'student_prog_curriculum_id', 'student_prog_curriculum_id'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Smisportal.sm student programme curriculum', 'id' => 'grid-student-id-request-search-student_prog_curr_id']
    ],
    'request_date',
    [
        'attribute' => 'status_id',
        'label' => 'Status',
        'value' => function ($model) {
            return $model->status->status_id;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => \yii\helpers\ArrayHelper::map(\app\models\IdRequestStatus::find()->asArray()->all(), 'status_id', 'status_id'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Smisportal.sm id request status', 'id' => 'grid-student-id-request-search-status_id']
    ],
    'receipt_number',
    'source',
    [
        'class' => 'yii\grid\ActionColumn',
    ],
];
?>


<div class="card">
    <div class="card-header">
        <?= Html::a('New Id request', ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumn,
            'pjax' => true,
            'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-id-request']],
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
            ],
            'export' => false,
            // your toolbar can include the additional full export menu
            'toolbar' => false,
        ]); ?>
    </div>
</div>
