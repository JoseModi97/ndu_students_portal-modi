<?php

/**
 * @var yii\web\View $this
 * @var string $gatewayUrl
 * @var array $payload
 * @var string $reference
 * @var float $amount
 * @var string $description
 */

$this->title = $title;
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
$this->registerJs("var checkoutForm = document.getElementById('ecitizen-checkout-form'); if (checkoutForm) { checkoutForm.submit(); }");
?>

<div class="content-header">
    <div class="container-fluid">
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
