<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StudentIdRequest */

$this->title = 'Update Student Id Request: ' . ' ' . $model->request_id;
$this->params['breadcrumbs'][] = ['label' => 'Student Id Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->request_id, 'url' => ['view', 'id' => $model->request_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="student-id-request-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
