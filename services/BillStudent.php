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
use app\models\Programmes;
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
        $followUpRegistration = false;

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

        $invoiceId = '%';
        $invoiceId .= $this->student->regNumber . '-' . $this->student->academicYear;
        if (!$this->student->isBilledAnnually) {
//            $invoiceId .= '-SEM' . $this->student->semester;
            $invoiceId .= '-SEM2';
        }
        $invoiceId .= '%';
//        dd($invoiceId);

        $invoiceDetails = InvoiceDetail::find()->where(['LIKE', 'invoice_id', $invoiceId, false])->asArray()->all();
//        dd($invoiceDetails);
        if (!empty($invoiceDetails)) {
            foreach ($invoiceDetails as $key => $detail) {
                if ($detail['invoice_detail_desc'] === AdminFee::REGISTRATION_FEES->value) {
                    unset($invoiceDetails[$key]);
                }
            }
        }

        if (!empty($invoiceDetails)) {
            $followUpRegistration = true;
        }
//        dd($invoiceDetails);
//        dd($followUpRegistration);

        $totalFees = 0;
        $adminFees = $this->payableAdminFees();

//        dd($this->student->isInATeachingSemester);
        // Admin fees are not applicable at follow-up registrations
        // Admin fees are not applicable in a supplementary semester
        if ($followUpRegistration || !$this->student->isInATeachingSemester) {
            $adminFees = [];
        }

//        dd($adminFees);

        // Only calculate for courses not yet invoiced
        $courseFees = $this->payableCourseFees($courses, $followUpRegistration);
        //dd($courseFees);

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
        $totalFees += $courseFees['total'];

        return [
            //'followUpRegistration' => $followUpRegistration,
            'adminFees' => $adminFees,
            'courseFees' => $courseFees,
            'total' => $totalFees,
            //'total' => $followUpRegistration ? $courseFees['total'] : $adminFees['total'] + $courseFees['total']
        ];
    }

    /**
     * @throws UnprocessableEntityHttpException
     * @throws ServerErrorHttpException
     * @throws Exception
     */
    public function bill(array $payableFees): void
    {
//                    dd($payableFees);
        $totalToPay = (int)$payableFees['total'];

        if ($this->isBalanceSufficient($totalToPay)) {
            $invoice = $this->storeInvoice($totalToPay);
            $this->storeTransaction($invoice);
            $this->storeInvoiceDetails($invoice, $payableFees);
        } else {
            throw new UnprocessableEntityHttpException('You have insufficient balance');
        }
    }

    /**
     * @param array $payableFees
     * @return array
     */
    public function detailedFeeItemsToBill(array $payableFees): array
    {
        /**
         * Billing is done in two or three steps:
         * First, we bill the admin (semester registration) fees
         * Second, we bill admin + course (units/tuition) fees during course registration
         * Third, we may bill follow-up course registration
         */
        $adminFeesItems = [];
        $courseFeesItems = [];
        if (array_key_exists('adminFees', $payableFees) && !empty($payableFees['adminFees'])) {
            $adminFeesItems = array_merge($payableFees['adminFees']['items']);
        }

        if (array_key_exists('courseFees', $payableFees) && !empty($payableFees['courseFees'])) {
            $courseFeesItems = array_merge($payableFees['courseFees']['items']);
        }

        return array_merge($adminFeesItems, $courseFeesItems);
    }

    /**
     * Check if student has enough balance to be deducted the amount payable
     * @param int $amountPayable
     * @return bool
     */
    public function isBalanceSufficient(int $amountPayable): bool
    {
        $totals = $this->totalTransactions();

        if (($totals['credits'] - $totals['debits']) < $amountPayable) {
            return false;
        }
        return true;
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
            ->all();

        if (empty($transactions)) {
            return false;
        }

        $credits = 0;
        $debits = 0;
        foreach ($transactions as $transaction) {
            if ($transaction['trans_type'] === InvoiceType::CR->value) {
                $credits += $transaction['trans_amount'];
            }

            if ($transaction['trans_type'] === InvoiceType::DR->value) {
                $debits += $transaction['trans_amount'];
            }
        }

        return [
            'credits' => $credits,
            'debits' => $debits
        ];
    }

    /**
     * @throws Exception
     */
    private function storeInvoice(int $amount): Invoice
    {
        $invoice = new Invoice();
        //$invoice->invoice_id = $this->student->regNumber . '-' . $this->student->academicYear . '-SEM' . $this->student->semester;
        $invoice->invoice_id = $this->student->regNumber . '-' . $this->student->academicYear . '-SEM2'; // @todo
        $invoice->invoice_desc = 'FEES PAYABLE FOR SEM ' . $this->student->semester;
        $invoice->invoice_date = SmisHelper::formatDate('now', 'Y-m-d');
        $invoice->last_update = $invoice->invoice_date;
        $invoice->user_id = $this->student->regNumber;
        $invoice->invoice_status = InvoiceStatus::FIRST->value;
        $invoice->amount = $amount;
        $invoice->exchange_rate = 1;
        $invoice->sync_status = false;
        $invoice->reg_number = $this->student->regNumber;
        $invoice->semester_id = $invoice->invoice_id;

        if ($invoice->save()) {
            // Prepend the invoice pk to the invoice_id of the just created invoice and update it
            $invoice->invoice_id = $invoice->id . '-' . $invoice->invoice_id;
            if (!$invoice->save()) {
                $this->checkForInvoiceStoreErrors($invoice);
            }
        } else {
            $this->checkForInvoiceStoreErrors($invoice);
        }

        return $invoice;
    }

    /**
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    private function checkForInvoiceStoreErrors(Invoice $invoice)
    {
        if (!$invoice->validate()) {
            throw new UnprocessableEntityHttpException(SmisHelper::getModelErrors($invoice->getErrors()));
        } else {
            throw new ServerErrorHttpException('An error occurred while creating invoice');
        }
    }

    /**
     * @param Invoice $invoice
     * @return void
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    private function storeTransaction(Invoice $invoice): void
    {
        $transaction = new FeeTransaction();
        $transaction->trans_id = $invoice->invoice_id;
        $transaction->academic_progress_id = $this->student->progressId;
        $transaction->trans_date = $invoice->invoice_date;
        $transaction->trans_type = InvoiceType::DR->value;
        $transaction->trans_amount = $invoice->amount;
        $transaction->trans_desc = $invoice->invoice_desc;
        $transaction->user_id = $invoice->user_id;
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
        $feeItems = $this->detailedFeeItemsToBill($payableFees);
//        dd($feeItems);

        foreach ($feeItems as $feeItem) {
            $detail = new InvoiceDetail();
            $detail->invoice_id = $invoice->invoice_id;
            $detail->trans_date = $invoice->invoice_date;;
            $detail->last_updated = $invoice->invoice_date;;
            $detail->amount = $feeItem['amount'];
            $detail->user_id = $invoice->user_id;
            $detail->charge_type_id = $invoice->invoice_id; // @todo value to set to be clarified
            $detail->sync_status = false;

            if (array_key_exists('type', $feeItem)) { // course fees
                $detail->invoice_detail_desc = $feeItem['type']; // reg type e.g. FA, PROJECT
                $detail->trans_code = $feeItem['desc']; // course code e.g. SMA101
            } else { // admin fees
                $detail->invoice_detail_desc = $feeItem['desc']; // fee desc e.g. Library fees
                $detail->trans_code = FeeItem::find()->select('fee_code_alias')
                    ->where(['fee_description' => $feeItem['desc']])
                    ->asArray()->one()['fee_code_alias'];
            }

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
     * @return array
     */
    #[ArrayShape(['items' => "array", 'total' => "int"])]
    private function payableAdminFees(): array
    {
        $adminFees = $this->fees(FeeType::ADMIN->value);

        // Remove the registration fees.
        // This is charged outside this routine when a student joins in the semester session.
        foreach ($adminFees as $key => $adminFee) {
            if ($adminFee['fee_description'] === AdminFee::REGISTRATION_FEES->value) {
                unset($adminFees[$key]);
            }
        }

        $total = 0;
        $adminCharges = [];

        // Some fees e.g. caution money are charged only once in a student's life. We bill these at 1st year semester 1
        // Note that some fees are charged once but not needed to be billed in the course of a student's progression journey.
        // Fees like gown and cap during graduation. We take note of these types and assign them a priority of 2.
        // We assume that these will be charged outside this work flow.
        foreach ($adminFees as $adminFee) {
            if ($adminFee['frequency'] === ChargeFrequency::ONCE->value &&
                $this->student->level === 1 &&
                $this->student->isInAFirstSemester) {

                $total += $adminFee['amount_charged'];
                $adminCharges[] = [
                    'desc' => $adminFee['fee_description'],
                    'amount' => $adminFee['amount_charged']
                ];
            }
        }

        foreach ($adminFees as $adminFee) {
            if ($this->student->isBilledAnnually) {
                if ($this->student->isInAFirstSemester) {
                    if ($adminFee['frequency'] === ChargeFrequency::ANNUAL->value) {
                        $adminCharges[] = [
                            'desc' => $adminFee['fee_description'],
                            'amount' => $adminFee['amount_charged']
                        ];

                        $total += $adminFee['amount_charged'];
                    }
                } else {
                    $total = 0;
                }
            } else {
                if ($adminFee['frequency'] === ChargeFrequency::ANNUAL->value) { // @todo revert to SEMESTER
                    $adminCharges[] = [
                        'desc' => $adminFee['fee_description'],
                        'amount' => $adminFee['amount_charged']
                    ];

                    $total += $adminFee['amount_charged'];
                }
            }
        }

        return [
            'items' => $adminCharges,
            'total' => $total
        ];
    }

    /**
     * @param array $courses
     * @param bool $followUpRegistration
     * @return array
     * @throws NotFoundHttpException
     */
    #[ArrayShape(['items' => "array", 'total' => "int|mixed"])]
    private function payableCourseFees(array $courses, bool $followUpRegistration): array
    {
        $fees = $this->fees(FeeType::COURSE->value);

        $tempFees = [];
        foreach ($fees as $fee) {
            $tempFees[$fee['fee_description']] = $fee['amount_charged'];
        }

        $totalUnitAmount = 0;
        $tuitionAmount = 0;
        $courseCharges = [];
        foreach ($courses as $course) {
            // To always make sure that the course coming in can be billed, only allow students to register for units that
            // have their charges already defined
            $courseFee = CourseFee::tryFrom($course['type']);
            $unitAmount = $tempFees[$courseFee->feeDescription()];
            $unitAmount = 0; // @todo revert
            $courseCharges[] = [
                'desc' => $course['code'],
                'amount' => $unitAmount,
                'type' => $course['type']
            ];
            $totalUnitAmount += $unitAmount;
        }

        /**
         * Tuition fee is charged under the following terms:
         * program must be billed per semester
         * must be the initial registration
         * student must be in a teaching semester
         */
        if (!$this->student->isBilledAnnually && !$followUpRegistration && $this->student->isInATeachingSemester) {
            try {
                $tuitionAmount = (int)$tempFees[CourseFee::tryFrom('TUITION')->feeDescription()];
                $tuitionAmount = 50000; // @todo revert
                $courseCharges['tuition'] = [
                    'desc' => 'TUITION',
                    'amount' => $tuitionAmount,
                    'type' => 'TUITION'
                ];
            } catch (\Exception $ex) {
                throw new NotFoundHttpException('This program\'s TUITION fee is not set');
            }
        }

        return [
            'items' => $courseCharges,
            'total' => $totalUnitAmount + $tuitionAmount
        ];
    }

    /**
     * @param string $feeType
     * @return array
     */
    private function fees(string $feeType): array
    {
        $fees = (new Query())->select([
            'fi.fee_description',
            'pcc.amount_charged',
            'pcc.level_of_study',
            'pcc.semester',
            'bf.name as frequency'
        ])
            ->from('smisportal.fss_prog_curr_charges pcc')
            ->innerJoin('smisportal.fss_fee_items fi', 'fi.fee_code=pcc.fee_code')
            ->innerJoin('smisportal.fss_billing_frequency bf', 'bf.billing_frequency_id=pcc.billing_frequency_id')
            ->where([
                'pcc.prog_curr_id' => $this->student->progCurrId,
                'pcc.acad_session_id' => $this->student->academicSessionId,
                'fi.fee_type' => $feeType,
                'fi.priority' => FeePriority::PRIORITY_1->value,
                'fi.publish' => FeeStatus::PUBLISHED->value
            ]);
        // @todo revert
//        if (!$this->student->isBilledAnnually) {
//            $fees->andWhere([
//                'pcc.level_of_study' => $this->student->level,
//                'pcc.semester' => $this->student->semester
//            ]);
//        }

        return $fees->all();
    }
}