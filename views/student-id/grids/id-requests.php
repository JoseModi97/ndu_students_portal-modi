<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\StudentIdRequestSearch */

/* @var $dataProvider yii\data\ActiveDataProvider */

use kartik\grid\GridView;

?>

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
    [
        'attribute' => 'status_id',
        'value' => function ($model) {
            /* @var $model app\models\StudentIdRequest */
            return $model->status->status_name;
        }
    ],
    'source',
    'request_date:date',
];
?>



<?= GridView::widget([
    'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
    'columns' => $gridColumn,
    'export' => false,
    // your toolbar can include the additional full export menu
    'toolbar' => false,
]); ?>

