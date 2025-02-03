<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/29/2023
 * @time: 11:13 PM
 */

/**
 * @var string $title
 * @var string $studProgCurriculumId
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\ResultsSearch $searchModel
 */

use app\models\CourseRegistration;
use app\models\ProgrammeCurriculumTimetable;
use app\models\StudentProgCurriculum;
use kartik\grid\GridView;
use yii\db\ActiveQuery;
use yii\web\ServerErrorHttpException;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
        <h1>Results</h1>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="bg-warning text-center" style="margin-bottom: 20px; padding: 20px 0;border-radius: .25rem">
                            While every effort is made to ensure that details are correct and up-to-date,
                            please reconfirm with your Faculty/School/Institute office in case of any missing unit.
                            Please note that NOT ALL Faculties, Schools and Institutes have released their results online.
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?php
                $semesterDesc = [
                    'label' => 'CODE',
                    'vAlign' => 'middle',
                    'group' => true,  // enable grouping,
                    'groupedRow' => true,                    // move grouped column to a single grouped row
                    'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
                    'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css clas
                    'value' => function($model){
                        return $model['programmeCurriculumTimetable']['programmeCurriculumSemesterGroup']['progCurrSemester']
                        ['academicSessionSemester']['acad_session_semester_desc'];
                    }
                ];
                $codeCol = [
                    'label' => 'CODE',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['programmeCurriculumTimetable']['programmeCurriculumCourse']['course']['course_code'];
                    }
                ];
                $nameCol = [
                    'label' => 'NAME',
                    'vAlign' => 'middle',
                    'value' => function($model){
                        return $model['programmeCurriculumTimetable']['programmeCurriculumCourse']['course']['course_name'];
                    }
                ];
                $examTypeCol = [
                    'label' => 'EXAM TYPE',
                    'vAlign' => 'middle',
                    'value' => function($model) use ($studProgCurriculumId){
                        $timetable = ProgrammeCurriculumTimetable::find()
                            ->select(['timetable_id'])
                            ->where(['mrksheet_id' => $model['mrksheet_id']])
                            ->asArray()->one();

                        $timetableId = $timetable['timetable_id'];

                        $cr = CourseRegistration::find()->alias('cr')
                            ->select([
                                'cr.timetable_id',
                                'cr.course_registration_type_id',
                                'cr.student_semester_session_id'
                            ])
                            ->where(['cr.timetable_id' => $timetableId])
                            ->joinWith(['courseRegistrationType ct' => function(ActiveQuery $q){
                                $q->select([
                                    'ct.course_reg_type_id',
                                    'ct.course_reg_type_code'
                                ]);
                            }],true, 'INNER JOIN')
                            ->joinWith(['semesterSessionProgress ssp' => function(ActiveQuery $q){
                                $q->select([
                                    'ssp.student_semester_session_id',
                                    'ssp.academic_progress_id'
                                ]);
                            }], true, 'INNER JOIN')
                            ->joinWith(['semesterSessionProgress.academicProgress ap' => function(ActiveQuery $q){
                                $q->select([
                                    'ap.academic_progress_id',
                                ]);
                            }], true, 'INNER JOIN')
                            ->andWhere(['ap.student_prog_curriculum_id' => $studProgCurriculumId])
                            ->asArray()->one();
                        if(empty($cr)){
                            return '--';
                        }
                        return $cr['courseRegistrationType']['course_reg_type_code'];
                    }
                ];
                $finalCol = [
                    'attribute' => 'final_mark',
                    'label' => 'FINAL MARK',
                    'vAlign' => 'middle'
                ];
                $gradeCol = [
                    'attribute' => 'grade',
                    'label' => 'GRADE',
                    'vAlign' => 'middle'
                ];

                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    $semesterDesc,
                    $codeCol,
                    $nameCol,
                    $examTypeCol,
                    $finalCol,
                    $gradeCol
                ];

                try{
                    echo GridView::widget([
                        'id' => 'register-for-courses-grid',
                        'dataProvider' => $dataProvider,
                        'columns' => $gridColumns,
                        'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'pjax' => true,
                        'responsiveWrap' => false,
                        'condensed' => true,
                        'hover' => true,
                        'striped' => false,
                        'bordered' => false,
                        'toolbar' => [],
                        'export' => false,
                        'panel' => [
                            'heading' => 'Results'
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