<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/21/2024
 * @time: 12:23 PM
 */

namespace app\services;

use app\enums\BillingType;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

final class StudentToBill
{
    private ?int $annualSemesters;
    private ?int $progressNumber;

    public ?string $progCode;
    public ?string $progCurrId;
    public ?int $progressId;
    public ?int $academicSessionId;
    public ?int $semSessionId;
    public ?string $academicYear;
    public ?int $level;

    public ?int $semester;
    public ?bool $isInAFirstSemester;
    public ?bool $isInATeachingSemester;
    public ?bool $isBilledAnnually;
    public ?bool $allowRegistration;

    /**
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function __construct(public readonly string $regNumber)
    {
        $this->progCode = explode('/', $this->regNumber)[0];

        $progDetails = $this->programDetails();
        $this->annualSemesters = $progDetails['annual_semesters'];
        $this->progCurrId = $progDetails['prog_curriculum_id'];

        $progress = $this->studentProgress();
//        print_r($progress['student_semester_session_id']); exit;
        if (!$progress) {
            throw new NotFoundHttpException('The student has no active academic progress for the assigned curriculum.');
        }

        $this->progressId = $progress['academic_progress_id'];
        $this->academicSessionId = $progress['acad_session_id'];
        $this->semSessionId = $progress['student_semester_session_id'];
        $this->academicYear = $progress['acad_session_name'];
        $this->level = $progress['academic_level'];
        $this->semester = $progress['semester_code'];
        $this->progressNumber = $progress['sem_progress_number'];
        $this->allowRegistration = $progress['allow_registration'];

        // @todo simulate promotion to 1.2
//        $this->semester = 2;
//        $this->progressNumber = 2;

        $this->isInAFirstSemester = $this->isInAFirstSemester();
        $this->isInATeachingSemester = $this->isInATeachingSemester();
        $this->isBilledAnnually = $this->isBilledAnnually();
    }

    /**
     * @return bool|array
     */
    private function programDetails(): bool|array
    {
        return (new Query())->select(['pc.annual_semesters', 'pc.prog_curriculum_id'])
            ->from('smisportal.sm_student_programme_curriculum spc')
            ->innerJoin(
                'smisportal.org_programme_curriculum pc',
                'pc.prog_curriculum_id=spc.prog_curriculum_id'
            )
            ->where([
                'spc.registration_number' => $this->regNumber,
                'pc.status' => 'ACTIVE'
            ])
            ->one();
    }

    /**
     * @return True is program is of Non-Integrated billing type. Admin fees billed per year
     * @return False if program is of Regular-Integrated billing type. Admin fees billed per teaching semester
     * @throws ServerErrorHttpException
     * @throws NotFoundHttpException
     */
    private function isBilledAnnually(): bool
    {
        $prog = (new Query())
            ->select(['pc.prog_curriculum_id', 'bt.billing_type_desc'])
            ->from('smis.org_programme_curriculum pc')
            ->innerJoin('smis.fss_billing_type bt', 'bt.billing_type_id=pc.billing_type_id')
            ->where(['pc.prog_curriculum_id' => $this->progCurrId])
            ->one(Yii::$app->smisDb);

        if (!$prog) {
            throw new NotFoundHttpException('This program\'s billing type is not found');
        }

        $billingType = strtolower(trim($prog['billing_type_desc'])); //print_r($billingType); exit;

        if ($billingType === strtolower(BillingType::INTEGRATED->value)) {
//            print_r('true'); exit;
            return true;
        }

        if (str_contains($billingType, 'integrated')) {
//            print_r('false'); exit;
            return true;
        }

        throw new ServerErrorHttpException('This program\'s billing type is not recognized');
    }

    /**
     * @throws NotFoundHttpException
     */
    public function tuitionFeeBilledPerSemester(): bool
    {
        $prog = (new Query())
            ->select(['pc.prog_curriculum_id', 'bt.billing_type_desc'])
            ->from('smis.org_programme_curriculum pc')
            ->innerJoin('smis.fss_billing_type bt', 'bt.billing_type_id=pc.billing_type_id')
            ->where(['pc.prog_curriculum_id' => $this->progCurrId])
            ->one(Yii::$app->smisDb);

        if (!$prog) {
            throw new NotFoundHttpException('This program\'s billing type is not found');
        }

        $billingType = strtolower(trim($prog['billing_type_desc']));

        if ($billingType === strtolower(BillingType::INTEGRATED->value)) {
            return true;
        }

        return  false;
    }

    /**
     * @return bool|array
     */
    private function studentProgress(): bool|array
    {
//        print_r([
//            $this->regNumber,
//            'spc.prog_curriculum_id' => $this->progCurrId,
//            'pcs.prog_curriculum_id' => $this->progCurrId,
//        ]); exit;

        return (new Query())->select([
            'ap.academic_progress_id',
            'yr.acad_session_id',
            'yr.acad_session_name',
            'lvl.academic_level',
            'lvl.academic_level_name',
            'ssp.student_semester_session_id',
            'ssp.sem_progress_number',
            'ssp.allow_registration',
            'sc.semester_code'
        ])
            ->from('smisportal.sm_academic_progress ap')
            ->innerJoin('smisportal.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id=ap.student_prog_curriculum_id')
            ->innerJoin('smisportal.sm_student_sem_session_progress ssp', 'ssp.academic_progress_id=ap.academic_progress_id')
            ->innerJoin('smisportal.org_academic_session yr', 'yr.acad_session_id=ap.acad_session_id')
            ->innerJoin('smisportal.org_academic_levels lvl', 'lvl.academic_level_id=ap.academic_level_id')
            ->innerJoin(
                'smisportal.org_prog_curr_semester_group pcsg',
                'pcsg.prog_curriculum_sem_group_id=ssp.prog_curriculum_semester_id'
            )
            ->innerJoin(
                'smisportal.org_prog_curr_semester pcs',
                'pcs.prog_curriculum_semester_id=pcsg.prog_curriculum_semester_id'
            )
            ->innerJoin('smisportal.org_academic_session_semester ass', 'ass.acad_session_semester_id=pcs.acad_session_semester_id')
            ->innerJoin('smisportal.org_semester_code sc', 'sc.semester_code=ass.semester_code')
            ->where([
                'spc.registration_number' => $this->regNumber,
                'spc.prog_curriculum_id' => $this->progCurrId,
                'pcs.prog_curriculum_id' => $this->progCurrId,
                'ap.current_status' => 1
            ])
            ->orderBy(['ssp.student_semester_session_id' => SORT_DESC])
            ->one();
    }

    /**
     * @return True if a student is in first semester. False otherwise.
     */
    private function isInAFirstSemester(): bool
    {
        if ($this->progressNumber === 1) {
            return true;
        } else {
            if ($this->progressNumber % $this->annualSemesters === 1) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    private function isInATeachingSemester(): bool
    {
        if ($this->semester > $this->annualSemesters) {
            return false;
        } else {
            return true;
        }
    }
}
