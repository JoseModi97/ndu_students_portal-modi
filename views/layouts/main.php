<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/* @var $this View */

/* @var $content string */

use app\assets\AppAsset;
use app\assets\FontAwesomeAsset;
use app\helpers\SmisHelper;
use kartik\growl\Growl;
use yii\bootstrap5\Html;
use yii\helpers\Url;
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
    <link rel="shortcut icon" href="<?= Yii::getAlias('@web'); ?>/img/ndu-arms.png" type="image/x-icon">
    <link rel="icon" href="<?= Yii::getAlias('@web'); ?>/img/ndu-arms.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<?php $this->beginBody() ?>

<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <?php // @todo revert and check for truthy after testing
            if (SmisHelper::studentHasAvailableSessionToJoin()):?>
                <li class="nav-item">
                    <a id="report-to-session" class="nav-link btn btn-success"
                       href="<?= Url::to(['/semester-session-progress/join-session']);?>">
                        <i class="nav-icon fa fa-registered" aria-hidden="true"></i>
                        Report to session
                    </a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['/site/logout']); ?>">
                    <i class="nav-icon fa fa-sign-out" aria-hidden="true"></i>
                    sign out
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <?= $this->render('./sidebar'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?= \app\widgets\Alert::widget() ?>
        <?= $content ?>
    </div>

    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>
            Do you need help? Send a message to <a href="mailto:smis_support@ndu.ac.ke">smis_support@ndu.ac.ke</a>
        </strong>
    </footer>

</div>
<!-- ./wrapper -->

<?php
$flashMap = [
    'success' => [Growl::TYPE_SUCCESS, 'Well done!', 'fas fa-check-circle'],
    'danger' => [Growl::TYPE_DANGER, 'Oh snap!', 'fas fa-times-circle'],
    'error' => [Growl::TYPE_DANGER, 'Oh snap!', 'fas fa-times-circle'],
    'warning' => [Growl::TYPE_WARNING, 'Heads up!', 'fas fa-exclamation-triangle'],
    'info' => [Growl::TYPE_INFO, 'Notice', 'fas fa-info-circle'],
];
$renderFlash = static function (array $flash) use ($flashMap): void {
    $type = $flash['type'] ?? 'info';
    [$flashType, $fallbackTitle, $flashIcon] = $flashMap[$type] ?? $flashMap['info'];

    try {
        echo Growl::widget([
            'type' => $flashType,
            'title' => $flash['title'] ?? $fallbackTitle,
            'icon' => $flashIcon,
            'body' => $flash['message'] ?? '',
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
    } catch (Exception $e) {
    }
};
$flashes = Yii::$app->session->getAllFlashes();
if (!empty($flashes)) {
    if (!empty($flashes['new']) && is_array($flashes['new'])) {
        $renderFlash($flashes['new']);
    }

    if (!empty($flashes['added'])) {
        foreach ($flashes['added'] as $addedFlash) {
            if (is_array($addedFlash)) {
                $renderFlash($addedFlash);
            }
        }
    }
}
?>

<?php $this->registerJs(<<<JS
$(document).on('click', '#report-to-session', function (e) {
    e.preventDefault();
    var url = $(this).attr('href');

    if (confirm('Are you sure you want to report to this session?')) {
        window.location.href = url;
    }
});
JS
); ?>

<?php
$this->endBody()
?>
</body>
</html>
<?php $this->endPage() ?>
