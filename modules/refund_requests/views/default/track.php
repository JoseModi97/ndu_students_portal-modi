<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\modules\refund_requests\models\User $user */
/** @var app\modules\refund_requests\models\RefundRequest|null $request */
/** @var app\modules\refund_requests\models\ApprovalProcess[] $approvals */
/** @var app\modules\refund_requests\models\ApprovalLevel[] $allLevels */
/** @var float $balance */

$this->title = 'Full Process Tracking';
$this->registerCssFile('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');
$this->registerCssFile('@web/css/refund-requests.css');

// Calculate current stage
$completedCount = count($approvals);
$totalStages = count($allLevels) + 2; // +1 for Eligibility, +1 for Application
$currentStageIndex = 0;

if (!$request) {
    if (strtoupper($user->clearance_status) === 'CLEARED') {
        $currentStageIndex = 1; // Application stage
    } else {
        $currentStageIndex = 0; // Eligibility stage
    }
} else {
    $currentStageIndex = 2 + $completedCount; // Approval stages
}

$progressPercent = ($currentStageIndex / $totalStages) * 100;
?>

<div class="cr-page">
    <div class="cr-container">
        
        <div class="cr-header">
            <span class="cr-header__badge">National Defence University of Kenya</span>
            <h1 class="cr-header__title">Process Overview</h1>
            <p class="cr-header__sub">Real-time tracking of the entire refund lifecycle</p>
        </div>

        <div class="cr-card" style="margin-bottom: 2.5rem;">
            <div class="cr-card__body">
                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <span style="font-size: 0.8rem; font-weight: 800; color: var(--cr-blue-600); text-transform: uppercase; letter-spacing: 0.05em;">Lifecycle Completion</span>
                        <span style="font-size: 1rem; font-weight: 800; color: var(--cr-blue-800);"><?= round($progressPercent) ?>%</span>
                    </div>
                    <div style="height: 12px; background: var(--cr-blue-50); border-radius: 999px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                        <div style="height: 100%; width: <?= $progressPercent ?>%; background: linear-gradient(90deg, var(--cr-blue-400), var(--cr-teal-400)); transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);"></div>
                    </div>
                </div>

                <div class="cr-steps" style="margin-bottom: 1rem;">
                    <div class="cr-step <?= $currentStageIndex > 0 ? 'cr-step--completed' : 'cr-step--active' ?>">
                        <div class="cr-step__icon">
                            <?= $currentStageIndex > 0 ? '<i class="fas fa-check"></i>' : '1' ?>
                        </div>
                        <div class="cr-step__label">Eligibility</div>
                        <div class="cr-step__line"></div>
                    </div>

                    <div class="cr-step <?= $currentStageIndex > 1 ? 'cr-step--completed' : ($currentStageIndex == 1 ? 'cr-step--active' : '') ?>">
                        <div class="cr-step__icon">
                            <?= $currentStageIndex > 1 ? '<i class="fas fa-check"></i>' : '2' ?>
                        </div>
                        <div class="cr-step__label">Application</div>
                        <div class="cr-step__line"></div>
                    </div>

                    <?php foreach ($allLevels as $idx => $level): 
                        $stageIdx = 2 + $idx;
                        $isComp = $currentStageIndex > $stageIdx;
                        $isAct = $currentStageIndex == $stageIdx;
                        $isRej = false;
                        foreach ($approvals as $app) {
                            if ($app->approver && $app->approver->approval_level_id == $level->approval_level_id && strtoupper($app->approval_status) === 'REJECTED') {
                                $isRej = true;
                                break;
                            }
                        }
                        
                        $cls = $isComp ? 'cr-step--completed' : ($isAct ? 'cr-step--active' : '');
                        if ($isRej) $cls = 'cr-step--rejected';
                    ?>
                        <div class="cr-step <?= $cls ?>">
                            <div class="cr-step__icon">
                                <?php if ($isRej): ?><i class="fas fa-times"></i>
                                <?php elseif ($isComp): ?><i class="fas fa-check"></i>
                                <?php else: ?><?= $idx + 3 ?><?php endif; ?>
                            </div>
                            <div class="cr-step__label"><?= Html::encode($level->description) ?></div>
                            <?php if ($idx < count($allLevels) - 1): ?>
                                <div class="cr-step__line"></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="cr-card">
                    <div class="cr-card__header">
                        <div class="cr-card__header-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                            </svg>
                        </div>
                        <h2 class="cr-card__title">Phase Detail: 
                            <?php 
                            if ($currentStageIndex == 0) echo "Eligibility Check";
                            elseif ($currentStageIndex == 1) echo "Form Submission";
                            else echo "Approval Workflow";
                            ?>
                        </h2>
                    </div>
                    <div class="cr-card__body">
                        <?php if ($currentStageIndex == 0): ?>
                            <div class="cr-notice cr-notice--warning">
                                <p class="cr-notice__title">Action Required</p>
                                <p>You have not yet met the eligibility criteria. Please ensure you are CLEARED by the university.</p>
                            </div>
                            <div style="margin-top: 1.5rem;">
                                <?= Html::a('View Eligibility Checklist', ['index'], ['class' => 'cr-btn cr-btn--secondary']) ?>
                            </div>
                        <?php elseif ($currentStageIndex == 1): ?>
                            <div class="cr-notice">
                                <p class="cr-notice__title">Next Step</p>
                                <p>You are eligible! Please fill out and submit the caution refund application form.</p>
                            </div>
                            <div style="margin-top: 1.5rem;">
                                <?= Html::a('Go to Application Form', ['apply'], ['class' => 'cr-btn cr-btn--primary']) ?>
                            </div>
                        <?php else: ?>
                            <div class="cr-timeline">
                                <?php foreach ($approvals as $approval): ?>
                                    <div class="cr-timeline-item">
                                        <div class="cr-timeline-dot" style="border-color: var(--cr-teal-400);"></div>
                                        <div style="display: flex; justify-content: space-between;">
                                            <h4 style="font-size: 0.9rem; font-weight: 700; margin: 0;"><?= Html::encode($approval->approver->approvalLevel->description ?? 'Unknown Level') ?></h4>
                                            <span class="cr-badge cr-badge--approved"><?= Html::encode($approval->approval_status) ?></span>
                                        </div>
                                        <p style="font-size: 0.75rem; color: var(--cr-slate-400); margin: 0.2rem 0;"><?= Yii::$app->formatter->asDatetime($approval->approval_date) ?></p>
                                        <?php if ($approval->remarks): ?>
                                            <div class="cr-timeline-comment"><?= Html::encode($approval->remarks) ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                                <div class="cr-timeline-item">
                                    <div class="cr-timeline-dot" style="border-color: var(--cr-blue-400); animation: pulse 2s infinite;"></div>
                                    <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--cr-blue-600);">Currently At: <?= Html::encode($allLevels[$completedCount]->description ?? 'Final Processing') ?></h4>
                                    <p style="font-size: 0.85rem; color: var(--cr-slate-400);">Awaiting review by the respective office.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="cr-card">
                    <div class="cr-card__header">
                        <div class="cr-card__header-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <h2 class="cr-card__title">Profile</h2>
                    </div>
                    <div class="cr-card__body">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <span style="font-size: 0.7rem; font-weight: 700; color: var(--cr-slate-400); text-transform: uppercase;">Request Status</span>
                            <span class="cr-badge <?= $request ? 'cr-badge--pending' : 'cr-badge--rejected' ?>" style="font-size: 0.65rem;">
                                <?= $request ? 'ACTIVE REQUEST' : 'NO ACTIVE REQUEST' ?>
                            </span>
                        </div>

                        <p style="font-size: 0.9rem; font-weight: 800; color: var(--cr-blue-800); margin-bottom: 0.2rem;"><?= Html::encode($user->surname . ' ' . $user->other_names) ?></p>
                        <p style="font-size: 0.8rem; color: var(--cr-slate-400); margin-bottom: 0;"><?= Html::encode($user->registration_number) ?></p>
                    </div>
                </div>

                <div class="alert alert-info" style="background: var(--cr-blue-50); border: none; border-radius: var(--cr-radius); padding: 1.25rem;">
                    <h5 style="font-size: 0.9rem; font-weight: 800; color: var(--cr-blue-800);"><i class="fas fa-lightbulb mr-2"></i> Information</h5>
                    <p style="font-size: 0.82rem; color: var(--cr-slate-700); line-height: 1.5; margin: 0;">This tracker represents the full administrative path of your refund request, including both student-facing and internal back-office steps.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
  0% { box-shadow: 0 0 0 0 rgba(91, 163, 222, 0.4); }
  70% { box-shadow: 0 0 0 10px rgba(91, 163, 222, 0); }
  100% { box-shadow: 0 0 0 0 rgba(91, 163, 222, 0); }
}
</style>
