<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\StudentIdRequestSearch */

/* @var $dataProvider yii\data\ActiveDataProvider */

use app\models\IdRequestStatus;
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
    [
        'attribute' => 'request_type_id',
        'value' => function ($model) {
            /* @var $model app\models\StudentIdRequest */
            return $model->requestType->id_type_desc;
        }
    ],
    [
        'attribute' => 'student_prog_curr_id',
        'value' => function ($model) {
            /* @var $model app\models\StudentIdRequest */
            return ($model->studentProgCurr->programmeCurriculum->program->prog_full_name);
        }
    ],
    'request_date:date',
    [
        'attribute' => 'status_id',
        'value' => function ($model) {
            /* @var $model app\models\StudentIdRequest */
            return $model->status->status_name;
        }
    ],
    'receipt_number',
    'source',
    [
        'class' => 'kartik\grid\ActionColumn',
        'header' => '#',
        'template' => '{update} {delete}',
        'buttons' => [
            'update' => function ($url, $model) {
                /* @var $model app\models\StudentIdRequest */
                if ($model->status->status_name == IdRequestStatus::STATUS_PENDING) {
                    return Html::a('<i class="fa fa-edit"></i>', [
                        'update', 'id' => $model->request_id
                    ], ['title' => 'Update request', 'class' => 'btn btn-sm btn-outline-success']);
                }
                return '';
            },
            'delete' => function ($url, $model) {
                /* @var $model app\models\StudentIdRequest */
                if ($model->status->status_name == IdRequestStatus::STATUS_PENDING) {
                    return Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $model->request_id], [
                        'title' => 'Delete request', 'class' => 'btn btn-sm btn-outline-danger',
                        'data' => [
                            'confirm' => 'Are you absolutely sure ? You will lose all the information about this request with this action.',
                            'method' => 'post',
                        ],
                    ]);
                }
                return '';
            },
        ],
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
//            'filterModel' => $searchModel,
            'columns' => $gridColumn,
            'export' => false,
            // your toolbar can include the additional full export menu
            'toolbar' => false,
        ]); ?>
    </div>
</div>
