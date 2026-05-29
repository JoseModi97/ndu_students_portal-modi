<?php

// namespace app\controllers;
namespace app\modules\caution\controllers;

use Yii;
use yii\web\Controller;

/**
 * CautionRefundController
 *
 * Only 2 imports (both core Yii — no models needed).
 * All DB work done with raw createCommand() queries.
 *
 * Connections expected in config/web.php:
 *   'db'     => MySQL  (caution_refunds, student_banks, student_bank_branches)
 *   'oracle' => Oracle (MUTHONI.*, CLEARANCE.*)
 *
 * Routes:
 *   GET/POST /caution-refund/index        – main page (form, status, not-eligible)
 *   GET      /caution-refund/branches     – AJAX JSON branch list
 */
class CautionController extends Controller
{
    // ─── Auth guard ──────────────────────────────────────────────────────────
    // Replace Yii::$app->session->get('regNo') with however your app
    // stores the logged-in student's registration number.
    // ─────────────────────────────────────────────────────────────────────────
    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) return false;

        if (Yii::$app->session->get('regNo') === null) {
            Yii::$app->response->redirect(['/site/login']);
            return false;
        }
        return true;
    }

    // =========================================================================
    // ACTION index  — GET shows form/status,  POST saves request
    // =========================================================================
    public function actionIndex()
    {
        $mysql  = Yii::$app->db;
        $oracle = Yii::$app->oracle;
        $regNo  = Yii::$app->session->get('regNo');

        // 1 ── Student category + CHSS check ───────────────────────────────────
        $studentCategory = (string) $oracle->createCommand(
            "SELECT STC_STUDENT_CATEGORY_ID FROM MUTHONI.UON_STUDENTS
              WHERE REGISTRATION_NUMBER = :reg",
            [':reg' => $regNo]
        )->queryScalar();

        $isChss = (bool) $oracle->createCommand(
            "SELECT COUNT(*) FROM MUTHONI.UON_STUDENTS S
               JOIN MUTHONI.DEGREE_PROGRAMMES DP ON S.D_PROG_DEGREE_CODE  = DP.DEGREE_CODE
               JOIN MUTHONI.FACULTIES          F ON DP.FACUL_FAC_CODE      = F.FAC_CODE
               JOIN MUTHONI.COLLEGES           C ON F.COL_CODE             = C.COL_CODE
               JOIN MUTHONI.GRADUANDS          G ON G.REGISTRATION_NUMBER  = S.REGISTRATION_NUMBER
             WHERE S.STC_STUDENT_CATEGORY_ID = '001'
               AND F.COL_CODE  = 'CHSS'
               AND G.GRAD_CODE >= 52
               AND S.REGISTRATION_NUMBER = :reg",
            [':reg' => $regNo]
        )->queryScalar();

        if (!in_array($studentCategory, ['003', '004'], true) && !$isChss) {
            return $this->render('index', [
                'mode'    => 'not-eligible',
                'message' => 'Only Module II students can apply for caution money refund online at the moment.',
            ]);
        }

        // 2 ── Already submitted? Show status ─────────────────────────────────
        $existing = $mysql->createCommand(
            "SELECT cr.*, sb.bank_name, sbb.branch_name
               FROM caution_refunds       cr
          LEFT JOIN student_banks         sb  ON cr.bank_id   = sb.bank_id
          LEFT JOIN student_bank_branches sbb ON cr.branch_id = sbb.branch_id
             WHERE cr.registration_no = :reg AND cr.refund_type = 'CAUTION'
             LIMIT 1",
            [':reg' => $regNo]
        )->queryOne();

        if ($existing) {
            return $this->render('index', [
                'mode'            => 'status',
                'data'            => $existing,
                'approvalMessage' => $this->approvalMessage($oracle, $regNo),
                'isChss'          => $isChss,
            ]);
        }

        // 3 ── Clearance + fee balance ─────────────────────────────────────────
        $cleared = (bool) $oracle->createCommand(
            "SELECT COUNT(*) FROM CLEARANCE.CLEARING_STUDENT CS
               JOIN MUTHONI.UON_STUDENTS       S  ON CS.REGISTRATION_NUMBER = S.REGISTRATION_NUMBER
               JOIN MUTHONI.DEGREE_PROGRAMMES  DP ON S.D_PROG_DEGREE_CODE   = DP.DEGREE_CODE
             WHERE CS.REGISTRATION_NUMBER = :reg AND CS.CLEARED = 'YES'",
            [':reg' => $regNo]
        )->queryScalar();

        $feeBalance = (float) $oracle->createCommand(
            "SELECT MUTHONI.GET_BALANCE(:reg,'2015/2016') FROM DUAL",
            [':reg' => $regNo]
        )->queryScalar();

        if (!$cleared || $feeBalance >= 1) {
            return $this->render('index', [
                'mode'    => 'not-eligible',
                'message' => 'You have not cleared from the University or you have an outstanding fee balance.',
            ]);
        }

        // 4 ── Build bank list ─────────────────────────────────────────────────
        $bankList = array_column(
            $mysql->createCommand("SELECT bank_id, bank_name FROM student_banks ORDER BY bank_name")->queryAll(),
            'bank_name',
            'bank_id'
        );

        // 5 ── Handle POST ─────────────────────────────────────────────────────
        $errors = $post = $branchList = [];

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            $mobileNo   = trim($post['mobile_no']         ?? '');
            $email      = trim($post['email']             ?? '');
            $amountRaw  = trim($post['amount_refundable'] ?? '');
            $amount     = (float) preg_replace('/[^0-9]/', '', $amountRaw);
            $refundType = trim($post['refund_type']       ?? '');
            $passportId = trim($post['passport_id']       ?? '');
            $accName    = trim($post['acc_name']          ?? '');
            $accNo      = trim($post['acc_no']            ?? '');
            $bankId     = (int)($post['bank_id']          ?? 0);
            $branchId   = (int)($post['branch_id']        ?? 0);
            $declared   = isset($post['declarataion_status']);

            if (!$mobileNo)   $errors['mobile_no']          = 'Mobile number is required.';
            if (!$amountRaw)  $errors['amount_refundable']   = 'Amount is required.';
            if (!$refundType) $errors['refund_type']         = 'Refund type is required.';
            if (!$passportId) $errors['passport_id']         = 'ID / Passport number is required.';
            if (!$declared)   $errors['declarataion_status'] = 'Please accept the declaration before submitting.';

            if (!$isChss) {
                if (!$accName)  $errors['acc_name']  = 'Account name is required.';
                if (!$accNo)    $errors['acc_no']    = 'Account number is required.';
                if (!$bankId)   $errors['bank_id']   = 'Please select a bank.';
                if (!$branchId) $errors['branch_id'] = 'Please select a branch.';
            }

            // Reload branches so the select re-populates on error
            if ($bankId > 0) {
                $branchList = array_column(
                    $mysql->createCommand(
                        "SELECT branch_id, branch_name FROM student_bank_branches
                          WHERE bank_id = :bid ORDER BY branch_name",
                        [':bid' => $bankId]
                    )->queryAll(),
                    'branch_name',
                    'branch_id'
                );
            }

            if (empty($errors)) {
                if ($this->saveRequest(
                    $mysql,
                    $oracle,
                    $regNo,
                    $mobileNo,
                    $email,
                    $amount,
                    $refundType,
                    $passportId,
                    $accName,
                    $accNo,
                    $bankId,
                    $branchId,
                    $isChss ? 'MODULE_I' : null,
                    $isChss
                )) {
                    Yii::$app->session->setFlash(
                        'success',
                        'Request submitted successfully. You will be updated on the status through this portal.'
                    );
                    return $this->redirect(['index']);
                }
                Yii::$app->session->setFlash(
                    'danger',
                    'Your application was not successful. Please try again later.'
                );
            }
        }

        // 6 ── Render form ─────────────────────────────────────────────────────
        return $this->render('index', [
            'mode'       => $isChss ? 'form-chss' : 'form',
            'post'       => $post,
            'errors'     => $errors,
            'bankList'   => $bankList,
            'branchList' => $branchList,
        ]);
    }

    // =========================================================================
    // ACTION branches — AJAX JSON branch list
    // =========================================================================
    public function actionBranches(): string
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $bankId = (int) Yii::$app->request->get('bank_id', 0);
        if ($bankId <= 0) return json_encode([]);

        return json_encode(
            Yii::$app->db->createCommand(
                "SELECT branch_id, branch_name FROM student_bank_branches
                  WHERE bank_id = :bid ORDER BY branch_name",
                [':bid' => $bankId]
            )->queryAll()
        );
    }

    // =========================================================================
    // PRIVATE helpers
    // =========================================================================
    private function saveRequest(
        $mysql,
        $oracle,
        string $regNo,
        string $mobileNo,
        string $email,
        float $amount,
        string $refundType,
        string $passportId,
        string $accName,
        string $accNo,
        int $bankId,
        int $branchId,
        ?string $requestType,
        bool $isChss
    ): bool {
        $branchCode = '';
        if (!$isChss && $branchId) {
            $branchCode = (string) $mysql->createCommand(
                "SELECT branch_code FROM student_bank_branches WHERE branch_id = :id",
                [':id' => $branchId]
            )->queryScalar();
        }

        $requestId = $oracle->createCommand(
            "SELECT MUTHONI.REQUEST_ID_SEQ.NEXTVAL FROM DUAL"
        )->queryScalar();

        $mt = $mysql->beginTransaction();
        $ot = $oracle->beginTransaction();
        try {
            $mysql->createCommand()->insert('caution_refunds', [
                'registration_no'   => $regNo,
                'mobile_no'         => $mobileNo,
                'email'             => $email,
                'application_date'  => date('Y-m-d'),
                'acc_no'            => $accNo,
                'acc_name'          => $accName,
                'bank_id'           => $bankId   ?: null,
                'branch_id'         => $branchId ?: null,
                'passport_id'       => $passportId,
                'amount_refundable' => $amount,
                'refund_type'       => $refundType,
                'request_type'      => $requestType,
            ])->execute();

            $oracle->createCommand()->insert('MUTHONI.CAUTION_REFUNDS', [
                'REQUEST_ID'       => $requestId,
                'REGISTRATION_NO'  => $regNo,
                'MOBILE_NO'        => $mobileNo,
                'EMAIL'            => $email,
                'APPLICATION_DATE' => new \yii\db\Expression('SYSDATE'),
                'ACCOUNT_NO'       => $accNo,
                'ACCOUNT_NAME'     => $accName,
                'BANK_ID'          => $bankId      ?: null,
                'PASSPORT_ID'      => $passportId,
                'AMOUNT_REQUESTED' => $amount,
                'BRANCH_ID'        => $branchCode  ?: null,
                'REFUND_TYPE'      => $refundType,
                'REQUEST_TYPE'     => $requestType,
            ])->execute();

            $mt->commit();
            $ot->commit();
            return true;
        } catch (\Exception $e) {
            $mt->rollBack();
            $ot->rollBack();
            Yii::error('CautionRefund save failed: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }

    private function approvalMessage($oracle, string $regNo): string
    {
        $refunded = (bool) $oracle->createCommand(
            "SELECT COUNT(*) FROM MUTHONI.CAUTION_REFUNDS
             WHERE APPROVAL_STATUS='APPROVED' AND REFUND_STATUS='REFUNDED'
               AND REFUND_TYPE='CAUTION' AND REGISTRATION_NO=:reg",
            [':reg' => $regNo]
        )->queryScalar();

        if ($refunded) {
            $date = $oracle->createCommand(
                "SELECT RD.DATE_BANKED FROM MUTHONI.CAUTION_REFUNDS CR
                   JOIN MUTHONI.REFUND_DETAILS RD ON CR.VOUCHER_NO = RD.PV_NO
                 WHERE CR.REGISTRATION_NO=:reg AND RD.STATUS='PAID'",
                [':reg' => $regNo]
            )->queryScalar();
            return $date
                ? "Your Caution Money was refunded on {$date}. Please check with your bank."
                : "Your Caution Money has been approved. Payment is being processed.";
        }

        $levels = $oracle->createCommand(
            "SELECT APPROVAL_ORDER FROM MUTHONI.APPROVAL_LEVELS ORDER BY APPROVAL_ORDER DESC"
        )->queryAll();

        foreach ($levels as $lvl) {
            $row = $oracle->createCommand(
                "SELECT AL.DESCRIPTION, AP.APPROVAL_STATUS, AP.REMARKS
                   FROM MUTHONI.APPROVAL_LEVELS AL
                   JOIN MUTHONI.APPROVAL_PROCESS AP ON AP.APPROVAL_LEVEL_ID = AL.APPROVAL_LEVEL_ID
                   JOIN MUTHONI.CAUTION_REFUNDS  CR ON CR.REQUEST_ID        = AP.REQUEST_ID
                 WHERE AL.APPROVAL_ORDER=:lvl AND CR.REGISTRATION_NO=:reg",
                [':lvl' => $lvl['APPROVAL_ORDER'], ':reg' => $regNo]
            )->queryOne();

            if ($row) {
                $msg = "Your request has been {$row['APPROVAL_STATUS']} by {$row['DESCRIPTION']}.";
                if (!empty($row['REMARKS'])) $msg .= " Remarks: {$row['REMARKS']}";
                return $msg;
            }
        }
        return 'Your request is pending approval.';
    }
}
