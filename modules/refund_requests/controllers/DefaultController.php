<?php

namespace app\modules\refund_requests\controllers;

use app\controllers\BaseController;
use app\modules\refund_requests\models\Bank;
use app\modules\refund_requests\models\BankBranch;
use app\modules\refund_requests\models\ApprovalLevel;
use app\modules\refund_requests\models\RefundRequest;
use app\modules\refund_requests\models\ApprovalProcess;
use app\modules\refund_requests\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Default controller for the `refund_requests` module
 */
class DefaultController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // Always allow the dashboard (index) to load so the user can see the checklist
        if ($action->id === 'index' || $action->id === 'branches') {
            return true;
        }

        /** @var User $user */
        $user = User::findOne(Yii::$app->user->id);
        $check = $this->checkEligibility($user);

        if (!$check['eligible']) {
            $this->setFlash('danger', 'Requirement Not Met', $check['reason']);
            $this->redirect(['index'])->send();
            return false;
        }

        return true;
    }

    /**
     * Check if a student is eligible for refund request
     * @param User $user
     * @return array ['eligible' => bool, 'reason' => string|null, 'academicStatus' => string, 'balance' => float]
     */
    private function checkEligibility(User $user): array
    {
        $regNumber = $user->registration_number;
        if (empty($regNumber)) {
            return [
                'eligible' => false,
                'reason' => 'No registration number found. Please contact the administrator.',
                'academicStatus' => 'UNKNOWN',
                'balance' => 0
            ];
        }

        // Fetch Academic Status from authoritative SMIS database
        $smisStudentData = (new \yii\db\Query())
            ->select(['s.status', 'spc.status_id', 'spc.student_prog_curriculum_id'])
            ->from('smis.sm_student_programme_curriculum spc')
            ->leftJoin('smis.sm_student_status s', 'spc.status_id = s.status_id')
            ->where(['spc.registration_number' => $regNumber])
            ->one(Yii::$app->smisDb);

        $academicStatus = $smisStudentData['status'] ?? 'UNKNOWN';
        $balance = $this->calculateFeeBalance($regNumber);
        
        $eligible = true;
        $reason = null;
        $allowedStatuses = ['GRADUATED', 'COMPLETED'];

        /** @var \app\modules\refund_requests\Module $module */
        $module = $this->module;
        $feeBalanceSatisfied = $balance <= 0 || $module->overrideFeeBalance;

        if (strtoupper($user->clearance_status) !== 'CLEARED') {
            $eligible = false;
            $reason = 'You must be CLEARED to access this feature. Current status: ' . ($user->clearance_status ?: 'PENDING');
        } elseif (!$feeBalanceSatisfied) {
            $eligible = false;
            $reason = 'You have an outstanding fee balance of ' . Yii::$app->formatter->asCurrency($balance) . '. Please clear your balance.';
        } elseif (!in_array(strtoupper($academicStatus), $allowedStatuses)) {
            $eligible = false;
            $reason = 'Refund requests are only available for GRADUATED or COMPLETED students. Your current status: ' . $academicStatus;
        }

        return [
            'eligible' => $eligible,
            'reason' => $reason,
            'academicStatus' => $academicStatus,
            'balance' => $balance,
            'smisStudentData' => $smisStudentData,
            'student_prog_curriculum_id' => $smisStudentData['student_prog_curriculum_id'] ?? null
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        /** @var User $user */
        $user = User::findOne(Yii::$app->user->id);
        $regNumber = $user->registration_number;

        $check = $this->checkEligibility($user);
        $academicStatus = $check['academicStatus'];

        // Synchronize student status to portal if it differs
        if (!empty($check['smisStudentData'])) {
            $portalStatusId = (new \yii\db\Query())
                ->select(['status_id'])
                ->from('smisportal.sm_student_status')
                ->where(['status' => $academicStatus])
                ->scalar();
            
            if ($portalStatusId) {
                Yii::$app->db->createCommand()->update('smisportal.sm_student_programme_curriculum',
                    ['status_id' => $portalStatusId],
                    ['registration_number' => $regNumber]
                )->execute();
            }
        }

        $allLevels = ApprovalLevel::find()->orderBy(['approval_order' => SORT_ASC])->all();
        $refundTypes = \app\modules\refund_requests\models\RefundType::find()->where(['refund_type_status' => true])->all();
        
        $existingRequest = null;
        if ($check['student_prog_curriculum_id']) {
            $existingRequest = RefundRequest::findOne(['student_prog_curriculum_id' => $check['student_prog_curriculum_id']]);
        }

        // Mode: STATUS (Already applied)
        if ($existingRequest) {
            $approvals = ApprovalProcess::find()
                ->where(['request_id' => $existingRequest->request_id])
                ->all();

            return $this->render('index', [
                'mode' => 'status',
                'user' => $user,
                'request' => $existingRequest,
                'approvals' => $approvals,
                'allLevels' => $allLevels,
                'academicStatus' => $academicStatus
            ]);
        }

        return $this->render('index', [
            'mode' => $check['eligible'] ? 'eligibility' : 'not-eligible',
            'user' => $user,
            'eligible' => $check['eligible'],
            'reason' => $check['reason'],
            'balance' => $check['balance'],
            'allLevels' => $allLevels,
            'refundTypes' => $refundTypes,
            'academicStatus' => $academicStatus
        ]);
    }

    /**
     * Calculate fee balance from transactions
     * @param string $regNumber
     * @return float
     */
    private function calculateFeeBalance(string $regNumber): float
    {
        $transactions = (new \yii\db\Query())
            ->select(['trans_amount', 'trans_type'])
            ->from('smis.fss_fee_transactions')
            ->where(['LIKE', 'progress_code', $regNumber . '%', false])
            ->all(Yii::$app->smisDb);

        $credits = 0;
        $debits = 0;
        foreach ($transactions as $transaction) {
            if ($transaction['trans_type'] === 'CR') {
                $credits += $transaction['trans_amount'];
            }
            if ($transaction['trans_type'] === 'DR') {
                $debits += $transaction['trans_amount'];
            }
        }
        return $debits - $credits;
    }

    /**
     * Create a new refund request
     * @return string|Response
     */
    public function actionApply()
    {
        /** @var User $user */
        $user = User::findOne(Yii::$app->user->id);
        $regNumber = $user->registration_number;

        $check = $this->checkEligibility($user);
        if (!$check['student_prog_curriculum_id']) {
            $this->setFlash('danger', 'Error', 'Could not resolve your curriculum record. Please contact the administrator.');
            return $this->redirect(['index']);
        }

        $model = new RefundRequest();

        if ($type = $this->request->get('type')) {
            $model->refund_type = $type;
        } else {
            // Default to STANDARD if no type is specified
            $standardType = \app\modules\refund_requests\models\RefundType::findOne(['refund_type_name' => 'STANDARD']);
            if ($standardType) {
                $model->refund_type = $standardType->refund_type_id;
            }
        }

        $model->student_prog_curriculum_id = $check['student_prog_curriculum_id'];
        $model->application_date = date('Y-m-d H:i:s');
        $model->refund_status = 'PENDING';
        $model->approval_status = 'PENDING';
        $model->declaration_status = '0';
        $model->email = $user->primary_email;
        $model->passport_id = $user->passport_no ?: ($user->national_id ?: 'N/A');
        $model->account_name = $user->surname . ' ' . $user->other_names;
        $model->mobile_no = $user->primary_phone_no ?: '0000000000';
        $model->payment_method = 'bank';

        if ($this->request->isPost) {
            $post = $this->request->post();

            if ($model->load($post)) {
                if ($model->payment_method === 'mpesa') {
                    $model->branch_id = null;
                    $model->account_no = null;
                    $model->bank_id = null;
                }

                if (empty($model->mobile_no)) {
                    $model->mobile_no = $user->primary_phone_no ?: '0000000000';
                }

                // Generate a request_id if not identity
                $maxId = RefundRequest::find()->max('request_id');
                $model->request_id = ($maxId ?: 0) + 1;

                if ($model->save()) {
                    $this->setFlash('success', 'Refund Request', 'Your application has been submitted successfully and will be synchronized soon.');
                    return $this->redirect(['index']);
                } else {
                    $errors = implode('<br>', \yii\helpers\ArrayHelper::getColumn($model->getErrors(), 0));
                    $this->setFlash('danger', 'Error', 'Failed to save your application: ' . $errors);
                }
            } else {
                $errors = implode('<br>', \yii\helpers\ArrayHelper::getColumn($model->getErrors(), 0));
                $this->setFlash('danger', 'Error', 'Failed to load your application data: ' . $errors);
            }
        }

        $banks = Bank::find()->all();
        $refundTypes = \app\modules\refund_requests\models\RefundType::find()->where(['refund_type_status' => true])->all();

        return $this->render('apply', [
            'model' => $model,
            'banks' => $banks,
            'refundTypes' => $refundTypes,
            'regNumber' => $regNumber
        ]);
    }

    /**
     * Get branches for a specific bank (AJAX)
     * @param int $bankId
     * @return array
     */
    public function actionBranches($bankId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $bank = Bank::findOne($bankId);
        if ($bank) {
            return BankBranch::find()->where(['bank_code' => $bank->bank_code])->all();
        }
        return [];
    }

    /**
     * Dedicated interface to track the whole process
     * @return string
     */
    public function actionTrack()
    {
        /** @var User $user */
        $user = User::findOne(Yii::$app->user->id);
        $regNumber = $user->registration_number;
        $check = $this->checkEligibility($user);

        $request = null;
        $approvals = [];
        if ($check['student_prog_curriculum_id']) {
            $request = RefundRequest::findOne(['student_prog_curriculum_id' => $check['student_prog_curriculum_id']]);
            if ($request) {
                $approvals = ApprovalProcess::find()
                    ->where(['request_id' => $request->request_id])
                    ->all();
            }
        }

        $allLevels = ApprovalLevel::find()->orderBy(['approval_order' => SORT_ASC])->all();
        $balance = $regNumber ? $this->calculateFeeBalance($regNumber) : 0;

        return $this->render('track', [
            'user' => $user,
            'request' => $request,
            'approvals' => $approvals,
            'allLevels' => $allLevels,
            'balance' => $balance
        ]);
    }
}
