<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/**
 * @var $this View
 * @var $content string
 */

use app\assets\AppAsset;
use app\assets\FontAwesomeAsset;
use kartik\growl\Growl;
use yii\bootstrap5\Html;
use yii\web\ServerErrorHttpException;
use yii\web\View;

FontAwesomeAsset::register($this);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition login-page">
<?php $this->beginBody() ?>

<header class="site-top-header">
    <div class="site-top-header-inner">
        <a href="<?= Yii::getAlias('@web'); ?>" class="site-top-header-logo">
            <img src="<?= Yii::getAlias('@web'); ?>/img/ndu-eng-logo.png" alt="National Defence University-Kenya">
        </a>
        <div class="site-top-header-brand">
            <h1 class="site-top-header-title">National Defence University&#8209;Kenya</h1>
            <p class="site-top-header-tagline">Wisdom. Excellence. Service</p>
        </div>
        <img src="<?= Yii::getAlias('@web'); ?>/img/ndu-at5.png" alt="" class="site-top-header-anniversary" aria-hidden="true">
    </div>
    <span class="site-top-header-stripe" aria-hidden="true"></span>
</header>

<div class="auth-shell">
    <aside class="auth-illustration" aria-hidden="true">
        <div class="auth-illustration-media"></div>
        <div class="auth-illustration-overlay"></div>
        <span class="auth-blob auth-blob-a"></span>
        <span class="auth-blob auth-blob-b"></span>
        <span class="auth-blob auth-blob-c"></span>
        <div class="auth-illustration-content">
            <img src="<?= Yii::getAlias('@web'); ?>/img/ndu-arms.png" alt="" class="auth-illustration-crest">
            <h2>National Defence University&#8209;Kenya</h2>
            <p>Access your registration, fees, results and academic records in one secure place.</p>
        </div>
    </aside>

    <main class="auth-panel">
        <div class="auth-panel-scroll">
            <div class="auth-panel-inner">
                <div class="auth-brand">
                    <img src="<?= Yii::getAlias('@web'); ?>/img/ndu-arms.png" alt="NDU logo" class="auth-brand-logo">
                    <span class="auth-brand-name">NDU Student Portal</span>
                </div>

                <?= $content ?>
            </div>
        </div>
    </main>
</div>

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
.auth-illustration-media{
background-image: url('$img');
}
CSS
);

$this->endBody()
?>

</body>
</html>
<?php $this->endPage() ?>
