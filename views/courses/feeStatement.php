<?php
/** @var string $name */
/** @var string $regNumber */
/** @var array $currentSessionDetails */
/** @var array $ledgerRows */
/** @var array $invoiceDetails */
/** @var float $totalDr */
/** @var float $totalCr */
/** @var float $netBalance */

// Group ledger rows by academic year
$groupedByYear = [];
foreach ($ledgerRows as $row) {
    $year = $row['academic_year'] ?: 'UNKNOWN';
    $groupedByYear[$year][] = $row;
}

// Balance with sign only — no label, for row-level display
function formatBalanceSimple(float $balance): string {
    if ($balance < 0) {
        return '<span style="color:#006600;">' . number_format($balance, 2) . '</span>';
    } elseif ($balance > 0) {
        return '<span style="color:#cc0000;">' . number_format($balance, 2) . '</span>';
    } else {
        return number_format($balance, 2);
    }
}

// Balance with sign and label — for grand totals and closing balances only
function formatBalanceWithLabel(float $balance): string {
    if ($balance < 0) {
        return '<span style="color:#006600;">' . number_format($balance, 2) . ' (Overpayment)</span>';
    } elseif ($balance > 0) {
        return '<span style="color:#cc0000;">' . number_format($balance, 2) . ' (Balance Due)</span>';
    } else {
        return number_format($balance, 2);
    }
}

// Balance with sign and label — for grand totals row on dark background
function formatBalanceWithLabelWhite(float $balance): string {
    if ($balance < 0) {
        return '<span style="color:#90EE90;">' . number_format($balance, 2) . ' (Overpayment)</span>';
    } elseif ($balance > 0) {
        return '<span style="color:#FFB6B6;">' . number_format($balance, 2) . ' (Balance Due)</span>';
    } else {
        return '<span style="color:#ffffff;">' . number_format($balance, 2) . '</span>';
    }
}
?>

<div class="header-title">NATIONAL DEFENCE UNIVERSITY OF KENYA</div>
<div class="sub-title">STATEMENT OF FEES ACCOUNT</div>

<br>

<table style="width:100%; font-size:11px;">
    <tr>
        <td><strong>Name:</strong> <?= htmlspecialchars($name) ?></td>
        <td><strong>Reg. Number:</strong> <?= htmlspecialchars($regNumber) ?></td>
        <td><strong>Date:</strong> <?= date('d-M-Y') ?></td>
    </tr>
    <tr>
        <td><strong>Programme:</strong> <?= htmlspecialchars($currentSessionDetails['programme']) ?></td>
        <td><strong>Level:</strong> <?= htmlspecialchars($currentSessionDetails['level']) ?></td>
        <td><strong>Semester:</strong> <?= htmlspecialchars($currentSessionDetails['semester']) ?></td>
    </tr>
</table>

<br>

<table class="ledger-table">
    <thead>
    <tr>
        <th style="width:20%;">Trans ID</th>
        <th style="width:10%;">Date</th>
        <th style="width:30%;">Description</th>
        <th class="text-right" style="width:13%;">DR (Ksh)</th>
        <th class="text-right" style="width:13%;">CR (Ksh)</th>
        <th class="text-right" style="width:14%;">Balance (Ksh)</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $runningBalance = 0;
    $grandTotalDr = 0;
    $grandTotalCr = 0;

    foreach ($groupedByYear as $academicYear => $rows):
        $yearDr = 0;
        $yearCr = 0;
        $openingBalance = $runningBalance;
        ?>

        <!-- Academic Year Header -->
        <tr>
            <td colspan="6" style="background-color:#003366; color:#ffffff; font-weight:bold; padding:4px 6px; font-size:10px;">
                Academic Year: <?= htmlspecialchars($academicYear) ?>
            </td>
        </tr>

        <!-- Opening Balance (from second year onwards) -->
        <?php if ($openingBalance != 0): ?>
        <tr>
            <td colspan="3" style="text-align:right; font-weight:bold; padding:3px 4px; font-size:10px;">
                Opening Balance
            </td>
            <td class="amount" style="font-size:10px;">&mdash;</td>
            <td class="amount" style="font-size:10px;">&mdash;</td>
            <td class="amount" style="font-weight:bold; font-size:10px;">
                <?= formatBalanceSimple($openingBalance) ?>
            </td>
        </tr>
    <?php endif; ?>

        <?php foreach ($rows as $row):
        $isCancelled = strtoupper($row['receipt_status'] ?? '') === 'CANCELLED';

        // Only add to running totals if not cancelled
        if (!$isCancelled) {
            if ($row['dr'] !== null) {
                $runningBalance += $row['dr'];
                $yearDr += $row['dr'];
            }
            if ($row['cr'] !== null) {
                $runningBalance -= $row['cr'];
                $yearCr += $row['cr'];
            }
        }
        ?>

        <!-- Main transaction row -->
        <tr class="<?= $row['type'] === 'CR' ? 'cr-row' : 'dr-row' ?>"
            style="<?= $isCancelled ? 'opacity:0.5; text-decoration:line-through;' : '' ?>">
            <td style="font-size:10px; padding:3px 4px;">
                <?= htmlspecialchars($row['trans_id_display']) ?>
            </td>
            <td class="text-center" style="font-size:10px; padding:3px 4px;">
                <?= htmlspecialchars($row['date']) ?>
            </td>
            <td style="font-size:10px; padding:3px 4px;">
                <?= htmlspecialchars($row['description']) ?>
                <?php if ($isCancelled): ?>
                    <span style="font-size:8px; color:#cc0000; font-weight:bold;"> [CANCELLED]</span>
                <?php endif; ?>
            </td>
            <td class="amount" style="font-size:10px; padding:3px 4px;">
                <?php if ($row['dr'] !== null && empty($row['details'])): ?>
                    <span class="balance-debit"><?= number_format($row['dr'], 2) ?></span>
                <?php elseif (!empty($row['details'])): ?>
                    &nbsp;
                <?php else: ?>
                    &mdash;
                <?php endif; ?>
            </td>
            <td class="amount" style="font-size:10px; padding:3px 4px;">
                <?php if ($row['cr'] !== null): ?>
                    <span class="balance-credit"><?= number_format($row['cr'], 2) ?></span>
                <?php else: ?>
                    &mdash;
                <?php endif; ?>
            </td>
            <td class="amount" style="font-size:10px; padding:3px 4px;">
                <?= formatBalanceSimple($runningBalance) ?>
            </td>
        </tr>

        <!-- Invoice detail rows -->
        <?php if (!empty($row['details']) && !$isCancelled):
        $detailBalance = $runningBalance - $row['dr'];
        foreach ($row['details'] as $detail):
            $detailBalance += $detail['amount'];
            ?>
            <tr style="background-color:#fafafa;">
                <td colspan="2" style="padding:2px 4px;">&nbsp;</td>
                <td style="font-size:9px; color:#333; padding:2px 4px; padding-left:16px;">
                    &nbsp;&nbsp;&nbsp;<?= htmlspecialchars($detail['invoice_detail_desc']) ?>
                </td>
                <td class="amount" style="font-size:9px; color:#333; padding:2px 4px;">
                    <?= number_format($detail['amount'], 2) ?>
                </td>
                <td class="amount" style="font-size:9px; color:#333; padding:2px 4px;">
                    0.00
                </td>
                <td class="amount" style="font-size:9px; color:#333; padding:2px 4px;">
                    <?= formatBalanceSimple($detailBalance) ?>
                </td>
            </tr>
        <?php endforeach; ?>

        <!-- Invoice grand total row -->
        <tr style="background-color:#f0f0f0;">
            <td colspan="3" style="text-align:right; font-size:9px; font-weight:bold; padding:2px 4px;">
                <?= htmlspecialchars($row['description']) ?> (Grand Total)
            </td>
            <td class="amount" style="font-size:9px; font-weight:bold; padding:2px 4px;">
                <?= number_format($row['dr'], 2) ?>
            </td>
            <td class="amount" style="font-size:9px; padding:2px 4px;">&nbsp;</td>
            <td class="amount" style="font-size:9px; padding:2px 4px;">&nbsp;</td>
        </tr>
    <?php endif; ?>

    <?php endforeach; // end rows ?>

        <!-- Academic Year Totals -->
        <?php
        $grandTotalDr += $yearDr;
        $grandTotalCr += $yearCr;
        ?>
        <tr style="background-color:#eef2ff; font-weight:bold;">
            <td colspan="3" style="text-align:right; font-size:10px; padding:3px 4px;">
                Academic Year Totals:
            </td>
            <td class="amount" style="font-size:10px; padding:3px 4px;">
                <span class="balance-debit"><?= number_format($yearDr, 2) ?></span>
            </td>
            <td class="amount" style="font-size:10px; padding:3px 4px;">
                <span class="balance-credit"><?= number_format($yearCr, 2) ?></span>
            </td>
            <td class="amount" style="font-size:10px; padding:3px 4px;">
                <?= formatBalanceSimple($runningBalance) ?>
            </td>
        </tr>

        <!-- Closing Balance -->
        <tr>
            <td colspan="6" style="font-size:10px; font-weight:bold; padding:3px 4px;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Closing Balance: <?= formatBalanceWithLabel($runningBalance) ?>
            </td>
        </tr>

        <!-- Spacer between years -->
        <tr><td colspan="6" style="padding:4px;">&nbsp;</td></tr>

    <?php endforeach; // end academic years ?>

    <!-- Grand Totals -->
    <tr style="background-color:#003366; font-weight:bold;">
        <td colspan="3" style="text-align:right; font-size:10px; padding:4px; color:#ffffff;">
            GRAND TOTALS:
        </td>
        <td class="amount" style="font-size:10px; padding:4px; color:#ffffff;">
            <?= number_format($grandTotalDr, 2) ?>
        </td>
        <td class="amount" style="font-size:10px; padding:4px; color:#ffffff;">
            <?= number_format($grandTotalCr, 2) ?>
        </td>
        <td class="amount" style="font-size:10px; padding:4px; color:#ffffff;">
            <?= number_format($runningBalance, 2) ?>
            <?php if ($runningBalance < 0): ?> (Overpayment)
            <?php elseif ($runningBalance > 0): ?> (Balance Due)
            <?php endif; ?>
        </td>
    </tr>

    <!-- Final Closing Balance -->
    <tr>
        <td colspan="6" style="font-size:11px; font-weight:bold; padding:6px 4px;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Closing Balance: <?= formatBalanceWithLabel($runningBalance) ?>
        </td>
    </tr>

    </tbody>
</table>

<br>

<!-- Signature block -->
<table style="width:100%; font-size:10px; margin-top:20px;">
    <tr>
        <td style="padding:4px;">Checked By: ........................................................</td>
    </tr>
    <tr>
        <td style="padding:4px; padding-left:40px;">Sign</td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td style="padding:4px;">Approved By: ........................................................</td>
    </tr>
    <tr>
        <td style="padding:4px; padding-left:40px;">BURSAR</td>
    </tr>
</table>

<br>

<p style="font-size:9px; color:#777;">
    * DR = Debit (charges/fees due) &nbsp;|&nbsp; CR = Credit (payments received)
    &nbsp;|&nbsp; Balance shown is cumulative. Negative balance indicates overpayment.
</p>

<p style="font-size:9px;">
    <strong>NB: Valid with an Official Stamp<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Any fee balance disqualifies one from sitting for examination</strong>
</p>