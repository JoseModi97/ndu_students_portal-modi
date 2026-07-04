# eCitizen Payment Confirmation and Reconciliation

## Purpose

This document records the changes made to the eCitizen student-payment workflow and explains
how payment confirmation, posting, and settled-payment reconciliation now work.

The work had two objectives:

1. Hide the student-facing **Complete payment** button from the invoice grid.
2. Add a separate background process that periodically confirms that locally settled payments
   are still reported as settled by eCitizen.

The existing automatic payment callback and SMIS posting workflow were not replaced.

## Payment confirmation flow

A normal successful payment follows this path:

1. The student opens **Pay this invoice** and completes payment in the eCitizen interface.
2. eCitizen sends a signed payment notification to the application's notification endpoint.
3. The application validates the notification signature, reference, amount, date, and paid status.
4. The portal fee statement is credited and the eCitizen row is marked `Credited` with
   `sync_status = 0`.
5. The existing `ecitizen-sync/sync` cron creates or completes the corresponding SMIS banking
   slip, fee transaction, fee payment, and receipt.
6. The eCitizen row becomes `Settled` with `sync_status = 1`.
7. The invoice grid no longer displays payment action buttons for that invoice.

Returning the browser from eCitizen to the portal does not itself confirm payment. The signed
server-to-server notification performs the normal automatic confirmation.

## Hidden Complete payment button

The **Complete payment** button in:

```text
modules/ecitizen/views/payment/invoices.php
```

was commented out. This button was a manual fallback that called the payment-status API when
the automatic notification had not completed the payment workflow.

The following remain available and unchanged:

- The **Pay this invoice** button for invoices requiring action.
- The eCitizen checkout interface.
- Signed eCitizen payment notifications.
- Automatic portal crediting.
- The existing `ecitizen-sync/sync` SMIS posting command.
- The server-side `complete-payment` action, although it is no longer exposed by the invoice grid.

Once the signed notification credits or settles an invoice, the grid's action buttons disappear
automatically.

## Settled-payment reconciliation

A separate console action was added:

```bash
php yii ecitizen-sync/reconcile-settled 100 60
```

Arguments:

- `100`: maximum number of settled rows checked in one run.
- `60`: minimum number of minutes before the same row is eligible to be checked again.

The command:

1. Selects locally settled rows that are due for another check.
2. Queries the configured eCitizen payment-status endpoint.
3. Verifies the returned invoice reference and amount.
4. Records the remote status, response, check time, and reconciliation result.
5. Leaves the existing payment, receipt, fee credit, and settled status unchanged.

Rows are processed from the least recently checked to the most recently checked so all settled
payments are eventually revisited without querying every row simultaneously.

## Installed cron

The following cron entry was installed:

```cron
*/15 * * * * cd /var/www/html/smisportalndudev && /usr/bin/php yii ecitizen-sync/reconcile-settled 100 60 >> runtime/logs/ecitizen-reconciliation.log 2>&1
```

It runs every 15 minutes. Because the minimum recheck interval is 60 minutes, an individual
settled row is normally checked no more than once per hour.

This job is separate from the existing posting cron:

```cron
* * * * * cd /var/www/html/smisportalndudev && /usr/bin/php yii ecitizen-sync/sync >> runtime/logs/ecitizen-sync.log 2>&1
```

A reconciliation failure therefore does not stop or alter normal payment posting.

## Reconciliation outcomes

### `confirmed`

Used when eCitizen returns an accepted paid/settled status and both the reference and amount
match the local payment.

Accepted remote statuses are:

- `PAID`
- `SUCCESS`
- `COMPLETED`
- `SETTLED`

### `review_required`

Used only when eCitizen explicitly returns a reversal/refund status and the reference and amount
match the local payment.

Recognized reversal statuses are:

- `REVERSED`
- `REFUNDED`
- `REVERSE`
- `REFUND`

Once set, `review_required` is sticky and is not cleared automatically by a later status check.
It must be investigated and cleared through an approved manual or future automated workflow.

### `unknown`

Used when the response cannot safely prove either settlement or reversal. Examples include:

- `Pending`
- An undocumented status
- Missing reference
- Incorrect reference
- Missing amount
- Incorrect amount

Unknown results do not modify financial records.

### Check failure

Timeouts, connection errors, malformed responses, and other exceptions are recorded in
`status_check_error`. They do not change payment, settlement, receipt, or fee-ledger records.

## Database changes

Migration:

```text
migrations/m260704_000001_add_ecitizen_reconciliation_columns.php
```

The migration adds these columns to `smisportal.ecitizen`:

- `remote_status`
- `reconciliation_status`
- `last_status_checked_at`
- `status_check_error`
- `last_status_response`
- `reversal_detected_at`

It also adds indexes for due reconciliation checks and `review_required` rows.

The migration is additive and does not alter the existing payment or sync columns.

## Financial reversal safety boundary

Automatic fee-credit reversal is intentionally **not enabled**.

Inspection of existing SMIS reversal records showed that marking receipts `CANCELLED` and banking
slips `REVERSED` does not consistently create a balancing debit in the fee ledger. Automatically
applying that incomplete convention could leave an incorrect student balance.

For now, an explicitly confirmed eCitizen reversal is recorded as `review_required` without:

- Deleting the original credit.
- Creating a debit.
- Cancelling a receipt.
- Changing the local `Settled` status.
- Modifying SMIS banking-slip or fee-payment records.

Before automatic financial reversal is introduced, the production eCitizen reversal payload and
the institution-approved accounting procedure must be validated. The final process should be
idempotent and preserve the original credit for audit purposes.

## Verification performed

The implementation was checked with:

- PHP syntax validation for the command, service, and migration.
- Eight unit tests covering confirmed settlement, explicit reversal, pending status, unknown
  status, wrong or missing reference, and wrong or missing amount.
- An additive migration applied successfully to the development database.
- A live one-row reconciliation smoke test that returned `SETTLED` and recorded `confirmed`.
- Verification that the original `ecitizen-sync/sync` cron remains installed unchanged.

## Operational checks

View the installed jobs:

```bash
crontab -l
```

Run reconciliation manually:

```bash
cd /var/www/html/smisportalndudev
/usr/bin/php yii ecitizen-sync/reconcile-settled 10 60
```

Inspect the log:

```bash
tail -n 100 /var/www/html/smisportalndudev/runtime/logs/ecitizen-reconciliation.log
```

Find payments requiring investigation:

```sql
SELECT payment_id,
       "billRefNumber",
       registration_number,
       "amountExpected",
       remote_status,
       reconciliation_status,
       last_status_checked_at,
       reversal_detected_at,
       status_check_error
FROM smisportal.ecitizen
WHERE reconciliation_status = 'review_required'
ORDER BY reversal_detected_at DESC;
```

