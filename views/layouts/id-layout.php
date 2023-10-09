<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\assets\FontAwesomeAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

FontAwesomeAsset::register($this);
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= Yii::getAlias('@web'); ?>/studentreg/img/ndu-arms.png" type="image/x-icon">
    <link rel="icon" href="<?= Yii::getAlias('@web'); ?>/studentreg/img/ndu-arms.png" type="image/x-icon">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>
<main>
    <div class="container-fluid">
        <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs'] ?? [],]); ?>
        <?= Alert::widget(); ?>
        <?= $content; ?>
    </div>
</main>

<footer class="main-footer">
    <strong>
        Do you need help? Send a message to <a href="mailto:smis_support@ndu.ac.ke">smis_support@ndu.ac.ke</a>
    </strong>
</footer>

<?php
$this->endBody()
?>
</body>
</html>
<?php $this->endPage() ?>
