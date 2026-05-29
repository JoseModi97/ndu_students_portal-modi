<?php

namespace app\modules\caution_refund\controllers;

use app\controllers\BaseController;
use app\modules\caution_refund\models\Bank;
use app\modules\caution_refund\models\BankBranch;
use app\modules\caution_refund\models\ApprovalLevel;
use app\modules\caution_refund\models\CautionRefund;
use app\modules\caution_refund\models\CautionRefundOfficial;
use app\modules\caution_refund\models\ApprovalProcess;
use app\modules\caution_refund\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Default controller for the `caution_refund` module
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
     * Check if a student is eligible for caution refund
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
            ->select(['s.status', 'spc.status_id'])
            ->from('smis.sm_student_programme_curriculum spc')
            ->leftJoin('smis.sm_student_status s', 'spc.status_id = s.status_id')
            ->where(['spc.registration_number' => $regNumber])
            ->one(Yii::$app->smisDb);

        $academicStatus = $smisStudentData['status'] ?? 'UNKNOWN';
        $balance = $this->calculateFeeBalance($regNumber);
        
        $eligible = true;
        $reason = null;
        $allowedStatuses = ['GRADUATED', 'COMPLETED'];

        /** @var \app\modules\caution_refund\Module $module */
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
            $reason = 'Caution refunds are only available for GRADUATED or COMPLETED students. Your current status: ' . $academicStatus;
        }

        return [
            'eligible' => $eligible,
            'reason' => $reason,
            'academicStatus' => $academicStatus,
            'balance' => $balance,
            'smisStudentData' => $smisStudentData
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

        $allLevels = ApprovalLevel::find()->orderBy(['level_order' => SORT_ASC])->all();
        $existingRequest = CautionRefund::findOne(['registration_number' => $regNumber]);

        // Mode: STATUS (Already applied)
        if ($existingRequest) {
            // Fetch the official status from SMIS
            $officialStatus = (new \yii\db\Query())
                ->select(['status'])
                ->from('smis.sm_caution_refund_official')
                ->where(['registration_number' => $regNumber])
                ->scalar(Yii::$app->smisDb);

            if ($officialStatus && $existingRequest->status !== $officialStatus) {
                $existingRequest->status = $officialStatus;
                $existingRequest->save(false);
            }

            $approvals = ApprovalProcess::find()
                ->joinWith('level')
                ->joinWith('refundOfficial')
                ->where(['smis.sm_caution_refund_official.registration_number' => $regNumber])
                ->orderBy(['smis.sm_approval_level.level_order' => SORT_ASC])
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
     * Create a new caution refund request
     * @return string|Response
     */
    public function actionApply()
    {
        /** @var User $user */
        $user = User::findOne(Yii::$app->user->id);
        $regNumber = $user->registration_number;

        $model = new CautionRefund();
        $model->student_id = $user->adm_refno;
        $model->registration_number = $regNumber;
        $model->status = 'PENDING';
        $model->refund_type = 'STANDARD';

        if ($this->request->isPost) {
            $post = $this->request->post();
            $model->load($post);
            
            // Manual validation for bank_id (not in model)
            $bankId = $post['bank_id'] ?? null;
            $isValid = $model->validate();
            
            if ($model->refund_type === 'STANDARD' && empty($bankId)) {
                $model->addError('bank_branch_id', 'Please select a bank first.');
                $isValid = false;
            }

            if ($isValid) {
                $transactionPortal = Yii::$app->db->beginTransaction();
                $transactionSmis = Yii::$app->smisDb->beginTransaction();

                try {
                    if ($model->save(false)) {
                        $official = new CautionRefundOfficial();
                        $official->registration_number = $regNumber;
                        $official->amount = $model->refund_amount;
                        $official->status = 'PENDING';
                        
                        if ($official->save()) {
                            $transactionPortal->commit();
                            $transactionSmis->commit();
                            $this->setFlash('success', 'Caution Refund', 'Your application has been submitted successfully.');
                            return $this->redirect(['index']);
                        } else {
                            throw new \Exception('Failed to save official record.');
                        }
                    } else {
                        throw new \Exception('Failed to save local record.');
                    }
                } catch (\Exception $e) {
                    $transactionPortal->rollBack();
                    $transactionSmis->rollBack();
                    $this->setFlash('danger', 'Error', 'An error occurred: ' . $e->getMessage());
                }
            }
        }

        $banks = Bank::find()->all();

        return $this->render('apply', [
            'model' => $model,
            'banks' => $banks,
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
        return BankBranch::find()->where(['bank_id' => $bankId])->all();
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

        $request = CautionRefund::findOne(['registration_number' => $regNumber]);
        $approvals = [];
        if ($request) {
            $approvals = ApprovalProcess::find()
                ->joinWith('level')
                ->joinWith('refundOfficial')
                ->where(['smis.sm_caution_refund_official.registration_number' => $regNumber])
                ->orderBy(['smis.sm_approval_level.level_order' => SORT_ASC])
                ->all();
        }

        $allLevels = ApprovalLevel::find()->orderBy(['level_order' => SORT_ASC])->all();
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
