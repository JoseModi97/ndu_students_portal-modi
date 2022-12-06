<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\StudentIdRequest */

$this->title = $model->request_id;
$this->params['breadcrumbs'][] = ['label' => 'Student Id Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-id-request-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Student Id Request'.' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
            
            <?= Html::a('Update', ['update', 'id' => $model->request_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->request_id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'request_id',
        [
            'attribute' => 'requestType.request_type_id',
            'label' => 'Request Type',
        ],
        [
            'attribute' => 'studentProgCurr.student_prog_curriculum_id',
            'label' => 'Student Prog Curr',
        ],
        'request_date',
        [
            'attribute' => 'status.status_id',
            'label' => 'Status',
        ],
        'receipt_number',
        'source',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>
    <div class="row">
        <h4>SmIdRequestStatus<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnSmIdRequestStatus = [
        'status_name',
    ];
    echo DetailView::widget([
        'model' => $model->status,
        'attributes' => $gridColumnSmIdRequestStatus    ]);
    ?>
    <div class="row">
        <h4>SmIdRequestType<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnSmIdRequestType = [
        'id_type_desc',
    ];
    echo DetailView::widget([
        'model' => $model->requestType,
        'attributes' => $gridColumnSmIdRequestType    ]);
    ?>
    <div class="row">
        <h4>SmStudentProgrammeCurriculum<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnSmStudentProgrammeCurriculum = [
        'student_prog_curriculum_id',
        'student_id',
        'registration_number',
        'prog_curriculum_id',
        'student_category_id',
        'adm_refno',
        [
            'attribute' => 'status.status_id',
            'label' => 'Status',
        ],
    ];
    echo DetailView::widget([
        'model' => $model->studentProgCurr,
        'attributes' => $gridColumnSmStudentProgrammeCurriculum    ]);
    ?>
</div>
