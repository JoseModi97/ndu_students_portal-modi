# Refund Request Process Automation

This folder contains PHP CLI scripts for testing the current `refund_requests` module lifecycle. The scripts now target the active FSS refund tables used by the module:

- Portal request table: `smisportal.fss_refund_requests`
- SMIS sync table: `smis.fss_refund_requests`
- Portal approval table: `smisportal.fss_refund_approval_process`
- Disapproved request tables: `smisportal.fss_refund_requests_disapproved` and `smis.fss_refund_requests_disapproved`
- Refund batch tables: `fss_refund_batches` and `fss_cancelled_vouchers`
- Posting fee transaction table: `smis.fss_fee_transactions`

## Target Student

All scripts target:

- Registration Number: `NR605/0001/2022`

## Automation Scripts

### 1. `step0_cleanup.php`

Deletes existing FSS refund requests, disapproved-request rows, approval-process rows, refund batch rows, orphan refund batch rows, and SMIS posting-generated fee transactions for the target student.

Usage:

```powershell
php modules\refund_requests\automation\step0_cleanup.php
```

### 2. `step1_eligibility.php`

Prepares the student for the module eligibility checks by setting clearance to `CLEARED` and academic status to `GRADUATED` where those status records exist. It also prints the SMIS fee balance used by the live controller.

Usage:

```powershell
php modules\refund_requests\automation\step1_eligibility.php
```

### 3. `step2_apply.php`

Creates a valid pending `CAUTION` refund request in `smisportal.fss_refund_requests` and `smis.fss_refund_requests`. `voucher_no` is left `NULL`; it is assigned only during SMIS posting.

Default usage creates a Bank payment request with mandatory bank, branch, account number, `refund_status = NOT REFUNDED`, and `declaration_status = 1`:

```powershell
php modules\refund_requests\automation\step2_apply.php
```

To create an M-PESA payment request instead:

```powershell
php modules\refund_requests\automation\step2_apply.php mpesa
```

### 4. `step3_approve_level1.php`

Prompts for an approval decision for Level 1, then records that decision in both Portal and SMIS approval-process tables. If you choose `reject`, the script asks for a rejection comment, inserts matching records in both disapproved-request tables, and marks the request `NOT APPROVED` on both request tables.

Usage:

```powershell
php modules\refund_requests\automation\step3_approve_level1.php
```

### 5. `step4_approve_level2.php`

Prompts for an approval decision for Level 2, then records only that Level 2 decision in both Portal and SMIS. It requires the refund request to exist and Level 1 approval to already exist in both databases. If you choose `reject`, the script asks for a rejection comment, inserts matching records in both disapproved-request tables, and marks the request `NOT APPROVED` on both request tables.

Usage:

```powershell
php modules\refund_requests\automation\step4_approve_level2.php
```

### 6. `step4_finalize.php`

Prompts for an approval decision for the final approval level, then records only that final-level decision in both Portal and SMIS. It requires all previous approval levels to already be approved in both databases. If you choose `approve`, the request remains `PENDING` on the parent request row, stores `amount_approved`, and becomes ready for posting through the Level 3 approval-process row; if you choose `reject`, the script asks for a rejection comment, inserts matching records in both disapproved-request tables, and marks the request `NOT APPROVED` on both request tables.

Usage:

```powershell
php modules\refund_requests\automation\step4_finalize.php
```

### 7. `step5_post_caution_refund.php`

Posts the latest fully approved, unposted `CAUTION` request for the target student on SMIS only. This creates one `smis.fss_refund_batches` row with `posted_by` and `posted_at` set, `status = PENDING`, and an empty `date_paid`; creates the caution DR/CR fee transactions in `smis.fss_fee_transactions`; updates prior `smis.fss_cancelled_vouchers` rows for the same `request_id` to the final voucher number when that column exists; and updates `smis.fss_refund_requests` with `approval_status = APPROVED`, `refund_status = REFUNDED`, the voucher number, and the approved amount.

Usage:

```powershell
php modules\refund_requests\automation\step5_post_caution_refund.php
```

### 8. `step6_save_paid_refund_voucher.php`

Mirrors the SMIS `Update Paid Refund Vouchers` save action for the latest posted `CAUTION` refund batch for the target student. It only works on posted batches where `posted_at IS NOT NULL` and `date_paid IS NULL`.

This step updates `smis.fss_refund_batches`:

- `status = PAID`
- `date_paid = current timestamp`

Linked `smis.fss_refund_requests` rows are already marked `REFUNDED` during posting and are not changed by this step.

Usage:

```powershell
php modules\refund_requests\automation\step6_save_paid_refund_voucher.php
```

## Utility Scripts

### `debug_record.php`

Prints the latest FSS refund request for the target student, including refund type, bank/branch labels, refund batch rows and payment status, cancelled voucher rows, SMIS posting fee transactions, and any matching disapproved-request rows when available.

### `verify_accuracy.php`

Prints the SMIS fee balance, caution posting fee transactions, SMIS academic status, and portal clearance status used by the eligibility flow.

### `check_status.php`

Prints a quick clearance-status summary and latest refund request approval/refund/voucher status for the target student, including batch `status` and `date_paid` when a voucher exists.

### `check_bank_reference_data.php`

Prints SMIS and Portal row counts and maximum primary-key values for `fss_banks` and `fss_bank_branches`.

### `sync_student_status_cli.php`

Interactive CLI tool to synchronize student academic statuses across SMIS and Portal.

## Tracking the Changes

Open the refund request module tracker in the application:

`/index.php?r=refund-requests/default/track`
