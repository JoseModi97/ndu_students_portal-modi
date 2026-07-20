<?php

use yii\bootstrap5\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var string $gatewayUrl
 * @var array $payload
 * @var string $reference
 * @var float $amount
 * @var string $description
 * @var string|null $refreshedTransId
 */

$this->title = $title;
$refreshedTransId = $refreshedTransId ?? null;
$this->registerCss(<<<CSS
.ecitizen-frame-wrap {
    width: 100%;
    min-height: 560px;
    overflow: hidden;
}
.ecitizen-frame-wrap iframe {
    display: block;
    width: 100%;
    height: 560px;
    border: 0;
}
CSS);
$refreshUrlScript = '';
if ($refreshedTransId !== null) {
    $refreshUrl = Url::to(['invoice', 'trans_id' => $refreshedTransId]);
    $refreshUrlScript = 'window.history.replaceState(null, document.title, '
        . Json::htmlEncode($refreshUrl)
        . ');';
}
$this->registerJs($refreshUrlScript . "var checkoutForm = document.getElementById('ecitizen-checkout-form'); if (checkoutForm) { checkoutForm.submit(); }");
?>

<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?= Html::a('Home', ['/site/index']) ?></li>
                <li class="breadcrumb-item"><?= Html::a('eCitizen Payment', ['index']) ?></li>
                <li class="breadcrumb-item"><?= Html::a('My eCitizen invoices', ['invoices']) ?></li>
                <li class="breadcrumb-item active" aria-current="page">Continue to eCitizen</li>
            </ol>
        </nav>
        <h1 class="m-0">Continue to eCitizen</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <?= $this->render('_checkout_iframe', [
            'gatewayUrl' => $gatewayUrl,
            'payload' => $payload,
            'reference' => $reference,
            'amount' => $amount,
            'description' => $description,
        ]) ?>
    </div>
</section>
