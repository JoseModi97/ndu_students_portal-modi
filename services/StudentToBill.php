<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/21/2024
 * @time: 12:23 PM
 */

namespace app\services;

use app\enums\BillingType;
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
        $this->progressId = $progress['academic_progress_id'];
        $this->academicSessionId = $progress['acad_session_id'];
        $this->semSessionId = $progress['student_semester_session_id'];
        $this->academicYear = $progress['acad_session_name'];
        $this->level = $progress['academic_level'];
        $this->semester = $progress['semester_code'];
        $this->progressNumber = $progress['sem_progress_number'];

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
            ->from('smisportal.org_programmes prog')
            ->innerJoin('smisportal.org_programme_curriculum pc', 'pc.prog_id=prog.prog_id')
            ->where(['prog.prog_code' => $this->progCode, 'pc.status' => 'ACTIVE'])
            ->orderBy(['pc.prog_curriculum_id' => SORT_DESC])
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
            ->from('smisportal.org_programmes prog')
            ->innerJoin('smisportal.org_programme_curriculum pc', 'pc.prog_id=prog.prog_id')
            ->innerJoin('smisportal.fss_billing_type bt', 'bt.billing_type_id=pc.billing_type_id')
            ->where(['prog.prog_code' => $this->progCode, 'pc.status' => 'ACTIVE'])
            ->orderBy(['pc.prog_curriculum_id' => SORT_DESC])
            ->one();

        if (!$prog) {
            throw new NotFoundHttpException('This program\'s billing type is not found');
        }

        if ($prog['billing_type_desc'] === BillingType::NON_INTEGRATED->value) {
            return true;
        } else if ($prog['billing_type_desc'] === BillingType::REGULAR_INTEGRATED->value) {
            return false;
        }

        throw new ServerErrorHttpException('This program\'s billing type is not recognized');
    }

    /**
     * @return bool|array
     */
    private function studentProgress(): bool|array
    {
        return (new Query())->select([
            'ap.academic_progress_id',
            'yr.acad_session_id',
            'yr.acad_session_name',
            'lvl.academic_level',
            'lvl.academic_level_name',
            'ssp.student_semester_session_id',
            'ssp.sem_progress_number',
            'sc.semester_code'
        ])
            ->from('smisportal.sm_academic_progress ap')
            ->innerJoin('smisportal.sm_student_programme_curriculum spc', 'spc.student_prog_curriculum_id=ap.student_prog_curriculum_id')
            ->innerJoin('smisportal.sm_student_sem_session_progress ssp', 'ssp.academic_progress_id=ap.academic_progress_id')
            ->innerJoin('smisportal.org_academic_session yr', 'yr.acad_session_id=ap.acad_session_id')
            ->innerJoin('smisportal.org_academic_levels lvl', 'lvl.academic_level_id=ap.academic_level_id')
            ->innerJoin('smisportal.org_prog_curr_semester pcs', 'pcs.prog_curriculum_semester_id=ssp.prog_curriculum_semester_id')
            ->innerJoin('smisportal.org_academic_session_semester ass', 'ass.acad_session_semester_id=pcs.acad_session_semester_id')
            ->innerJoin('smisportal.org_semester_code sc', 'sc.semester_code=ass.semester_code')
            ->where(['spc.registration_number' => $this->regNumber])
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