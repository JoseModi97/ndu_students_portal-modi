<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

/* @var $this View */
/* @var $content string */

use app\assets\AppAsset;
use app\assets\FontAwesomeAsset;
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
    <link rel="shortcut icon" href="<?=Yii::getAlias('@web');?>/img/ndu-arms.png" type="image/x-icon">
    <link rel="icon" href="<?=Yii::getAlias('@web');?>/img/ndu-arms.png" type="image/x-icon">
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
        <?= $content ?>
    </div>

    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>
            Do you need help? Send a message to smis_support@ndu.ac.ke
        </strong>
    </footer>

</div>
<!-- ./wrapper -->

<?php
$flashType = '';
$flashTitle = '';
$flashIcon = '';
$flashes = Yii::$app->session->getAllFlashes();
if(!empty($flashes)){
    if(!empty($flashes['new'])) {
        $flashMessage = $flashes['new']['message'];

        if ($flashes['new']['type'] === 'success') {
            $flashType = Growl::TYPE_SUCCESS;
            $flashTitle = 'Well done!';
            $flashIcon = 'fa fa-check-circle';
        }

        if ($flashes['new']['type'] === 'danger') {
            $flashType = Growl::TYPE_DANGER;
            $flashTitle = 'Oh snap!';
            $flashIcon = 'fa fa-times-circle';
        }

        try {
            echo Growl::widget([
                'type' => $flashType,
                'title' => $flashTitle,
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
        } catch (Exception $e) {
        }
    }

    if(!empty($flashes['added'])){
        foreach ($flashes['added'] as $addedFlash){
            $flashMessage = $addedFlash['message'];
            if($addedFlash['type'] === 'success'){
                $flashType = Growl::TYPE_SUCCESS;
                $flashTitle = 'Well done!';
                $flashIcon = 'fas fa-check-circle';
            }
            if($addedFlash['type'] === 'danger'){
                $flashType = Growl::TYPE_DANGER;
                $flashTitle = 'Oh snap!';
                $flashIcon = 'fas fa-times-circle';
            }

            try {
                echo Growl::widget([
                    'type' => $flashType,
                    'title' => $flashTitle,
                    'icon' => $flashIcon,
                    'body' => $flashMessage,
                    'showSeparator' => true,
                    'delay' => 0,
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
        }
    }
}
?>

<?php
$this->endBody()
?>
</body>
</html>
<?php $this->endPage() ?>
