<?php

namespace app\services;

use app\enums\AdminFee;
use app\enums\ChargeFrequency;
use app\enums\CourseFee;
use app\enums\FeePriority;
use app\enums\FeeStatus;
use app\enums\FeeType;
use app\enums\InvoiceStatus;
use app\enums\InvoiceType;
use app\enums\ReceiptStatus;
use app\helpers\SmisHelper;
use app\models\FeeItem;
use app\models\FeeTransaction;
use app\models\Invoice;
use app\models\InvoiceDetail;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/21/2024
 * @time: 10:27 AM
 */
final class BillStudent
{
    private bool $followUpRegistration = false;

    public function __construct(private readonly StudentToBill $student)
    {
    }

    /**
     * Get the admin and course (unit/tuition) fees payable
     * We disregard registration fees. This is charged outside this routine.
     * @param array $courses
     * @return array
     * @throws NotFoundHttpException
     */
    public function payableFees(array $courses): array
    {
        $this->followUpRegistration = false; //print_r(111); exit;

        /**
         * A student is billed the semester registration fees before joining into a session.
         * You must be in a session to do any course registration.
         *
         * During the initial course registration a student in billed the whole admin fees plus course unit/tuition fees.
         * For programs billed per year, admin fees are only charged during the first semester. In follow-up course registration
         * we only bill the course unit fees.
         *
         * For programs billed per semester, admin fees are charged each semester.
         * These programs also have a tuition charge that is billed together with the admin charges as a block.
         * Therefore, we have no course units charges hence for follow-ups, the student is billed nothing
         */

        $invoiceProgressCode = '%';
        $invoiceProgressCode .= $this->student->regNumber . '-' . $this->student->academicYear;
//        if (!$this->student->isBilledAnnually) {
//            $invoiceProgressCode .= '-SEM' . $this->student->semester;
//        }
        if ($this->student->tuitionFeeBilledPerSemester()) {
            $invoiceProgressCode .= '-SEM' . $this->student->semester;
        }
        $invoiceProgressCode .= '%'; //dd($invoiceId);
//        dd($invoiceId);

//        print_r($invoiceProgressCode); exit;

        $invoiceDetails = InvoiceDetail::find()->where(['LIKE', 'invoice_progress_code', $invoiceProgressCode, false])->asArray()->all();
//        dd($invoiceDetails);
        if (!empty($invoiceDetails)) {
            foreach ($invoiceDetails as $key => $detail) {
                if ($detail['invoice_detail_desc'] === AdminFee::REGISTRATION_FEES->value) {
                    unset($invoiceDetails[$key]);
                }
            }
        }

        if (!empty($invoiceDetails)) {
            $this->followUpRegistration = true;
        }
//        dd($invoiceDetails);
//        dd($followUpRegistration);

        $adminFees = $this->payableAdminFees(); //print_r($adminFees); exit;
        $adminFeesTotal = $adminFees['total'];

//        dd($this->student->isInATeachingSemester);
        // Admin fees are not applicable at follow-up registrations
        // Admin fees are not applicable in a supplementary semester
        if ($this->followUpRegistration || !$this->student->isInATeachingSemester) {
            $adminFeesTotal = 0;
            $adminFees = [];
        }

//        dd($adminFees);  

// print_r('nnnnnn'); exit;

        // Only calculate for courses not yet invoiced
        // @todo we hide this for now
//        $courseFees = $this->payableCourseFees($courses, $this->followUpRegistration);



//        print_r($courseFees); exit;

        // For programs billed per semester we have a tuition fee
        // This fee is billed together with other admin fees needed during the initial course registration
        // Therefore, for follow-ups, the student is billed zero
//        if ($followUpRegistration && !$this->student->isBilledAnnually) {
//            // This array will contain the tuition fee amount. Since it's a follow-up, this had been already paid for.
//            // So we remove it.
//            $courseFees['total'] = $courseFees['total'] - $courseFees['items']['tuition']['amount'];
//            unset($courseFees['items']['tuition']);
//        }
        //dd($courseFees);

        return [
            'adminFees' => $adminFees,
//            'courseFees' => $courseFees,
//            'total' => $adminFeesTotal + $courseFees['total'],
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function billCourseRegistration(array $courses): array
    {
        $fees = $this->fees(FeeType::COURSE->value);

        $courseCharges = [];
        $totalCourseCharges = 0;

        foreach ($courses as $course) {
            $courseFee = CourseFee::tryFrom($course['type']);

            if ($courseFee === null) {
                throw new NotFoundHttpException('This program\'s ' . $course['type'] . ' fee is not set');
            }

            $description = strtolower(str_replace(' ', '', $courseFee->feeDescription()));

            $matchedFee = null;
            foreach ($fees as $fee) {
                if ($fee['frequency'] !== ChargeFrequency::UNIT->value) {
                    continue;
                }

                if (strtolower(str_replace(' ', '', $fee['description'])) === $description) {
                    $matchedFee = $fee;
                    break;
                }
            }

            $courseCharges[] = [
                'chargeTypeId' => $matchedFee['charge_type_id'] ?? null,
                'description' => $matchedFee['description'] ?? $courseFee->feeDescription(),
                'amount' => $matchedFee['amount_charged'] ?? 0,
                'type' => $course['type'],
                'code' => $course['code'],
            ];

            $totalCourseCharges += $matchedFee['amount_charged'] ?? 0;
        }

        return [
            'adminFees' => ['items' => [], 'total' => 0],
            'courseFees' => ['items' => $courseCharges, 'total' => $totalCourseCharges],
            'total' => $totalCourseCharges,
        ];
    }

    /**
     * @throws UnprocessableEntityHttpException
     * @throws ServerErrorHttpException
     * @throws Exception
     */
    public function bill(array $payableFees): void
    {
//                    print_r($payableFees); exit;
        $totalToPay = (int)$payableFees['total'];

        if ($this->isBalanceSufficient($totalToPay)) {
            $feeTransaction = $this->storeTransaction($totalToPay);
            $invoice = $this->storeInvoice($feeTransaction);
            $this->storeInvoiceDetails($invoice, $payableFees);
        } else {
            throw new UnprocessableEntityHttpException('Your balance is insufficient. Please top up to at least 50% of the total amount payable to proceed.');        }
    }

    /**
     * @throws UnprocessableEntityHttpException
     * @throws ServerErrorHttpException
     * @throws Exception
     */
    public function billZeroCourseFees(array $payableFees): void
    {
        $totalToPay = (int)$payableFees['total'];

        $feeTransaction = $this->storeTransaction($totalToPay);
        $invoice = $this->storeInvoice($feeTransaction);
        $this->storeInvoiceDetails($invoice, $payableFees);
    }


    /**
     * @param array $payableFees
     * @return array
     */
    public function detailedFeeItemsToBill(array $payableFees): array
    {
        $adminFeesItems = $payableFees['adminFees']['items'] ?? [];
        $courseFeesItems = $payableFees['courseFees']['items'] ?? [];

        return array_merge($adminFeesItems, $courseFeesItems);
    }

//    public function detailedFeeItemsToBill(array $payableFees): array
//    {
//        /**
//         * Billing is done in two or three steps:
//         * First, we bill the admin (semester registration) fees
//         * Second, we bill admin + course (units/tuition) fees during course registration
//         * Third, we may bill follow-up course registration
//         */
//        $adminFeesItems = [];
//        $courseFeesItems = [];
//        if (array_key_exists('adminFees', $payableFees) && !empty($payableFees['adminFees'])) {
//            $adminFeesItems = array_merge($payableFees['adminFees']['items']);
//        }
//
//        if (array_key_exists('courseFees', $payableFees) && !empty($payableFees['courseFees'])) {
//            $courseFeesItems = array_merge($payableFees['courseFees']['items']);
//        }
//
////        print_r([$courseFeesItems]); exit;
//
//        return array_merge($adminFeesItems, $courseFeesItems);
//    }

    /**
     * Check if a student has enough balances to be deducted the amount payable
     * @param int $amountPayable
     * @return bool
     */
    public function isBalanceSufficient(int $amountPayable): bool
    {
        // Some students may be allowed to register with no full payments yet
        if($this->student->allowRegistration){
            return true;
        }

        $totals = $this->totalTransactions();

        if (empty($totals)) {
            return false;
        }

        $balance = $totals['credits'] - $totals['debits'];
        $minimumRequired = $amountPayable * 0.5;

        return $balance >= $minimumRequired;
    }

    /**
     * @return array|bool
     */
    public function totalTransactions(): array|bool
    {
        $transactions = (new Query())
            ->select(['trans_amount', 'trans_type'])
            ->from('smisportal.fss_fee_transactions')
            ->where(['LIKE', 'progress_code', $this->student->regNumber . '%', false])
            ->all();//dd($transactions);

//        print_r([9888, $transactions]); exit;


        $credits = 0;
        $debits = 0;

        if (!empty($transactions)) {

            foreach ($transactions as $transaction) {
                if ($transaction['trans_type'] === InvoiceType::CR->value) {
                    $credits += (int)$transaction['trans_amount'];
                }

                if ($transaction['trans_type'] === InvoiceType::DR->value) {
                    $debits += (int)$transaction['trans_amount'];
                }
            }
        }

//        print_r([
//            'credits' => $credits,
//            'debits' => $debits
//        ]); exit;

        return [
            'credits' => $credits,
            'debits' => $debits
        ];
    }

    /**
     * @throws Exception
     */
    private function storeInvoice(FeeTransaction $feeTransaction): Invoice
    {
        $invoice = new Invoice();
        $invoice->invoice_id = $this->student->regNumber . '-' . $this->student->academicYear . '-SEM' . $this->student->semester;
        $invoice->invoice_desc = 'FEES PAYABLE FOR SEM ' . $this->student->semester;
        $invoice->invoice_date = $feeTransaction->trans_date;
        $invoice->last_update = $invoice->invoice_date;
        $invoice->user_id = $this->student->regNumber;
        $invoice->invoice_status = InvoiceStatus::FIRST->value;
        $invoice->amount = $feeTransaction->trans_amount;
        $invoice->exchange_rate = 1;
        $invoice->sync_status = false;
        $invoice->reg_number = $this->student->regNumber;
        $invoice->trans_id = $feeTransaction->trans_id;

        if (!$invoice->save()) {
            if (!$invoice->validate()) {
                throw new UnprocessableEntityHttpException(SmisHelper::getModelErrors($invoice->getErrors()));
            } else {
                throw new ServerErrorHttpException('An error occurred while creating invoice');
            }
        }

        return $invoice;
    }

    /**
     * @param int $amount
     * @return FeeTransaction
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws Exception
     */
    private function storeTransaction(int $amount): FeeTransaction
    {
        $date = SmisHelper::formatDate('now', 'Y-m-d');
        $transaction = new FeeTransaction();
        $transaction->academic_progress_id = $this->student->progressId;
        $transaction->trans_date = $date;
        $transaction->trans_type = InvoiceType::DR->value;
        $transaction->trans_amount = $amount;
        $transaction->trans_desc = 'FEES PAYABLE FOR SEM ' . $this->student->semester;
        $transaction->user_id = $this->student->regNumber;
        $transaction->receipt_status = ReceiptStatus::INVOICED->value; // @todo value to set to be clarified
        $transaction->exchange_rate = 1;
        $transaction->progress_code = $this->student->regNumber . '-' . $this->student->academicYear;
        $transaction->sync_status = false;
        $transaction->student_semester_session_id = $this->student->semSessionId;

        if (!$transaction->save()) {
            if (!$transaction->validate()) {
                throw new UnprocessableEntityHttpException(SmisHelper::getModelErrors($transaction->getErrors()));
            } else {
                throw new ServerErrorHttpException('An error occurred while creating transaction details');
            }
        }

        return $transaction;
    }

    /**
     * @param Invoice $invoice
     * @param array $payableFees
     * @return void
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    private function storeInvoiceDetails(Invoice $invoice, array $payableFees): void
    {
        // dd($invoice);
        $feeItems = $this->detailedFeeItemsToBill($payableFees);

        foreach ($feeItems as $feeItem) {
            $detail = new InvoiceDetail();
            $detail->invoice_id = $invoice->id;
            $detail->invoice_progress_code = $invoice->invoice_id;
            $detail->trans_date = $invoice->invoice_date;;
            $detail->amount = $feeItem['amount'];
            $detail->user_id = $invoice->user_id;
            if(!empty($feeItem['chargeTypeId'])){
                $detail->charge_type_id = (int)$feeItem['chargeTypeId'];
            }
            $detail->sync_status = false;

            $description = $feeItem['description']; // reg type e.g. FA, PROJECT
            if (!empty($feeItem['code'])) {
                $description = $feeItem['code'] . ' - ' . $description;
            }
            $detail->invoice_detail_desc = $description;

            if (!$detail->save()) {
                if (!$detail->validate()) {
                    throw new UnprocessableEntityHttpException(SmisHelper::getModelErrors($detail->getErrors()));
                } else {
                    throw new ServerErrorHttpException('An error occurred while creating invoice details');
                }
            }
        }
    }

    /**
     * @throws NotFoundHttpException
     */
    public function payableReportingFees(): array
    {
        $regFees = $this->payableRegFees();
        $adminFees = $this->payableAdminFees();
        $courseFees = $this->payableCourseFees($this->followUpRegistration);

        // Registration fees are just another admin fee category, so fold them in here
        $adminFees['items'] = array_merge($regFees['adminFees']['items'], $adminFees['items']);
        $adminFees['total'] += $regFees['total'];

        return [
            'adminFees' => $adminFees,
            'courseFees' => $courseFees,
            'total' => $adminFees['total'] + $courseFees['total'],
        ];
    }

    /**
     * @return array
     */
    #[ArrayShape(['items' => "array", 'total' => "int"])]
    private function payableAdminFees(): array
    {
        $adminFees = $this->fees(FeeType::ADMIN->value);

        // @todo for now semester reporting is free
        foreach ($adminFees as $key => $adminFee) {
            if ($adminFee['fee_description'] === AdminFee::REGISTRATION_FEES->value) {
                unset($adminFees[$key]);
            }
        }

        //dd($adminFees);

//        print_r($adminFees); exit;

        $total = 0;
        $adminCharges = [];

        // Some fees e.g. caution money are charged only once in a student's life. We bill these at 1st year semester 1
        // Note that some fees are charged once but not needed to be billed in the course of a student's progression journey.
        // Fees like gown and cap during graduation. We take note of these types and assign them a priority of 2.
        // We assume that these will be charged outside this work flow.
        foreach ($adminFees as $key => $adminFee) {
            if ($adminFee['frequency'] === ChargeFrequency::ONCE->value &&
                $this->student->level === 1 &&
                $this->student->isInAFirstSemester) {

                $total += $adminFee['amount_charged'];
                $adminCharges[] = [
                    'chargeTypeId' => $adminFee['charge_type_id'],
                    'description' => $adminFee['fee_description'],
                    'amount' => $adminFee['amount_charged']
                ];

                unset($adminFees[$key]);
            }
        }

        foreach ($adminFees as $key => $adminFee) {

            if ($this->student->isInAFirstSemester) {
                if ($adminFee['frequency'] === ChargeFrequency::ANNUAL->value) {
                    $adminCharges[] = [
                        'chargeTypeId' => $adminFee['charge_type_id'],
                        'description' => $adminFee['fee_description'],
                        'amount' => $adminFee['amount_charged']
                    ];

                    $total += $adminFee['amount_charged'];
                }
            }

            if($adminFee['frequency'] == ChargeFrequency::SEMESTER->value){

                if($adminFee['semester'] == $this->student->semester && $adminFee['level_of_study'] == $this->student->level){
                    $adminCharges[] = [
                        'chargeTypeId' => $adminFee['charge_type_id'],
                        'description' => $adminFee['fee_description'],
                        'amount' => $adminFee['amount_charged'],
                        'level' => $adminFee['level_of_study'],
                        'semester' => $adminFee['semester']
                    ];

                    $total += $adminFee['amount_charged'];
                }

            }
        }

//        print_r($adminCharges); exit;

        return [
            'items' => $adminCharges,
            'total' => $total
        ];
    }


    /**
     * @throws NotFoundHttpException
     */
    public function payableRegFees(): array
    {
        $adminFees = $this->fees(FeeType::ADMIN->value);

//        print_r($adminFees); exit;

//        print_r([$this->student->semester, $this->student->level]); exit;

        $payableFees = [];
        foreach ($adminFees as $key => $adminFee) {
//            print_r([$this->student->semester, $this->student->level]); exit;
            if ($adminFee['fee_description'] === AdminFee::REGISTRATION_FEES->value) {
//                print_r([$this->student->semester, $this->student->level]); exit;
                if($adminFee['semester'] == $this->student->semester && $adminFee['level_of_study'] == $this->student->level) {
                    $payableFees = [
                        'adminFees' => [
                            'items' => [
                                [
                                    'chargeTypeId' => $adminFee['charge_type_id'],
                                    'description' => AdminFee::REGISTRATION_FEES->value,
                                    'amount' => $adminFee['amount_charged']
                                ]
                            ],
                            'total' => $adminFee['amount_charged'] // Total amount charged for the admin fees items
                        ],
                        'total' => $adminFee['amount_charged'] // Grand total
                    ];
                }
            }
        }

        if(empty($payableFees)) {
            throw new NotFoundHttpException('This program\'s Semester Registration fee is not set');
        }

        return $payableFees;
    }

    /**
     * @param bool $followUpRegistration
     * @return array
     * @throws NotFoundHttpException
     */
    #[ArrayShape(['items' => "array", 'total' => "int|mixed"])]
    private function payableCourseFees(bool $followUpRegistration): array
    {
        $fees = $this->fees(FeeType::COURSE->value);

        $courseCharges = [];
        $totalCourseCharges = 0;

        /**
         * Tuition fee is charged under the following terms:
         * program must be billed per semester
         * must be the initial registration
         * student must be in a teaching semester
         */
//        $followUpRegistration = false;
        if ($this->student->tuitionFeeBilledPerSemester() && !$followUpRegistration && $this->student->isInATeachingSemester) {
            $tuitionDescription = strtolower(str_replace(' ', '', CourseFee::tryFrom('TUITION')->feeDescription()));
            $tuitionFee = null;
            foreach ($fees as $key => $fee) { //print_r($fees); exit;
                if($fee['semester'] == $this->student->semester && $fee['level_of_study'] == $this->student->level){
                    if (strtolower(str_replace(' ', '', $fee['fee_description'])) === $tuitionDescription) {
                        $tuitionFee = $fee;
                        unset($fees[$key]);
                        break;
                    }
                }
            }

            if ($tuitionFee === null) {
                throw new NotFoundHttpException('This program\'s TUITION fee is not set');
            }

            $courseCharges['tuition'] = [
                'chargeTypeId' => $tuitionFee['charge_type_id'],
                'description' => $tuitionFee['fee_description'],
                'amount' => $tuitionFee['amount_charged'],
                'type' => 'TUITION',
            ];

            $totalCourseCharges += $tuitionFee['amount_charged'];


        }
//        print_r($courseCharges); exit;

        foreach ($fees as $fee) {
            if($fee['frequency'] == ChargeFrequency::SEMESTER->value){

                if($fee['semester'] == $this->student->semester && $fee['level_of_study'] == $this->student->level){
                    $courseCharges[] = [
                        'chargeTypeId' => $fee['charge_type_id'],
                        'description' => $fee['fee_description'],
                        'amount' => $fee['amount_charged'],
                        'level' => $fee['level_of_study'],
                        'semester' => $fee['semester'],
                        'type'=> 'COURSE'
                    ];

                    $totalCourseCharges += $fee['amount_charged'];
                }

            }
        }

        // @todo for now we dont raise invoice for course reg fees
//        foreach ($courses as $course) {
//            // Only allow students to register for units that have their charges already defined
//            $courseFee = CourseFee::tryFrom($course['type']);
//
//            if ($courseFee === null) {
//                throw new NotFoundHttpException('This program\'s ' . $course['type'] . ' fee is not set');
//            }
//
//            $description = strtolower(str_replace(' ', '', $courseFee->feeDescription()));
//
//            // Look for a per-unit fee matching this course type. If the program isn't
//            // billed per unit (e.g. it's billed via a block TUITION fee instead),
//            // no match will be found, and we bill 0 for this course entry.
//            $matchedFee = null;
//            foreach ($fees as $fee) {
//                if ($fee['frequency'] !== ChargeFrequency::UNIT->value) {
//                    continue;
//                } //print_r($fee); exit;
//
//                if (strtolower(str_replace(' ', '', $fee['description'])) === $description) {
//                    $matchedFee = $fee;
//                    break;
//                }
//            }
//
//            $courseCharges[] = [
//                'chargeTypeId' => $matchedFee['charge_type_id'] ?? null,
//                'description' => $matchedFee['description'] ?? $courseFee->feeDescription(),
//                'amount' => $matchedFee['amount_charged'] ?? 0,
//                'type' => $course['type'],
//            ];
//
//            $totalCourseCharges += $matchedFee['amount_charged'] ?? 0;
//        }

//        print_r($courseCharges); exit;

        return [
            'items' => $courseCharges,
            'total' => $totalCourseCharges
        ];
    }

    /**
     * @param string $feeType
     * @return array
     */
    private function fees(string $feeType): array
    {
//        print_r($feeType); exit;

//        print_r($this->student->progCurrId); exit;

        $fees = (new Query())->select([
            'pcc.charge_type_id',
            'pcc.prog_curr_id',
            'fi.fee_description',
            'fi.fee_type',
            'pcc.amount_charged',
            'pcc.level_of_study',
            'pcc.semester',
            'bf.name as frequency'
        ])
            ->from('smisportal.fss_prog_curr_charges pcc')
            ->innerJoin('smisportal.fss_fee_items fi', 'fi.fee_code=pcc.fee_code')
            ->innerJoin('smisportal.fs_billing_frequency bf', 'bf.billing_frequency_id=pcc.billing_frequency_id')
            ->where([
                'pcc.prog_curr_id' => $this->student->progCurrId,
                'pcc.acad_session_id' => $this->student->academicSessionId,
                'fi.fee_type' => $feeType,
                'fi.priority' => FeePriority::PRIORITY_1->value,
                'fi.publish' => FeeStatus::PUBLISHED->value,
                'pcc.level_of_study' => $this->student->level,
                'pcc.semester' => $this->student->semester
            ]);

//        print_r($fees->all()); exit;

        return $fees->all();
    }
}
