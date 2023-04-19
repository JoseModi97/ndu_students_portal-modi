<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 4/17/2023
 * @time: 10:04 PM
 */

/**
 * @var string $name
 * @var string $regNumber
 * @var string[] $currentSessionDetails
 * @var string[] $courses
 */

use app\helpers\SmisHelper;
use yii\web\ServerErrorHttpException;
?>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="text-center" style="padding: 20px;">
                    <img class="mx-auto d-block" style="height: 100px;"
                         src="<?=Yii::getAlias('@web');?>/img/ndu-arms.png"
                         alt="avatar">
                </div>
                <h3 class="profile-username text-center"><?=$name?></h3>
                <p class="text-center"><?=$regNumber?></p>
                <p class=" text-muted text-center">
                    <?=$currentSessionDetails['academicSession'] . ' | ' . $currentSessionDetails['programme'] . ' | Year ' .
                    $currentSessionDetails['level'] . ' | Semester ' . $currentSessionDetails['semester'] ?>
                </p>
                <table class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Venue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(!empty($courses)):
                            $count = 1;
                            foreach ($courses as $course):
                            $code = $course['programmeCurriculumCourse']['course']['course_code'];
                            $name = $course['programmeCurriculumCourse']['course']['course_name'];
                            try {
                                $date = SmisHelper::formatDate($course['exam_date'], 'd-m-Y h:i');
                            } catch (\Exception $ex) {
                                $message = $ex->getMessage();
                                if(YII_ENV_DEV) {
                                    $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
                                }
                                throw new ServerErrorHttpException($message, 500);
                            }
                            $venue = $course['examVenue']['room_name'] . ' (' . $course['examVenue']['room_code'] . ')';
                            $venue = $course['examVenue']['room_name'];
                        ?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$code?></td>
                            <td><?=$name?></td>
                            <td><?=$date?></td>
                            <td><?=$venue?></td>
                        </tr>
                        <?php
                            $count++;
                            endforeach;
                        endif;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
