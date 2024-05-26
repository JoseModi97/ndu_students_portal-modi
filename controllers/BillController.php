<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/21/2024
 * @time: 11:46 AM
 */

namespace app\controllers;

use app\enums\AdminFee;
use app\enums\FeePriority;
use app\enums\FeeStatus;
use app\enums\FeeType;
use app\models\Invoice;
use app\models\InvoiceDetail;
use app\services\BillStudent;
use app\services\StudentToBill;
use Exception;
use yii\db\Query;
use yii\web\Controller;

class BillController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionIndex()
    {
        $regNumber = 'ND201/0001/2022';
        $student = new StudentToBill($regNumber);
        $billStudent = new BillStudent($student);

        /**
         * Join session and registration fees - When student hasn't joined session - controller
         *
         * Pay admin fees and course fees if balance fits - reg all courses - best case
         *
         * Worst case is pay for few courses and all admin fees
         * RN pay admin fees + sma101, 102, 103
         * Later pay for sma104, 105
         *
         */

        $payableFees = [
            'adminFees' => [
                'items' => [
                    [
                        'desc' => AdminFee::REGISTRATION_FEES->value,
                        'amount' => 1000 // Amount charged for this fee item
                    ]
                ],
                'total' => 1000 // Total amount charged for the admin fees items
            ],
            'total' => 1000 // Grand total
        ];
//        dd($payableFees);
        $billStudent->bill($payableFees);

        /**
         * Join session and registration fees - When student hasn't joined session - controller
         *
         * Pay admin fees and course fees if balance fits - reg all courses - best case
         *
         * Worst case is pay for few courses and all admin fees
         * RN pay admin fees + sma101, 102, 103
         * Later pay for sma104, 105
         *
         * Non integrated
         * Check if balance can pay all admin fees - Payable as a block - all admin fees go into a single invoice
         * Check if balance can also pay for the course fees. If not inform students to remove some courses and recheck
         * or abort process and top up
         *
         * Worst case: Full admin fees already paid for, now courses paid for later - Check if these fees have been paid
         *
         */

        // get the session the student is in. with this
        // I will have the semester code
        // course registration will happen in the session the student is in
        // check for any invoices in that session
        // get the admin fees already paid for and the total
        // get any courses paid for and the total
        // subtract these totals from the main balance
        // only courses can be paid for partially
        $invoice = Invoice::find()->where(['semester_id' => 'ND201/0002/2022-2022/2023-SEM1'])->all();

        $courses = [
            // 1st registration
//            [
//                'code' => 'SMA101',
//                'type' => 'FA'
//            ],
//            [
//                'code' => 'SMA102',
//                'type' => 'FA'
//            ],
//            [
//                'code' => 'SMA103',
//                'type' => 'FA'
//            ],
//            [
//                'code' => 'SMA104',
//                'type' => 'FA'
//            ],
//            [
//                'code' => 'SMA105',
//                'type' => 'FA'
//            ],

            // 2nd registration
//            [
//                'code' => 'SMA106',
//                'type' => 'FA'
//            ],
//            [
//                'code' => 'SMA107',
//                'type' => 'FA'
//            ],

            // semester 2
//            [
//                'code' => 'SMA108',
//                'type' => 'FA'
//            ],
//            [
//                'code' => 'SMA109',
//                'type' => 'FA'
//            ],
//            [
//                'code' => 'SMA110',
//                'type' => 'FA'
//            ],
//            [
//                'code' => 'SMA111',
//                'type' => 'FA'
//            ],
//            [
//                'code' => 'SMA112',
//                'type' => 'FA'
//            ],

            // 2nd registration
            [
                'code' => 'SMA115',
                'type' => 'FA'
            ],
            [
                'code' => 'SMA116',
                'type' => 'FA'
            ],
        ];

//
        $payableFees = $billStudent->payableFees($courses);
//        dd($payableFees);

        $billStudent->bill($payableFees);
    }
}