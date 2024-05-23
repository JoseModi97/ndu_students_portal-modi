<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/21/2024
 * @time: 11:46 AM
 */

namespace app\controllers;

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

        $billStudent->bill();


    }
}