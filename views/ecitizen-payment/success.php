<?php

use yii\bootstrap5\Html;

/**
 * @var yii\web\View $this
 * @var string $reference
 */

$this->title = $title;
?>

<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">eCitizen payment status</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <p class="mb-3">
                    Your eCitizen payment request has been received. The fee statement will update after eCitizen sends
                    the payment notification.
                </p>
                <p><strong>Reference:</strong> <?= Html::encode($reference) ?></p>
                <?= Html::a('View eCitizen payments', ['index'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</section>
