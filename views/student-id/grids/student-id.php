<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\StudentIdSearch */

/* @var $dataProvider yii\data\ActiveDataProvider */

use app\models\StudentIdStatus;
use kartik\grid\GridView;
use yii\helpers\Html;

?>
<?php
$gridColumn = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'student_prog_curr_id',
        'value' => function ($model) {
            /* @var $model app\models\StudentId */
            return ($model->studentProgCurr->programmeCurriculum->program->prog_full_name);
        },
    ],
    'issuance_date:date',
    'valid_from:date',
    'valid_to:date',
    'barcode',
    'id_status',
    [
        'class' => 'kartik\grid\ActionColumn',
        'header' => '#',
        'template' => '{update}',
        'buttons' => [
            'update' => function ($url, $model) {
                /* @var $model app\models\StudentId */
                if ($model->id_status != StudentIdStatus::ID_EXPIRED) {
                    return Html::a('<i class="fa fa-file-edit"></i>', [
                        'update-id-status', 'id' => $model->student_id_serial_no
                    ], ['title' => 'Update request', 'class' => 'btn btn-sm btn-outline-default']);
                }
                return '';
            }
        ]
    ],
];
?>
<?= GridView::widget([
    'id' => 'id-history',
    'dataProvider' => $dataProvider,
    'rowOptions' => function ($model, $key, $index, $grid) {
        /* @var $model app\models\StudentId */
        if ($model->id_status === StudentIdStatus::ID_ACTIVE) {
            return ['class' => 'bg-info'];
        } else if ($model->id_status === StudentIdStatus::ID_LOST) {
            return ['class' => 'bg-danger'];
        }
        return [];
    },
    'columns' => $gridColumn, // check this value by clicking GRID COLUMNS SETUP button at top of the page
    'pjax' => false, // pjax is set to always false for this demo
    'bordered' => false,
    'striped' => false,
    'panel' => [
        'before' => '',
    ],
    // set your toolbar
    'toolbar' => [
        [
            'content' => Html::a('<i class="fas fa-plus"></i> New id request', ['create'], [
                'class' => 'btn btn-success'
            ]),
            'options' => ['class' => 'btn-group mr-2 me-2']
        ],
    ],
    'itemLabelSingle' => 'id record',
    'itemLabelPlural' => 'id records'
]); ?>
