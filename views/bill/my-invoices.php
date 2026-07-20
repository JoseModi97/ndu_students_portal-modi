<?php
/** @var string $name */
/** @var string $regNumber */
/** @var array $invoices */
/** @var array $currentSessionDetails */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<!-- Content Header -->
<div class="content-header">
    <div class="page-header">
        <h1>My Invoices</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Fee Invoices</h3>
                    </div>
                    <div class="card-body">

                        <div class="row mb-3 gy-1 student-summary-row">
                            <div class="col-sm-6"><strong>Name:</strong> <?= htmlspecialchars($name) ?></div>
                            <div class="col-sm-6"><strong>Reg. Number:</strong> <?= htmlspecialchars($regNumber) ?></div>
                            <div class="col-sm-6"><strong>Programme:</strong> <?= htmlspecialchars($currentSessionDetails['programme']) ?></div>
                            <div class="col-sm-6"><strong>Level:</strong> <?= htmlspecialchars($currentSessionDetails['level']) ?></div>
                        </div>

                        <?php if (empty($invoices)): ?>
                            <div class="alert alert-info">No invoices found.</div>
                        <?php else: ?>
                            <table class="table table-bordered table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice ID</th>
                                        <th>Academic Year</th>
                                        <th class="text-center">Semester</th>
                                        <th>Date</th>
                                        <th class="text-right">Amount (Ksh)</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($invoices as $index => $invoice): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($invoice['invoice_id']) ?></td>
                                            <td><?= htmlspecialchars($invoice['academic_year']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($invoice['semester']) ?></td>
                                            <td><?= htmlspecialchars($invoice['invoice_date']) ?></td>
                                            <td class="text-right"><?= number_format($invoice['amount'], 2) ?></td>
                                            <td class="text-center">
                                                <?= Html::a(
                                                    '<i class="fas fa-download"></i> Download',
                                                    Url::to(['/bill/download-invoice', 'invoice_id' => $invoice['invoice_id']]),
                                                    [
                                                        'class' => 'btn btn-info btn-sm',
                                                        'target' => '_blank',
                                                        'data-pjax' => '0'
                                                    ]
                                                ) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>