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
        'width' => '13%',
        'buttons' => [
            'update' => function ($url, $model) {
                /* @var $model app\models\StudentId */
                if ($model->id_status == StudentIdStatus::ID_ACTIVE) {
                    return Html::a('REPORT AS LOST', [
                        'report-lost-id', 'id' => $model->student_id_serial_no
                    ], ['title' => 'Report this id as lost', 'class' => 'btn btn-sm btn-danger']);
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
    'columns' => $gridColumn,
    'pjax' => false,
    'bordered' => true,
    'striped' => false,
    'panel' => [
        'before' => '',
    ],
    // set your toolbar
    'toolbar' => \app\models\StudentId::hasActiveAndValidId() ? '' : [
        [
            'content' => Html::a('<i class="fas fa-plus"></i> Request new ID', ['new-id'], [
                'class' => 'btn btn-success'
            ]),
            'options' => [
                'class' => 'btn-group mr-2 me-2'
            ]
        ],
    ],
    'itemLabelSingle' => 'id record',
    'itemLabelPlural' => 'id records'
]); ?>
