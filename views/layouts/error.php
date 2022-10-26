<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/* @var $this View */
/* @var $content string */

use app\assets\ErrorAsset;
use yii\bootstrap5\Html;
use yii\web\View;

ErrorAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?=Yii::getAlias('@web');?>/img/logo.png" type="image/x-icon">
    <link rel="icon" href="<?=Yii::getAlias('@web');?>/img/logo.png" type="image/x-icon">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>

<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>




