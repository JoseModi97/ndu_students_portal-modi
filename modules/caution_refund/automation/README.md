# Caution Refund Process Automation

This folder contains a suite of PHP scripts designed to automate the lifecycle of a Caution Refund application for testing and demonstration purposes. These scripts directly manipulate the database to simulate various stages of the process.

## Target Student
All scripts are currently configured to target the test student:
- **Registration Number**: `NR605/0001/2022`

## Automation Scripts

### 1. `step0_cleanup.php`
- **Purpose**: Provides a clean slate for testing.
- **Action**: Deletes all existing caution refund records for the student from both the Portal (`smisportal.sm_caution_refund`) and the SMIS (`smis.sm_caution_refund_official`) databases.
- **Usage**: `php modules\caution_refund\automation\step0_cleanup.php`

### 2. `step1_eligibility.php`
- **Purpose**: Prepares the student to meet the basic eligibility criteria.
- **Action**: Updates the student's status to `CLEARED` in the `smisportal.sm_admitted_student` table.
- **Note**: This script ensures the clearance check passes; however, if the student has a fee balance in `fss_fee_transactions`, they may still be ineligible.
- **Usage**: `php modules\caution_refund\automation\step1_eligibility.php`

### 3. `step2_apply.php`
- **Purpose**: Simulates the submission of the Caution Refund form.
- **Action**: Creates a `PENDING` request in the student portal and a corresponding official record in the SMIS database.
- **Usage**: `php modules\caution_refund\automation\step2_apply.php`

### 4. `step3_approve_level1.php`
- **Purpose**: Simulates the first administrative approval.
- **Action**: Records an `APPROVED` status for the first workflow level (typically the Dean of Students) in the `smis.sm_approval_process` table.
- **Usage**: `php modules\caution_refund\automation\step3_approve_level1.php`

### 5. `step4_finalize.php`
- **Purpose**: Completes the entire approval lifecycle.
- **Action**: Iteratively approves all remaining levels in the workflow and sets the final status of the refund request to `APPROVED` in both databases.
- **Usage**: `php modules\caution_refund\automation\step4_finalize.php`

## Utility Scripts

### `sync_student_status_cli.php`
- **Purpose**: An interactive CLI tool to manage and synchronize student academic statuses (e.g., ACTIVE, GRADUATED) across both databases.
- **Action**: Queries current status from SMIS and Portal, lists available statuses with an explicit **Exit** option, and performs a transactional update on both systems to ensure consistency.
- **Usage**: `php modules\caution_refund\automation\sync_student_status_cli.php`

### `check_status.php`
- **Purpose**: A quick diagnostic tool to verify the current clearance status of the test student.
- **Usage**: `php modules\caution_refund\automation\check_status.php`

---

## Tracking the Changes
You can observe the effects of these scripts in real-time by visiting the **Full Process Tracker** in the application:
`URL: /index.php?r=caution_refund/default/track`
