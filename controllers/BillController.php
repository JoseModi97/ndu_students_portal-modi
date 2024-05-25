<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/21/2024
 * @time: 11:46 AM
 */

namespace app\controllers;

use app\enums\AdminFee;
use app\services\BillStudent;
use app\services\StudentToBill;
use Exception;
use yii\web\Controller;

class BillController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionIndex()
    {
        $regNumber = 'ND201/0001/2022';

        $billStudent = new BillStudent(new StudentToBill($regNumber));

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
        $billStudent->bill($payableFees);

        $courses = [
            [
                'code' => 'SMA101',
                'type' => 'FA'
            ],
            [
                'code' => 'SMA102',
                'type' => 'FA'
            ],
            [
                'code' => 'SMA103',
                'type' => 'FA'
            ],
            [
                'code' => 'SMA104',
                'type' => 'FA'
            ],
            [
                'code' => 'SMA105',
                'type' => 'PROJECT'
            ]
        ];

        $billStudent->bill($billStudent->payableFees($courses));
    }
}