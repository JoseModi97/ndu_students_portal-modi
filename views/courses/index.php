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
use app\models\CourseRegistrationStatus;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
        <h1>Course registration</h1>
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
                        $name = 'timetable-' . $model['timetable_id'] . '-exam-type';
                        return '
                            <select id="'. $name .'" class="exam-type" name="' . $name . '">
                                <option value=""></option>
                                <option value="FA">FIRST ATTEMPT</option>
                                <option value="RETAKE">RETAKE</option>
                                <option value="SPECIAL">SPECIAL</option>
                            </select>';
                    }
                ];
                $groupCol = [
                    'label' => 'GROUP',
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'value' => function($model){
                        $name = 'timetable-' . $model['timetable_id'] . '-class-group';
                        return '
                            <select id="'. $name .'" class="class-group" name="' . $name . '">
                                <option value=""></option>
                                <option value="1">GROUP 1</option>
                                <option value="2">GROUP 2</option>
                                <option value="3">GROUP 3</option>
                            </select>';
                    }
                ];
                $statusCol = [
                    'label' => 'STATUS',
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'value' => function($model) use($studentSemesterSessionId) {
                        $courseReg = CourseRegistration::find()->select(['course_reg_status_id'])->where([
                            'student_semester_session_id' => $studentSemesterSessionId,
                            'timetable_id' => $model['timetable_id']
                        ])->asArray()->one();

                        if (empty($courseReg)) {
                            return '<div class="status-pending">PENDING</div>';
                        } else {
                            $courseRegStatus = CourseRegistrationStatus::find()->select(['course_reg_status_name'])
                                ->where(['course_reg_status_id' => $courseReg['course_reg_status_id']])->asArray()->one();
                            $status = $courseRegStatus['course_reg_status_name'];
                            if ($status === 'CONFIRMED') {
                                return '<div class="status-approved">CONFIRMED</div>';
                            } elseif ($status === 'PROVISIONAL') {
                                return '<div class="status-review">PROVISIONAL</div>';
                            }
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
                    $groupCol,
                    $statusCol,
                ];

                $toolbar = [
                    [
                        'content' =>
                            Html::button('Register', [
                                'title' => 'Register for courses',
                                'id' => 'register-for-course-btn',
                                'class' => 'btn btn-success btn-spacer btn-sm',
                            ]) . '&nbsp' .
                            Html::a('Confirm registration',
                                Url::to(['/courses/provisional']),
                                [
                                    'title' => 'Confirm course registration',
                                    'class' => 'btn btn-success btn-spacer'
                                ]
                            ). '&nbsp' .
                            Html::button('Drop courses', [
                                'title' => 'Drop courses',
                                'id' => 'drop-courses-btn',
                                'class' => 'btn btn-success btn-spacer btn-sm',
                            ]),
                        'options' => ['class' => 'btn-group mr-2']
                    ],
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
                        'export' => false,
                        'panel' => [
                            'heading' => '2022/2023 | Bsc. Computer science | Year 1 | Semester 1' ,
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
$getSelectedExamTypesUrl = Url::to(['/courses/selected-exam-types']);
$confirmedCoursesUrl = Url::to(['/courses/confirmed']);
$dropCoursesUrl = Url::to(['/courses/drop']);

$registerForCoursesJs = <<< JS
const registerForCoursesUrl = '$registerForCoursesUrl';
const getSelectedExamTypesUrl = '$getSelectedExamTypesUrl';
const confirmedCoursesUrl = '$confirmedCoursesUrl';
const dropCoursesUrl = '$dropCoursesUrl';

const courseRegistrationLoader = $('.course-registration > .loader');
courseRegistrationLoader.html(loader);
courseRegistrationLoader.hide();
        
const courseRegistrationErrorDisplay =  $('.course-registration > .error-display');
courseRegistrationErrorDisplay.hide();

/**
* Provisional registration.
*/
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

/**
* Get the exam types for the courses registered for and set their select field values.
*/
function getSelectedExamType(){
    let timetableIds = [];
    $('table > tbody').find('tr .exam-type').each(function (e){
        let elementId = $(this).attr('id');
        let timetableId = elementId.split('-')[1];
        timetableIds.push(timetableId);
    });
    
    axios.get(getSelectedExamTypesUrl, {
        params: {
            timetableIds: timetableIds
        }
    })
    .then(response => {
        let examTypes = response.data.examTypes;
        Object.keys(examTypes).forEach(function(key) {
            let examTypeElementId = '#timetable-' + key + '-exam-type';
            $(examTypeElementId).val(examTypes[key]).change();
        });
    })
    .catch(error => {
        courseRegistrationLoader.hide();
        courseRegistrationErrorDisplay.html('Fetching selected exam types: ' + error.message) 
        courseRegistrationErrorDisplay.show();
    });
}
getSelectedExamType();

/**
* Get confirmed courses and disable their select checkbox, exam type and class group input fields.
* Confirmed courses are not editable from the portal.
*/
function getConfirmedCourses(){
    let timetableIds = [];
    $('table > tbody').find('tr .exam-type').each(function (e){
        let elementId = $(this).attr('id');
        let timetableId = elementId.split('-')[1];
        timetableIds.push(timetableId);
    });
    
    axios.get(confirmedCoursesUrl, {
        params: {
            timetableIds: timetableIds
        }
    })
    .then(response => {
        let confirmedTimetableIds = response.data.confirmedTimetableIds;
        for (const id of confirmedTimetableIds) {
            let examTypeElementId = '#timetable-' + id + '-exam-type';
            $(examTypeElementId).attr("disabled", true);
            $('.kv-row-checkbox[value="'+id+'"]').attr("disabled", true);
        }
    })
    .catch(error => {
        courseRegistrationLoader.hide();
        courseRegistrationErrorDisplay.html('Fetching confirmed courses: ' + error.message) 
        courseRegistrationErrorDisplay.show();
    });
}
getConfirmedCourses();

/**
* Drop courses registration
*/
$('#register-for-courses-grid-pjax').on('click', '#drop-courses-btn', function (e){
    e.preventDefault();
    let timetableIds = getSelectedIds('#register-for-courses-grid');
    if(timetableIds.length === 0){
        alert('No courses have been selected.');
    }else{
        if(confirm('Drop courses?')){
            courseRegistrationErrorDisplay.hide();
            courseRegistrationLoader.show(); 
            $.ajax({
                url: dropCoursesUrl,
                type: 'POST',
                data: {'timetableIds' : timetableIds}
            }).done(function (data){
                courseRegistrationLoader.hide();
                if(!data.success){
                    courseRegistrationErrorDisplay.html(data.message);
                    courseRegistrationErrorDisplay.show();
                }
            }).fail(function (data){
                courseRegistrationLoader.hide();
                courseRegistrationErrorDisplay.html(data.responseText);
                courseRegistrationErrorDisplay.show();
            });
        }
    }
});
JS;
$this->registerJs($registerForCoursesJs, yii\web\View::POS_READY);

