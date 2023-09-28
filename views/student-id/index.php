<?php
/* @var $this View */
/* @var $idRequestSearchModel StudentIdRequestSearch */
/* @var $idRequestProvider ActiveDataProvider */

/* @var $studentIdSearchModel StudentIdSearch */

/* @var $studentIdProvider ActiveDataProvider */

use app\models\search\StudentIdRequestSearch;
use app\models\search\StudentIdSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-header">
    <div class="page-header">
        <h3>Student ID <i class="fa fa-angle-right" aria-hidden="true"></i> <?= Html::encode($this->title) ?> </h3>
    </div>
</div>

<div class="card border-success mb-3">
    <div class="card-body">
        <?= $this->render('grids/student-id', ['dataProvider' => $studentIdProvider]) ?>
    </div>
</div>


<div class="card">
    <div class="card-body">
        <?= $this->render('grids/id-requests', ['dataProvider' => $idRequestProvider]) ?>
    </div>
</div>
