<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/28/2023
 * @time: 11:24 AM
 */

/**
 * @var string $title
 * @var yii\web\View $this
 * @var yii\data\ArrayDataProvider $timetableCoursesProvider
 * @var string $studentSemesterSessionId
 */

use app\models\CourseRegistration;
use app\models\CourseRegistrationType;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
        <h1>Registration  <i class="fa fa-angle-right" aria-hidden="true"></i>  Courses</h1>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 course-registration">
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
                    'label' => 'Course Code',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['programmeCurriculumCourse']['course']['course_code'];
                    }
                ];
                $nameCol = [
                    'label' => 'Course Name',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['programmeCurriculumCourse']['course']['course_name'];
                    }
                ];
                $selectExamTypeCol = [
                    'label' => 'Select Exam Type',
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'hAlign' => 'center',
                    'value' => function($model){
                        $name = 'timetable-' . $model['timetable_id'] . '-exam-type';
                        return '
                            <select class="exam-type" name="' . $name . '">
                                <option value=""></option>
                                <option value="FA">First Attempt</option>
                                <option value="RETAKE">Retake</option>
                                <option value="SUPP">Supplementary</option>
                            </select>';
                    }
                ];
                $statusCol = [
                    'label' => 'Status',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'format' => 'raw',
                    'value' => function($model) use($studentSemesterSessionId){
                        $courseReg = CourseRegistration::find()->select('student_course_reg_id')->where([
                            'student_semester_session_id' => $studentSemesterSessionId,
                            'timetable_id' => $model['timetable_id']
                        ])->asArray()->one();
                        if(empty($courseReg)){
                            return '<div class="text-center status-pending">pending</div>';
                        }
                        return '<div class="text-center status-approved">registered</div>';
                    }
                ];
                $examTypeCol = [
                    'label' => 'Exam Type',
                    'vAlign' => 'middle',
                    'value' => function($model) use($studentSemesterSessionId) {
                        $courseReg = CourseRegistration::find()->select('course_registration_type_id')->where([
                            'student_semester_session_id' => $studentSemesterSessionId,
                            'timetable_id' => $model['timetable_id']
                        ])->asArray()->one();

                        if(empty($courseReg)){
                            return '--';
                        }

                        $courseRegType = CourseRegistrationType::find()->select(['course_reg_type_name'])
                            ->where(['course_reg_type_id' => $courseReg['course_registration_type_id']])->asArray()->one();

                        return $courseRegType['course_reg_type_name'];
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
                    $selectExamTypeCol,
                    $statusCol,
                    $examTypeCol
                ];

                $toolbar = [
                    [
                        'content' => Html::button('<i class="fas fa-check"></i> Register', [
                            'title' => 'Register for courses',
                            'id' => 'register-for-course-btn',
                            'class' => 'btn btn-success btn-spacer btn-sm',
                        ]),
                        'options' => ['class' => 'btn-group mr-2']
                    ],
                    '{export}',
                    '{toggleData}',
                ];

                try{
                    echo GridView::widget([
                        'id' => 'register-for-courses-grid',
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
                        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                        'toggleDataOptions' => ['minCount' => 50],
                        'export' => [
                            'fontAwesome' => true,
                            'label' => 'Export requests'
                        ],
                        'panel' => [
                            'heading' => 'Register for courses',
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
$registerForCoursesUrl = Url::to(['/courses/register']);

$registerForCoursesJs = <<< JS
const registerForCoursesUrl = '$registerForCoursesUrl';

const courseRegistrationLoader = $('.course-registration > .loader');
courseRegistrationLoader.html(loader);
courseRegistrationLoader.hide();
        
const courseRegistrationErrorDisplay =  $('.course-registration > .error-display');
courseRegistrationErrorDisplay.hide();

$('#register-for-courses-grid-pjax').on('click', '#register-for-course-btn', function (e){
    e.preventDefault();
    if(getSelectedIds('#register-for-courses-grid').length === 0){
        alert('No courses have been selected.');
    }else{
        let courses = [];
        let missingExamType = false;
        $('table > tbody').find('tr.table-danger').each(function (e){
            let examTypeInput = $(this).find('.exam-type');
            let name = examTypeInput.attr('name');
            let timetableId = name.split('-')[1];
            if(examTypeInput.val() === ''){
                missingExamType = true;
                return;
            }else{
                let course = {  
                    'timetableId' : timetableId,
                    'examType' : examTypeInput.val()
                };
                courses.push(course);
            }
        });
        
         if(missingExamType){
             alert('Select the exam type for all courses you want to register for.');
         }else{
            if(confirm('Register for selected courses?')){
                courseRegistrationErrorDisplay.hide();
                courseRegistrationLoader.show();
                $.ajax({
                    url: registerForCoursesUrl,
                    type: 'POST',
                    data: {'courses' : courses}
                }).done(function (data){
                    courseRegistrationLoader.hide();
                     if(!data.success){
                        courseRegistrationErrorDisplay.html(data.message) 
                        courseRegistrationErrorDisplay.show();
                     }
                }).fail(function (data){
                     courseRegistrationLoader.hide();
                     courseRegistrationErrorDisplay.html(data.responseText) 
                     courseRegistrationErrorDisplay.show();
                });
            }
         }
     }
});
JS;
$this->registerJs($registerForCoursesJs, yii\web\View::POS_READY);

