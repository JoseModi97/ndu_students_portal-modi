<?php

use yii\bootstrap5\Html;

/**
 * @var yii\web\View $this
 * @var array $report
 */

$this->title = $title;

$rowValue = static function (array $row, string $key): string {
    $value = $row[$key] ?? '';
    if ($value === null || $value === '') {
        return 'Missing';
    }
    return (string) $value;
};

$statusBadge = static function (bool $ok, string $okText = 'OK', string $missingText = 'Missing'): string {
    return Html::tag('span', $ok ? $okText : $missingText, [
        'class' => $ok ? 'badge bg-success' : 'badge bg-danger',
    ]);
};

$workflowStatus = static function (array $row): string {
    $posted = strtoupper((string) ($row['post_status'] ?? '')) === 'POSTED';
    $hasPayment = !empty($row['fee_paymt_id']);
    $hasTransaction = !empty($row['academic_progress_id']) && ($row['trans_type'] ?? '') === 'CR';

    if ($posted && $hasPayment && $hasTransaction) {
        return Html::tag('span', 'Complete', ['class' => 'badge bg-success']);
    }
    if ($posted) {
        return Html::tag('span', 'Posted, incomplete trace', ['class' => 'badge bg-warning text-dark']);
    }
    return Html::tag('span', 'Waiting for payment', ['class' => 'badge bg-secondary']);
};

$renderKvTable = static function (array $row, array $labels) use ($rowValue): string {
    $body = '';
    foreach ($labels as $key => $label) {
        $body .= '<tr><th style="width: 38%;">' . Html::encode($label) . '</th><td>' . Html::encode($rowValue($row, $key)) . '</td></tr>';
    }
    return '<div class="table-responsive"><table class="table table-sm table-bordered mb-0"><tbody>' . $body . '</tbody></table></div>';
};

$portal = $report['portal'];
$smis = $report['smis'];
$counts = $smis['counts'];
?>

<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?= Html::a('Home', ['/site/index']) ?></li>
                <li class="breadcrumb-item"><?= Html::a('eCitizen Payment', ['index']) ?></li>
                <li class="breadcrumb-item active" aria-current="page">Workflow report</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="m-0">eCitizen workflow report</h1>
                <p class="text-muted mb-0">
                    Admission ref: <?= Html::encode((string) $report['admRefNo']) ?> |
                    Registration: <?= Html::encode($report['registrationNumber'] ?: 'Missing') ?>
                </p>
            </div>
            <div>
                <?= Html::a('Make payment', ['index'], ['class' => 'btn btn-outline-primary']) ?>
                <?= Html::a('Previous invoices', ['invoices'], ['class' => 'btn btn-outline-secondary']) ?>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="text-muted">Banking slips</div>
                        <h3><?= Html::encode((string) $counts['bankingSlips']) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="text-muted">Posted slips</div>
                        <h3><?= Html::encode((string) $counts['postedSlips']) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="text-muted">Fee payments</div>
                        <h3><?= Html::encode((string) $counts['feePayments']) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="text-muted">Fee transactions</div>
                        <h3><?= Html::encode((string) $counts['feeTransactions']) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Workflow health</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Step</th>
                        <th>Database</th>
                        <th>Status</th>
                        <th>What it confirms</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Logged-in student</td>
                        <td>Portal DB</td>
                        <td><?= $statusBadge(!empty($portal['admittedStudent'])) ?></td>
                        <td>Portal account exists for the current user.</td>
                    </tr>
                    <tr>
                        <td>Registration resolution</td>
                        <td>Portal DB</td>
                        <td><?= $statusBadge(!empty($portal['programme']['registration_number'] ?? null)) ?></td>
                        <td>The portal user resolves to a registration number.</td>
                    </tr>
                    <tr>
                        <td>Student in SMIS</td>
                        <td>Main SMIS DB</td>
                        <td><?= $statusBadge(!empty($smis['student'])) ?></td>
                        <td>The registration number exists in the main student table.</td>
                    </tr>
                    <tr>
                        <td>Academic progress</td>
                        <td>Main SMIS DB</td>
                        <td><?= $statusBadge(!empty($smis['academicProgress'])) ?></td>
                        <td>The student can be attached to fee transactions.</td>
                    </tr>
                    <tr>
                        <td>eCitizen payment mode</td>
                        <td>Main SMIS DB</td>
                        <td><?= $statusBadge(!empty($smis['paymentMode'])) ?></td>
                        <td>Payment mode 12 exists for eCitizen.</td>
                    </tr>
                    <tr>
                        <td>Settlement accounts</td>
                        <td>Main SMIS DB</td>
                        <td><?= $statusBadge(!empty($smis['bankAccounts'])) ?></td>
                        <td>There is a bank account available for the banking slip.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Portal DB data</h3>
                    </div>
                    <div class="card-body">
                        <h5>Admitted student</h5>
                        <?= $renderKvTable($portal['admittedStudent'], [
                            'adm_refno' => 'Admission ref',
                            'surname' => 'Surname',
                            'other_names' => 'Other names',
                            'primary_email' => 'Email',
                            'primary_phone_no' => 'Phone',
                            'admission_status' => 'Admission status',
                        ]) ?>
                        <h5 class="mt-3">Portal programme</h5>
                        <?= $renderKvTable($portal['programme'], [
                            'student_prog_curriculum_id' => 'Programme curriculum ID',
                            'student_id' => 'Student ID',
                            'registration_number' => 'Registration number',
                            'adm_refno' => 'Admission ref',
                            'status_id' => 'Status ID',
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Main SMIS DB data</h3>
                    </div>
                    <div class="card-body">
                        <h5>SMIS student</h5>
                        <?= $renderKvTable($smis['student'], [
                            'student_id' => 'Student ID',
                            'student_number' => 'Student number',
                            'surname' => 'Surname',
                            'other_names' => 'Other names',
                            'primary_email' => 'Email',
                            'primary_phone_no' => 'Phone',
                        ]) ?>
                        <h5 class="mt-3">Academic progress</h5>
                        <?= $renderKvTable($smis['academicProgress'], [
                            'academic_progress_id' => 'Academic progress ID',
                            'acad_session_id' => 'Academic session',
                            'academic_level_id' => 'Academic level',
                            'student_prog_curriculum_id' => 'Programme curriculum ID',
                            'current_status' => 'Current status',
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">eCitizen payment trace</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Amount</th>
                        <th>Slip</th>
                        <th>Fee payment</th>
                        <th>Fee transaction</th>
                        <th>Workflow status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($smis['workflowRows'])): ?>
                        <tr>
                            <td colspan="6" class="text-center">No eCitizen workflow rows found for this student.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($smis['workflowRows'] as $row): ?>
                        <tr>
                            <td>
                                <strong><?= Html::encode($row['source_reference'] ?: $row['trans_reference']) ?></strong><br>
                                <span class="text-muted">Trans ID: <?= Html::encode((string) $row['trans_id']) ?></span>
                            </td>
                            <td><?= Yii::$app->formatter->asCurrency($row['deposit_amount']) ?></td>
                            <td>
                                <?= Html::encode($row['post_status'] ?: 'Pending') ?><br>
                                <span class="text-muted"><?= Html::encode((string) $row['deposit_date']) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($row['fee_paymt_id'])): ?>
                                    <?= Html::tag('span', 'Created', ['class' => 'badge bg-success']) ?><br>
                                    <span class="text-muted">ID: <?= Html::encode((string) $row['fee_paymt_id']) ?></span>
                                <?php else: ?>
                                    <?= Html::tag('span', 'Missing', ['class' => 'badge bg-secondary']) ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($row['academic_progress_id']) && $row['trans_type'] === 'CR'): ?>
                                    <?= Html::tag('span', 'CR created', ['class' => 'badge bg-success']) ?><br>
                                    <span class="text-muted"><?= Html::encode((string) $row['fee_transaction_amount']) ?></span>
                                <?php else: ?>
                                    <?= Html::tag('span', 'Missing', ['class' => 'badge bg-secondary']) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= $workflowStatus($row) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Raw SQL confirmation</h3>
            </div>
            <div class="card-body">
                <h4>Portal DB SQL</h4>
                <?php foreach ($report['sql']['portalDb'] as $label => $sql): ?>
                    <details class="mb-2">
                        <summary><?= Html::encode($label) ?></summary>
                        <pre class="bg-light border p-3 mt-2"><code><?= Html::encode($sql) ?></code></pre>
                    </details>
                <?php endforeach; ?>

                <h4 class="mt-4">Main SMIS DB SQL</h4>
                <?php foreach ($report['sql']['smisDb'] as $label => $sql): ?>
                    <details class="mb-2">
                        <summary><?= Html::encode($label) ?></summary>
                        <pre class="bg-light border p-3 mt-2"><code><?= Html::encode($sql) ?></code></pre>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
