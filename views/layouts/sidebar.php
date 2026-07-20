<?php

/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

use yii\helpers\Url;

$currentRoute = Yii::$app->controller->route;

$isActive = static function (array $routes) use ($currentRoute): bool {
    return in_array($currentRoute, $routes, true);
};

$isActivePrefix = static function (string $prefix, array $except = []) use ($currentRoute): bool {
    if (in_array($currentRoute, $except, true)) {
        return false;
    }
    $prefix = rtrim($prefix, '/');
    return $currentRoute === $prefix || str_starts_with($currentRoute, $prefix . '/');
};

$navClass = static function (bool $active): string {
    return 'nav-link' . ($active ? ' active' : '');
};
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= Url::to(['/site/index']); ?>" class="brand-link">
        <img src="<?= Yii::getAlias('@web'); ?>/img/ndu-arms.png" alt="NDU logo" class="brand-image">
        <span class="brand-text">
            NDU Student Portal
            <small>National Defence University-Kenya</small>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block btn-link">
                    <?= Yii::$app->user->identity->surname . ' ' . Yii::$app->user->identity->other_names; ?>
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
                <li class="nav-item">
                    <a href="<?= Url::to(['/account/index']); ?>" class="<?= $navClass($isActive([
                        'account/index', 'account/update-profile', 'account/update-password', 'account/update-email',
                    ])); ?>">
                        <i class="nav-icon fa fa-cog" aria-hidden="true"></i>
                        <p>Account</p>
                    </a>
                </li>
                <?php
                if (Yii::$app->user->identity->admission_status === 'REGISTERED'):
                ?>
                    <li class="nav-item">
                        <a href="<?= Url::to(['/account/list-name-change']); ?>" class="<?= $navClass($isActive([
                            'account/list-name-change', 'account/create-name-change', 'account/edit-name-change',
                            'account/store-name-change', 'account/update-name-change', 'account/delete-name-change',
                            'account/download-name-change-doc',
                        ])); ?>">
                            <i class="nav-icon fas fa-edit" aria-hidden="true"></i>
                            <p>Name change</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::to(['/student-id']); ?>" class="<?= $navClass($isActivePrefix('student-id')); ?>">
                            <i class="nav-icon fa fa-id-card" aria-hidden="true"></i>
                            <p>Student ID</p>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="<?= Url::to(['/registration/index']); ?>" class="<?= $navClass($isActive([
                            'registration/index', 'registration/registration-document',
                            'registration/download-document', 'registration/delete-document',
                        ])); ?>">
                            <i class="nav-icon fa fa-file" aria-hidden="true"></i>
                            <p>My registration documents</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= Url::to(['/registration/add-documents']); ?>" class="<?= $navClass($isActive([
                            'registration/add-documents', 'registration/upload', 'registration/submit-documents',
                        ])); ?>">
                            <i class="nav-icon fa fa-upload" aria-hidden="true"></i>
                            <p>Add registration documents</p>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="<?= Url::to(['/sm-withdrawal-request']); ?>" class="<?= $navClass($isActivePrefix('sm-withdrawal-request')); ?>">
                        <i class="nav-icon fa fa-forward" aria-hidden="true"></i>
                        <p>Deferment</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= Url::to(['/ecitizen/payment/index']); ?>" class="<?= $navClass($isActivePrefix('ecitizen')); ?>">
                        <i class="nav-icon fa fa-credit-card" aria-hidden="true"></i>
                        <p>eCitizen Payment</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= Url::to(['/bill/raise-invoice']); ?>" class="<?= $navClass($isActive(['bill/raise-invoice', 'bill/accept-invoice'])); ?>">
                        <i class="nav-icon fas fa-file-invoice" aria-hidden="true"></i>
                        <p>Raise Invoice</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= Url::to(['/bill/my-invoices']); ?>" class="<?= $navClass($isActive(['bill/my-invoices', 'bill/download-invoice'])); ?>">
                        <i class="nav-icon fas fa-file-invoice" aria-hidden="true"></i>
                        <p>My Invoices</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= Url::to(['/courses/fee-statement']); ?>" class="<?= $navClass($isActive(['courses/fee-statement'])); ?>">
                        <i class="nav-icon fa fa-file-invoice-dollar" aria-hidden="true"></i>
                        <p>Fee Statement</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= Url::to(['/refund-requests/default/index']); ?>" class="<?= $navClass($isActivePrefix('refund-requests')); ?>">
                        <i class="nav-icon fa fa-money-bill-wave" aria-hidden="true"></i>
                        <p>Refund Request</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= Url::to(['/courses']); ?>" class="<?= $navClass($isActivePrefix('courses', ['courses/fee-statement'])); ?>">
                        <i class="nav-icon fa fa-registered" aria-hidden="true"></i>
                        <p>Course Registration</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-forward" aria-hidden="true"></i>
                        <p>Student Exams</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-forward" aria-hidden="true"></i>
                        <p>Timetables</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= Url::to(['/results']); ?>" class="<?= $navClass($isActivePrefix('results')); ?>">
                        <i class="nav-icon fa fa-forward" aria-hidden="true"></i>
                        <p>Results</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-forward" aria-hidden="true"></i>
                        <p>Help</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
