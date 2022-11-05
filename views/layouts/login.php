<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var $this View
 * @var $content string
 */

use app\assets\AppAsset;
use kartik\growl\Growl;
use yii\bootstrap5\Html;
use yii\web\ServerErrorHttpException;
use yii\web\View;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?=Yii::getAlias('@web');?>/img/ndu-arms.png" type="image/x-icon">
    <link rel="icon" href="<?=Yii::getAlias('@web');?>/img/ndu-arms.png" type="image/x-icon">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition login-page">
<?php $this->beginBody() ?>

<?= $content ?>

<?php
foreach (Yii::$app->session->getAllFlashes() as $flash) {
    if (!empty($flash)) {
        $type = Growl::TYPE_SUCCESS;
        $flashIcon = 'fas fa-check-circle';
        $title = 'Well done!';
        $flashMessage = $flash['message'];

        if ($flash['type'] === 'danger') {
            $type = Growl::TYPE_DANGER;
            $flashIcon = 'fas fa-times-circle';
            $title = 'Oh snap!';
        }

        try {
            echo Growl::widget([
                'type' => $type,
                'title' => $title,
                'icon' => $flashIcon,
                'body' => $flashMessage,
                'showSeparator' => true,
                'delay' => 0,
                'closeButton' => null,
                'pluginOptions' => [
                    'showProgressbar' => false,
                    'placement' => [
                        'from' => 'bottom',
                        'align' => 'right',
                    ]
                ]
            ]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }
}

$img = Yii::getAlias('@web') . '/img/ndu-model.jpg';

$this->registerCss(
    <<<CSS
body{
background-image: url('$img');
background-position: center;
background-repeat: no-repeat;
background-size: cover;
}
CSS
);

$this->endBody()
?>

</body>
</html>
<?php $this->endPage() ?>
