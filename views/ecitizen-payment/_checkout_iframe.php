<?php

use yii\bootstrap5\Html;

/**
 * @var string $gatewayUrl
 * @var array $payload
 * @var string $reference
 * @var float $amount
 * @var string $description
 */
?>

<div class="card card-primary card-outline ecitizen-checkout-panel">
    <div class="card-header">
        <h3 class="card-title">eCitizen checkout</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            Complete your payment on the embedded eCitizen screen below. Reference:
            <strong><?= Html::encode($reference) ?></strong>
        </div>

        <form id="ecitizen-checkout-form" method="post" action="<?= Html::encode($gatewayUrl) ?>" target="ecitizen-checkout-frame">
            <?php foreach ($payload as $name => $value): ?>
                <?= Html::hiddenInput($name, $value) . "\n" ?>
            <?php endforeach; ?>
        </form>

        <div class="ecitizen-frame-wrap border rounded bg-light">
            <iframe name="ecitizen-checkout-frame" title="eCitizen checkout"></iframe>
        </div>

        <div class="mt-3">
            <?= Html::submitButton('Reload Checkout', [
                'class' => 'btn btn-outline-primary',
                'form' => 'ecitizen-checkout-form',
            ]) ?>
        </div>
    </div>
</div>
