# eCitizen Workflow SQL Checks

Use this file to confirm the eCitizen payment workflow across the two databases used by this portal.

## Database Map

```php
Yii::$app->db      // Portal database, configured in config/db.php
Yii::$app->smisDb  // Main SMIS database, configured in config/smis_db.php
```

The portal database is used to resolve the logged-in student.

The main SMIS database is used for the actual eCitizen fee workflow:

```text
smis.fss_banking_slips
-> smis.fss_fee_payments
-> smis.fss_fee_transactions
```

The eCitizen payment mode is:

```sql
pay_mode = 12
```

# Database 1: Portal DB

Run this section against the **portal database** connected through:

```php
Yii::$app->db
```

## 1. Confirm the admitted student exists

```sql
SELECT
    adm_refno,
    surname,
    other_names,
    primary_email,
    primary_phone_no,
    national_id,
    passport_no,
    admission_status
FROM smisportal.sm_admitted_student
WHERE adm_refno = :adm_refno;
```

## 2. Confirm the logged-in user resolves to a registration number

```sql
SELECT
    student_prog_curriculum_id,
    student_id,
    registration_number,
    adm_refno,
    status_id
FROM smisportal.sm_student_programme_curriculum
WHERE adm_refno = :adm_refno;
```

## 3. Confirm the portal student record

```sql
SELECT
    student_id,
    student_number,
    surname,
    other_names,
    primary_email,
    primary_phone_no
FROM smisportal.sm_student
WHERE student_number = :registration_number;
```

# Database 2: Main SMIS DB

Run this section against the **main SMIS database** connected through:

```php
Yii::$app->smisDb
```

## 1. Setup Checks

### Confirm eCitizen payment mode exists

```sql
SELECT
    payment_mode_id,
    mode_code,
    description,
    mode_flag
FROM smis.fss_payment_modes
WHERE payment_mode_id = 12;
```

### Confirm available payment types

```sql
SELECT
    payment_type_id,
    payment_desc
FROM smis.fss_payment_types
ORDER BY payment_type_id;
```

### Confirm settlement bank accounts

```sql
SELECT
    ba.brank_account_id,
    ba.account_no,
    ba.account_details,
    ba.branch_code,
    bb.branch_name,
    bb.bank_code,
    b.brank_id,
    b.bank_name
FROM smis.fss_bank_accounts ba
LEFT JOIN smis.fss_bank_branches bb
    ON bb.branch_code = ba.branch_code
LEFT JOIN smis.fss_banks b
    ON b.bank_code = bb.bank_code
ORDER BY b.bank_name, ba.account_no;
```

## 2. Student Resolution Checks

### Confirm student exists in SMIS

```sql
SELECT
    student_id,
    student_number,
    surname,
    other_names,
    primary_email,
    primary_phone_no
FROM smis.sm_student
WHERE student_number = :registration_number;
```

### Confirm student programme curriculum exists in SMIS

```sql
SELECT
    student_prog_curriculum_id,
    student_id,
    registration_number,
    prog_curriculum_id,
    student_category_id,
    adm_refno,
    status_id
FROM smis.sm_student_programme_curriculum
WHERE registration_number = :registration_number
ORDER BY student_prog_curriculum_id DESC;
```

### Confirm academic progress exists in SMIS

```sql
SELECT
    sap.academic_progress_id,
    sap.acad_session_id,
    sap.academic_level_id,
    sap.student_prog_curriculum_id,
    sap.progress_status_id,
    sap.current_status
FROM smis.sm_academic_progress sap
INNER JOIN smis.sm_student_programme_curriculum spc
    ON spc.student_prog_curriculum_id = sap.student_prog_curriculum_id
WHERE spc.registration_number = :registration_number
ORDER BY sap.current_status DESC, sap.academic_progress_id DESC;
```

## 3. eCitizen Request Checks

The portal creates pending eCitizen invoices in:

```text
smis.fss_banking_slips
```

### Confirm pending eCitizen invoice/request was created

```sql
SELECT
    trans_id,
    deposit_date,
    deposit_type,
    payment_type_id,
    deposit_amount,
    reg_number,
    registration_number,
    post_status,
    post_comment,
    receipt_no,
    pay_mode,
    trans_reference,
    source_reference,
    account_no,
    bank_id,
    bank_number,
    last_update
FROM smis.fss_banking_slips
WHERE pay_mode = 12
  AND reg_number = :registration_number
ORDER BY trans_id DESC;
```

### Confirm one eCitizen request by reference

```sql
SELECT
    *
FROM smis.fss_banking_slips
WHERE pay_mode = 12
  AND (
      source_reference = :ecitizen_reference
      OR trans_reference = :ecitizen_reference
  );
```

### Confirm requests still waiting for payment/posting

```sql
SELECT
    trans_id,
    deposit_date,
    deposit_amount,
    reg_number,
    post_status,
    post_comment,
    trans_reference,
    source_reference
FROM smis.fss_banking_slips
WHERE pay_mode = 12
  AND COALESCE(post_status, '') <> 'POSTED'
ORDER BY trans_id DESC;
```

### Confirm posted eCitizen banking slips

```sql
SELECT
    trans_id,
    deposit_date,
    deposit_amount,
    reg_number,
    post_status,
    post_comment,
    receipt_no,
    pay_mode,
    trans_reference,
    source_reference,
    process_date,
    last_update
FROM smis.fss_banking_slips
WHERE pay_mode = 12
  AND post_status = 'POSTED'
ORDER BY trans_id DESC;
```

## 4. Fee Payment Checks

### Confirm fee payment row was created

```sql
SELECT
    fp.fee_paymt_id,
    fp.receipt_no,
    fp.trans_date,
    fp.trans_amount,
    fp.pay_mode,
    fp.collection_point_id,
    fp.user_id,
    fp.entry_date,
    fp.trans_id,
    fp.academic_session,
    fp.authorized_by,
    fp.authorized_date,
    fp.receipt_status,
    fp.exchange_rate,
    fp.student_prog_curriculum_id,
    bs.reg_number,
    bs.source_reference,
    bs.trans_reference
FROM smis.fss_fee_payments fp
INNER JOIN smis.fss_banking_slips bs
    ON bs.trans_id = fp.trans_id
WHERE fp.pay_mode = 12
  AND bs.reg_number = :registration_number
ORDER BY fp.fee_paymt_id DESC;
```

### Confirm fee payment for one eCitizen reference

```sql
SELECT
    fp.*
FROM smis.fss_fee_payments fp
INNER JOIN smis.fss_banking_slips bs
    ON bs.trans_id = fp.trans_id
WHERE fp.pay_mode = 12
  AND (
      bs.source_reference = :ecitizen_reference
      OR bs.trans_reference = :ecitizen_reference
  );
```

## 5. Fee Transaction Checks

### Confirm student statement credit was created

```sql
SELECT
    ft.trans_id,
    ft.academic_progress_id,
    ft.trans_date,
    ft.trans_type,
    ft.trans_amount,
    ft.trans_desc,
    ft.user_id,
    ft.receipt_status,
    ft.exchange_rate,
    ft.progress_code,
    ft.sync_status,
    ft.fee_trans_id,
    bs.reg_number,
    bs.source_reference,
    bs.trans_reference
FROM smis.fss_fee_transactions ft
INNER JOIN smis.fss_banking_slips bs
    ON bs.trans_id = ft.trans_id
WHERE bs.pay_mode = 12
  AND ft.trans_type = 'CR'
  AND bs.reg_number = :registration_number
ORDER BY ft.trans_id DESC;
```

### Confirm fee transaction for one eCitizen reference

```sql
SELECT
    ft.*
FROM smis.fss_fee_transactions ft
INNER JOIN smis.fss_banking_slips bs
    ON bs.trans_id = ft.trans_id
WHERE bs.pay_mode = 12
  AND ft.trans_type = 'CR'
  AND (
      bs.source_reference = :ecitizen_reference
      OR bs.trans_reference = :ecitizen_reference
  );
```

## 6. Full Workflow Trace

This query shows the full request-to-posting path.

```sql
SELECT
    bs.trans_id,
    bs.reg_number,
    bs.deposit_date,
    bs.deposit_amount,
    bs.post_status,
    bs.post_comment,
    bs.receipt_no,
    bs.source_reference,
    bs.trans_reference,
    fp.fee_paymt_id,
    fp.trans_date AS fee_payment_date,
    fp.trans_amount AS fee_payment_amount,
    fp.student_prog_curriculum_id,
    ft.academic_progress_id,
    ft.trans_type,
    ft.trans_amount AS fee_transaction_amount,
    ft.trans_desc,
    ft.sync_status
FROM smis.fss_banking_slips bs
LEFT JOIN smis.fss_fee_payments fp
    ON fp.trans_id = bs.trans_id
LEFT JOIN smis.fss_fee_transactions ft
    ON ft.trans_id = bs.trans_id
WHERE bs.pay_mode = 12
  AND bs.reg_number = :registration_number
ORDER BY bs.trans_id DESC;
```

## 7. Duplicate Protection Checks

### Check duplicate banking slip references

```sql
SELECT
    source_reference,
    COUNT(*) AS duplicate_count
FROM smis.fss_banking_slips
WHERE pay_mode = 12
GROUP BY source_reference
HAVING COUNT(*) > 1
ORDER BY duplicate_count DESC;
```

### Check duplicate fee payments for one banking slip

```sql
SELECT
    trans_id,
    COUNT(*) AS payment_count
FROM smis.fss_fee_payments
WHERE pay_mode = 12
GROUP BY trans_id
HAVING COUNT(*) > 1
ORDER BY payment_count DESC;
```

### Check duplicate fee transactions for one banking slip

```sql
SELECT
    trans_id,
    COUNT(*) AS transaction_count
FROM smis.fss_fee_transactions
WHERE trans_type = 'CR'
GROUP BY trans_id
HAVING COUNT(*) > 1
ORDER BY transaction_count DESC;
```

## 8. Recent eCitizen Activity

```sql
SELECT
    bs.trans_id,
    bs.reg_number,
    bs.deposit_amount,
    bs.post_status,
    bs.post_comment,
    bs.source_reference,
    bs.trans_reference,
    bs.last_update,
    CASE
        WHEN fp.fee_paymt_id IS NULL THEN 'NO FEE PAYMENT'
        ELSE 'FEE PAYMENT CREATED'
    END AS fee_payment_status,
    CASE
        WHEN ft.trans_id IS NULL THEN 'NO FEE TRANSACTION'
        ELSE 'FEE TRANSACTION CREATED'
    END AS fee_transaction_status
FROM smis.fss_banking_slips bs
LEFT JOIN smis.fss_fee_payments fp
    ON fp.trans_id = bs.trans_id
LEFT JOIN smis.fss_fee_transactions ft
    ON ft.trans_id = bs.trans_id
WHERE bs.pay_mode = 12
ORDER BY bs.trans_id DESC
LIMIT 50;
```
