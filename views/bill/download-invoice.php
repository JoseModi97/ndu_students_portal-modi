
<?php
/** @var string $name */
/** @var string $regNumber */
/** @var array $invoice */
/** @var array $details */
/** @var string $academicYear */
/** @var string $semesterLabel */
/** @var array $currentSessionDetails */
?>

<!-- Logo and Header -->
<table style="width:100%; margin-bottom:6px;">
    <tr>
        <td style="width:15%; text-align:center; vertical-align:middle;">
            <img src="<?= Yii::getAlias('@webroot') ?>/img/ndu-arms.png" style="width:70px; height:auto;" />
        </td>
        <td style="text-align:center; vertical-align:middle;">
            <div class="header-title">NATIONAL DEFENCE UNIVERSITY OF KENYA</div>
            <div class="sub-title">FEE INVOICE</div>
        </td>
        <td style="width:15%;">&nbsp;</td>
    </tr>
</table>

<br>

<table class="student-info" style="width:100%;">
    <tr>
        <td><strong>Student Name:</strong></td>
        <td><?= htmlspecialchars($name) ?></td>
        <td><strong>Reg. Number:</strong></td>
        <td><?= htmlspecialchars($regNumber) ?></td>
    </tr>
    <tr>
        <td><strong>Programme:</strong></td>
        <td><?= htmlspecialchars($currentSessionDetails['programme']) ?></td>
        <td><strong>Level:</strong></td>
        <td><?= htmlspecialchars($currentSessionDetails['level']) ?></td>
    </tr>
    <tr>
        <td><strong>Academic Year:</strong></td>
        <td><?= htmlspecialchars($academicYear) ?></td>
        <td><strong>Semester:</strong></td>
        <td><?= htmlspecialchars($semesterLabel) ?></td>
    </tr>
    <tr>
        <td><strong>Invoice ID:</strong></td>
        <td><?= htmlspecialchars($invoice['invoice_id']) ?></td>
        <td><strong>Invoice Date:</strong></td>
        <td><?= htmlspecialchars($invoice['invoice_date']) ?></td>
    </tr>
</table>

<br>

<table class="invoice-table">
    <thead>
    <tr>
        <th style="width:5%;" class="text-center">#</th>
        <th style="width:75%;">Description</th>
        <th style="width:20%;" class="text-right">Amount (Ksh)</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $total = 0;
    $count = 1;
    foreach ($details as $detail):
        $total += $detail['amount'];
        ?>
        <tr>
            <td class="text-center"><?= $count++ ?></td>
            <td><?= htmlspecialchars($detail['invoice_detail_desc']) ?></td>
            <td class="text-right"><?= number_format($detail['amount'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
    <tr class="totals-row">
        <td colspan="2" class="text-right">TOTAL</td>
        <td class="text-right"><?= number_format($total, 2) ?></td>
    </tr>
    </tbody>
</table>

<br>

<p style="font-size:9px; color:#777;">
    * This is a system generated invoice.
</p>