# Refund Request Synchronization Setup Guide

This document explains how to set up and manage the automated synchronization of refund requests from the **Portal** database (`smisportal`) to the **SMIS** database (`smis`).

## Architecture Overview

To improve security and performance, the application uses a **decoupled synchronization mechanism**:
1.  **Web Layer**: Students apply via the web interface. Data is saved only to `smisportal.fss_refund_requests`.
2.  **Staging**: A new column `sync_status` (0=Pending, 1=Synced, 2=Failed) tracks the state of each request.
3.  **Background Layer**: A crontab-driven console command periodically pushes pending data to the SMIS database.

---

## 1. Prerequisites

### Database Schema
The `smisportal.fss_refund_requests` table must have the following columns:
- `sync_status` (SMALLINT, default 0)
- `sync_error` (TEXT, nullable)
- `last_synced_at` (TIMESTAMP, nullable)

*These were added via migration `m260529_090308_add_sync_columns_to_fss_refund_requests`.*

### Console Controller
The synchronization logic is located in:
`commands/RefundSyncController.php`

---

## 2. Cron Configuration

The synchronization is designed to run every 5 minutes.

### The Actual Crontab Entry
Copy and paste this exact line into your crontab (`crontab -e`):

```cron
*/5 * * * * php /var/www/html/smisportalndudev/yii refund-sync/sync >> /var/www/html/smisportalndudev/runtime/logs/refund_sync.log 2>&1
```

### Environment Details
- **Project Root**: `/var/www/html/smisportalndudev/`
- **Yii Executable**: `/var/www/html/smisportalndudev/yii`

### Setup Instructions
1.  Log in to the server via SSH as the application user (e.g., `rufusy`).
2.  Open the crontab editor:
    ```bash
    crontab -e
    ```
3.  Add the following line to the file:
    ```cron
    */5 * * * * php /var/www/html/smisportalndudev/yii refund-sync/sync >> /var/www/html/smisportalndudev/runtime/logs/refund_sync.log 2>&1
    ```
4.  Save and exit the editor.

### Understanding the Cron Expression
The part `*/5 * * * *` tells the server when to run the task:
- `*/5`: Every 5 minutes.
- `*`: Every hour of the day.
- `*`: Every day of the month.
- `*`: Every month of the year.
- `*`: Every day of the week.

### Verifying the Installation
To confirm your crontab is active and saved correctly, run:
```bash
crontab -l
```
This will list all active cron jobs for your user. If you see the command you added, the system is configured correctly.

---

## 3. Monitoring and Maintenance

### Checking Logs
You can monitor the synchronization process in real-time by tailing the log file:
```bash
tail -f /var/www/html/smisportalndudev/runtime/logs/refund_sync.log
```

### Manual Synchronization
If you need to trigger a sync immediately without waiting for the cron:
```bash
php /var/www/html/smisportalndudev/yii refund-sync/sync
```

### Troubleshooting
If a record fails to sync:
1.  The `sync_status` in the Portal database will be set to `2`.
2.  The error message will be recorded in the `sync_error` column.
3.  Once the issue is resolved, resetting `sync_status` to `0` for that record will cause the cron to attempt synchronization again.

---

## 4. Automation Scripts
The testing scripts in `modules/refund_requests/automation/` (e.g., `step2_apply.php` and `step4_finalize.php`) have also been updated to follow this staging-and-sync workflow.
