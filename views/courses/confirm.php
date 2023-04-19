<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 4/15/2023
 * @time: 8:23 PM
 */

/**
 * @var string $title
 * @var yii\web\View $this
 * @var yii\data\ArrayDataProvider $timetableCoursesProvider
 * @var string $studentSemesterSessionId
 * @var string[] $currentSessionDetails
 */

use app\models\ClassGroup;
use app\models\CourseRegistration;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
        <h1>Confirm course registration</h1>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 course-confirmation">
                <div class="loader"></div>
                <div class="error-display alert text-center" role="alert"></div>
            </div>
            <div class="col-12">
                <?php
                $idCol = [
                    'attribute' => 'timetable_id',
                    'label' => 'id',
                    'vAlign' => 'middle',
                ];
                $codeCol = [
                    'label' => 'CODE',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['programmeCurriculumCourse']['course']['course_code'];
                    }
                ];
                $nameCol = [
                    'label' => 'NAME',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['programmeCurriculumCourse']['course']['course_name'];
                    }
                ];
                $examTypeCol = [
                    'label' => 'TYPE',
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'value' => function($model){
                        return 'FIRST ATTEMPT';
                    }
                ];
                $groupCol = [
                    'label' => 'GROUP',
                    'vAlign' => 'middle',
                    'value' => function($model) use($studentSemesterSessionId) {
                        $courseReg = CourseRegistration::find()->select(['class_code'])->where([
                            'student_semester_session_id' => $studentSemesterSessionId,
                            'timetable_id' => $model['timetable_id']
                        ])->asArray()->one();

                        if(empty($courseReg)){
                            return '';
                        }else{
                            $classGroup = ClassGroup::find()->select(['class_description'])
                                ->where(['class_code' => $courseReg['class_code']])->asArray()->one();
                            return strtoupper($classGroup['class_description']);
                        }
                    }
                ];

                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'class' => '\kartik\grid\CheckboxColumn',
                        'checkboxOptions' => function($model, $key, $index, $widget) {
                            return [
                                'value' => $model['timetable_id']
                            ];
                        }
                    ],
                    $codeCol,
                    $nameCol,
                    $examTypeCol,
                    $groupCol
                ];

                $toolbar = [
                    [
                        'content' =>
                            Html::button('Confirm registration', [
                                'title' => 'Confirm courses',
                                'id' => 'confirm-courses-btn',
                                'class' => 'btn btn-success btn-spacer btn-sm',
                            ]),
                        'options' => ['class' => 'btn-group mr-2']
                    ]
                ];

                try{
                    echo GridView::widget([
                        'id' => 'confirm-courses-grid',
                        'dataProvider' => $timetableCoursesProvider,
                        'columns' => $gridColumns,
                        'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'pjax' => true,
                        'responsiveWrap' => false,
                        'condensed' => true,
                        'hover' => true,
                        'striped' => false,
                        'bordered' => false,
                        'toolbar' => $toolbar,
                        'export' => false,
                        'panel' => [
                            'heading' => $currentSessionDetails['academicSession'] . ' | ' . $currentSessionDetails['programme'] . ' | Year ' .
                                $currentSessionDetails['level'] . ' | Semester ' . $currentSessionDetails['semester']
                        ],
                        'persistResize' => false,
                        'itemLabelSingle' => 'course',
                        'itemLabelPlural' => 'courses',
                    ]);
                }catch (Throwable $ex) {
                    $message = $ex->getMessage();
                    if(YII_ENV_DEV) {
                        $message = $ex->getMessage() . ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
                    }
                    throw new ServerErrorHttpException($message, 500);
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?php
$confirmCoursesUrl = Url::to(['/courses/confirm']);

$confirmCoursesJs = <<< JS
const confirmCoursesUrl = '$confirmCoursesUrl';

const confirmLoader = $('.course-confirmation > .loader');
confirmLoader.html(loader);
confirmLoader.hide();
        
const confirmErrorDisplay =  $('.course-confirmation > .error-display');
confirmErrorDisplay.hide();

/**
* Confirm courses registration
*/
$('#confirm-courses-grid-pjax').on('click', '#confirm-courses-btn', function (e){
    e.preventDefault();
    let timetableIds = getSelectedIds('#confirm-courses-grid');
    if(timetableIds.length === 0){
        alert('No courses have been selected.');
    }else{
        if(confirm('Confirm selected courses registration?')){
            confirmErrorDisplay.hide();
            confirmLoader.show(); 
            $.ajax({
                url: confirmCoursesUrl,
                type: 'POST',
                data: {'timetableIds' : timetableIds}
            }).done(function (data){
                confirmLoader.hide();
                if(!data.success){
                    confirmErrorDisplay.html(data.message);
                    confirmErrorDisplay.show();
                }
            }).fail(function (data){
                confirmLoader.hide();
                confirmErrorDisplay.html(data.responseText);
                confirmErrorDisplay.show();
            });
        }
    }
});
JS;
$this->registerJs($confirmCoursesJs, yii\web\View::POS_READY);



