# Refund Request Process Automation

This folder contains PHP CLI scripts for testing the current `refund_requests` module lifecycle. The scripts now target the active FSS refund tables used by the module:

- Portal request table: `smisportal.fss_refund_requests`
- SMIS sync table: `smis.fss_refund_requests`
- Portal approval table: `smisportal.fss_refund_approval_process`

## Target Student

All scripts target:

- Registration Number: `NR605/0001/2022`

## Automation Scripts

### 1. `step0_cleanup.php`

Deletes existing FSS refund requests and approval-process rows for the target student from both Portal and SMIS request tables.

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

Creates a valid pending refund request in `smisportal.fss_refund_requests` with `sync_status = 0`. Run the refund sync command to copy it to `smis.fss_refund_requests`.

Default usage creates a Bank payment request with mandatory bank, branch, account number, and `declaration_status = 1`:

```powershell
php modules\refund_requests\automation\step2_apply.php
```

To create an M-PESA payment request instead:

```powershell
php modules\refund_requests\automation\step2_apply.php mpesa
```

### 4. `step3_approve_level1.php`

Prompts for an approval decision for Level 1, then records that decision in both Portal and SMIS approval-process tables. If you choose `reject`, the script asks for a rejection comment and marks the request `REJECTED` on both request tables.

Usage:

```powershell
php modules\refund_requests\automation\step3_approve_level1.php
```

### 5. `step4_approve_level2.php`

Prompts for an approval decision for Level 2, then records only that Level 2 decision in both Portal and SMIS. It requires the refund request to exist and Level 1 approval to already exist in both databases. If you choose `reject`, the script asks for a rejection comment and marks the request `REJECTED` on both request tables.

Usage:

```powershell
php modules\refund_requests\automation\step4_approve_level2.php
```

### 6. `step4_finalize.php`

Prompts for an approval decision for the final approval level, then records only that final-level decision in both Portal and SMIS. It requires all previous approval levels to already be approved in both databases. If you choose `approve`, the request is marked `APPROVED`; if you choose `reject`, the script asks for a rejection comment and marks the request `REJECTED` on both request tables.

Usage:

```powershell
php modules\refund_requests\automation\step4_finalize.php
```

## Utility Scripts

### `debug_record.php`

Prints the latest FSS refund request for the target student, including refund type and bank/branch labels when available.

### `verify_accuracy.php`

Prints the SMIS fee balance, SMIS academic status, and portal clearance status used by the eligibility flow.

### `check_status.php`

Prints a quick clearance-status summary for the target student.

### `check_bank_reference_data.php`

Prints SMIS and Portal row counts and maximum primary-key values for `fss_banks` and `fss_bank_branches`.

### `sync_student_status_cli.php`

Interactive CLI tool to synchronize student academic statuses across SMIS and Portal.

## Tracking the Changes

Open the refund request module tracker in the application:

`/index.php?r=refund-requests/default/track`
