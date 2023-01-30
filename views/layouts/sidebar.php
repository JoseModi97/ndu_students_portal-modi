<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

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
                <li class="nav-item">
                    <a href="<?= Url::to(['/account/index']); ?>" class="nav-link">
                        <i class="nav-icon fa fa-cog" aria-hidden="true"></i>
                        <p>Account</p>
                    </a>
                </li>
                <?php
                if(Yii::$app->user->identity->admission_status === 'REGISTERED'):
                ?>
                <li class="nav-item">
                    <a href="<?= Url::to(['/account/list-name-change']); ?>" class="nav-link">
                        <i class="nav-icon fas fa-edit" aria-hidden="true"></i>
                        <p>Name change</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= Url::to(['/student-id']); ?>" class="nav-link">
                        <i class="nav-icon fa fa-id-card" aria-hidden="true"></i>
                        <p>Student ID</p>
                    </a>
                </li>
                <?php else:?>
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
                    <a href="<?= Url::to(['/sm-withdrawal-request']); ?>" class="nav-link">
                        <i class="nav-icon fa fa-forward" aria-hidden="true"></i>
                        <p>Deferment</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-forward" aria-hidden="true"></i>
                        <p>Student fees</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-forward" aria-hidden="true"></i>
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