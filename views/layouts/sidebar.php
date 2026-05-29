<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

use app\helpers\SmisHelper;
use yii\helpers\Url;

?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block btn-link">
                    <?= Yii::$app->user->identity->other_names; ?>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-home" aria-hidden="true"></i>
                        <p>Home</p>
                    </a>
                </li>

                <?php
                if (SmisHelper::studentHasAvailableSessionToJoin()):?>
                    <li class="nav-item">
                        <a id="report-to-session" class="nav-link"
                           href="<?= Url::to(['/semester-session-progress/join-session']); ?>">
                            <i class="nav-icon fa fa-calendar-check" aria-hidden="true"></i>
                            Report to session
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a href="<?= Url::to(['/account/index']); ?>" class="nav-link">
                        <i class="nav-icon fa fa-cog" aria-hidden="true"></i>
                        <p>Account</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= Url::to(['/ecitizen-payment/index']); ?>" class="nav-link">
                        <i class="nav-icon fa fa-credit-card" aria-hidden="true"></i>
                        <p>eCitizen Payment</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= Url::to(['/refund-requests/default/index']); ?>" class="nav-link">
                        <i class="nav-icon fa fa-money-bill-wave" aria-hidden="true"></i>
                        <p>Refund Request</p>
                    </a>
                </li>

                <?php // Only accessible to fully registered students
                if (Yii::$app->user->identity->admission_status === 'REGISTERED'):
                    ?>
                    <li class="nav-item">
                        <a href="<?= Url::to(['/courses']); ?>" class="nav-link">
                            <i class="nav-icon fa fa-clipboard-list" aria-hidden="true"></i>
                            <p>Course Registration</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::to(['/results']); ?>" class="nav-link">
                            <i class="nav-icon fa fa-poll" aria-hidden="true"></i>
                            <p>Results</p>
                        </a>
                    </li>
                <?php // Only accessible to admitted students awaiting clearance on their registration documents
                else: ?>
                    <li class="nav-item">
                        <a href="<?= Url::to(['/registration/index']); ?>" class="nav-link">
                            <i class="nav-icon fa fa-file" aria-hidden="true"></i>
                            <p>My registration documents</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::to(['/registration/add-documents']); ?>" class="nav-link">
                            <i class="nav-icon fa fa-upload" aria-hidden="true"></i>
                            <p>Add registration documents</p>
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
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
