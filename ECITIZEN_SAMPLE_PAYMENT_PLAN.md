# eCitizen Logged-In Student Fee Payment Plan

## Objective

Enable the currently logged-in portal student to make a fee payment through eCitizen using the existing fees management flow, where eCitizen has `payment_mode_id = 12`.

## Current Fee Payment Flow

The current fees module posts money through this path:

```text
ReceiptSponsorFund
-> SponsorBeneficiary
-> BankingSlips
-> FeePayments
-> FeeTransactions
```

The payment mode is stored as:

```text
smis.fss_banking_slips.pay_mode
smis.fss_fee_payments.pay_mode
```

For eCitizen, both should carry:

```text
pay_mode = 12
```

## Key Existing Code Paths

- `modules/feesManagement/views/receipt-sponsor-fund/_search.php`
  - Allows selecting `payment_mode`.
  - eCitizen should appear if `payment_mode_id = 12` exists in `smis.fss_payment_modes`.

- `modules/feesManagement/controllers/ReceiptSponsorFundController.php`
  - `generateColumns()` maps selected payment mode into `BankingSlips.pay_mode`.
  - `actionPostBeneficiary()` creates banking slips and receipt numbers.

- `modules/feesManagement/models/BankingSlips.php`
  - `postFeePayments()` creates `fss_fee_payments`.
  - `postFeeTransactions()` creates a `CR` row in `fss_fee_transactions`.

- `modules/feesManagement/controllers/BankingSlipsController.php`
  - `actionPostFeePayments()` posts selected banking slips.
  - `actionSync()` posts all unposted banking slips except reversed slips.

## Assumptions

- The current portal user is authenticated.
- The logged-in user's `adm_refno` resolves to a `smisportal.sm_student_programme_curriculum.registration_number`.
- The resolved registration number exists in `smis.sm_student.student_number`.
- The student has an active `sm_student_programme_curriculum` record in the main SMIS database.
- The student has a matching `sm_academic_progress` record in the main SMIS database.
- eCitizen exists in `smis.fss_payment_modes` with `payment_mode_id = 12`.
- A bank account exists or will be created to represent the eCitizen settlement account.

## Local Code/Data Gaps Found

This portal checkout does not contain every object needed to run the full plan end to end. The missing fees-management code was found in the sibling SMIS project at:

```text
C:\Users\odhis\Documents\smis
```

- `smis/modules/feesManagement` is not present locally, so the referenced controllers/views cannot be verified from this checkout:
  - `ReceiptSponsorFundController`
  - `BankingSlipsController`
  - `modules/feesManagement/views/receipt-sponsor-fund/_search.php`
- The local model set includes:
  - `smis/models/PaymentMode.php`
  - `smis/models/ReceiptSponsorFund.php`
  - `smis/models/SponsorBeneficiary.php`
  - `smis/models/Banks.php`
- The local model set does not include:
  - `BankingSlips`
  - `FeePayments`
  - `FeeTransactions` for the `smis` application
  - `BankAccounts`
  - `BankBranches`
- The sibling SMIS project contains the missing code at:
  - `C:\Users\odhis\Documents\smis\modules\feesManagement\models\BankingSlips.php`
  - `C:\Users\odhis\Documents\smis\modules\feesManagement\models\FeePayments.php`
  - `C:\Users\odhis\Documents\smis\modules\feesManagement\models\FeeTransactions.php`
  - `C:\Users\odhis\Documents\smis\modules\feesManagement\models\BankAccounts.php`
  - `C:\Users\odhis\Documents\smis\modules\feesManagement\models\BankBranches.php`
  - `C:\Users\odhis\Documents\smis\modules\feesManagement\controllers\ReceiptSponsorFundController.php`
  - `C:\Users\odhis\Documents\smis\modules\feesManagement\controllers\BankingSlipsController.php`
- The local schema dumps do not include definitions or seed rows for the required `smis.fss_*` fee tables, including `fss_payment_modes`, `fss_banking_slips`, `fss_fee_payments`, `fss_fee_transactions`, `fss_bank_accounts`, `fss_banks`, `fss_bank_branches`, `fss_sponsor_beneficiary`, and `fss_receipt_sponsor_fund`.
- No local seed data was found for:
  - Logged-in student test data in the main SMIS database
  - eCitizen payment mode `payment_mode_id = 12`
  - eCitizen settlement bank account
  - eCitizen gateway config under `modules/ecitizen`
- This portal app now has an additional `smisDb` Yii DB component configured from the sibling SMIS database credentials. Use `Yii::$app->smisDb` when portal code needs to read/write the main `smis` database directly.

Because of these gaps, use the SQL checks below against the target SMIS database through `Yii::$app->smisDb`, or import/copy the missing fees-management module/schema before testing the full flow inside this portal checkout.

## Setup Checklist

## eCitizen Gateway Credentials

Use the same eCitizen credentials currently configured in the portal module, but load them from local configuration or environment variables rather than hard-coding them in controller logic or committing them to the repository.

```text
/var/www/html/smisportal/modules/ecitizen/config/credentials.php
```

```php
return [
    'apiClientID' => getenv('ECITIZEN_API_CLIENT_ID'),
    'apiKey' => getenv('ECITIZEN_API_KEY'),
    'secret' => getenv('ECITIZEN_SECRET'),
];
```

Use these with the non-secret gateway defaults from the portal module:

```php
return [
    'serviceID' => getenv('ECITIZEN_SERVICE_ID') ?: '2798167',
    'url' => getenv('ECITIZEN_PAYMENT_URL') ?: 'https://payments.ecitizen.go.ke/PaymentAPI/iframev2.1.php',
    'currency' => getenv('ECITIZEN_CURRENCY') ?: 'KES',
];
```

The current SMIS implementation should load these values from configuration rather than hard-coding them in controller logic.

## Logged-In Student Resolution

Do not hard-code a sample student registration number for portal payments. Resolve the paying student from the authenticated portal user:

```php
$studentProgramme = \app\models\StudentProgCurriculum::find()
    ->select('registration_number')
    ->where(['adm_refno' => Yii::$app->user->identity->adm_refno])
    ->asArray()
    ->one();

if (empty($studentProgramme['registration_number'])) {
    throw new \yii\web\NotFoundHttpException('Registration number not found for the logged in student.');
}

$registrationNumber = $studentProgramme['registration_number'];
```

When creating an eCitizen checkout request, store this resolved registration number against the local payment request/reference. The callback should look up the student from that trusted local request record or from the banking slip created by the server. It should not trust a browser-supplied or callback-supplied registration number as the source of truth.

1. Confirm eCitizen payment mode exists:

   ```sql
   SELECT *
   FROM smis.fss_payment_modes
   WHERE payment_mode_id = 12;
   ```

2. If missing, create the payment mode:

   ```sql
   INSERT INTO smis.fss_payment_modes (payment_mode_id, mode_code, description, mode_flag)
   VALUES (12, 'ECITIZEN', 'eCitizen', 1);
   ```

3. Confirm the logged-in student's resolved registration number exists in SMIS:

   ```sql
   SELECT *
   FROM smis.sm_student
   WHERE student_number = :registration_number;
   ```

4. Confirm the logged-in student's programme curriculum exists:

   ```sql
   SELECT *
   FROM smis.sm_student_programme_curriculum
   WHERE registration_number = :registration_number;
   ```

5. Confirm the logged-in student's academic progress exists:

   ```sql
   SELECT sap.*
   FROM smis.sm_academic_progress sap
   INNER JOIN smis.sm_student_programme_curriculum spc
       ON spc.student_prog_curriculum_id = sap.student_prog_curriculum_id
   WHERE spc.registration_number = :registration_number;
   ```

6. Create or identify an eCitizen settlement bank account in:

   ```text
   smis.fss_banks
   smis.fss_bank_branches
   smis.fss_bank_accounts
   ```

   The current receipting flow requires a bank account because `ReceiptSponsorFundController::generateColumns()` loads `BankAccounts::findOne($bankingDetails['bank_account'])`.

## Manual Payment Plan Using Existing Flow

Use this plan if eCitizen payments can be receipted through the current sponsor/batch workflow.

1. Create or select a sponsor record representing eCitizen or the paying entity.

2. Create a receipt sponsor fund:

   - Sponsor: eCitizen or selected sponsor.
   - Amount: sample amount to pay.
   - Academic session: target academic year.
   - Source reference: eCitizen transaction reference.
   - Transaction type: `POST`.

3. Add the logged-in student as a beneficiary:

   - Registration number: resolved from `Yii::$app->user->identity->adm_refno`
   - Amount: amount paid through eCitizen
   - Transfer status: `PENDING`
   - Transaction type: `POST`

4. Post the beneficiary into banking slips using:

   ```text
   /fees-management/receipt-sponsor-fund/list-beneficiary-view
   ```

   Select:

   - Bank account: eCitizen settlement account
   - Payment mode: eCitizen, `payment_mode_id = 12`
   - Payment type: applicable fee/payment type

5. Confirm a banking slip was created:

   ```sql
   SELECT *
   FROM smis.fss_banking_slips
   WHERE reg_number = :registration_number
     AND pay_mode = 12
   ORDER BY trans_id DESC;
   ```

6. Post the banking slip:

   - Use the Banking Deposits / Receipting screen, or
   - Run the existing sync action if that is how posting is handled in this environment.

7. Confirm payment record was created:

   ```sql
   SELECT fp.*
   FROM smis.fss_fee_payments fp
   INNER JOIN smis.fss_banking_slips bs
       ON bs.trans_id = fp.trans_id
   WHERE bs.reg_number = :registration_number
     AND fp.pay_mode = 12
   ORDER BY fp.fee_paymt_id DESC;
   ```

8. Confirm fee transaction was created:

   ```sql
   SELECT ft.*
   FROM smis.fss_fee_transactions ft
   INNER JOIN smis.fss_banking_slips bs
       ON bs.trans_id = ft.trans_id
   WHERE bs.reg_number = :registration_number
     AND ft.trans_type = 'CR'
   ORDER BY ft.trans_id DESC;
   ```

9. Confirm the student statement shows the credit:

   ```text
   /fees-management/reports/fee-statement
   ```

## Direct eCitizen Payment Plan

Use this plan if eCitizen payments should be made directly by students without creating sponsor funds and beneficiaries.

The current `BankingSlips::postFeePayments()` method assumes every payment has a `sponsor_beneficiary_id`. Direct student payments need a small refactor.

### Required Code Change

Refactor `BankingSlips::postFeePayments()` so it can resolve the student in either of these ways:

```text
1. If sponsor_beneficiary_id exists:
   Use the existing sponsor beneficiary path.

2. If sponsor_beneficiary_id is empty:
   Use BankingSlips.reg_number or BankingSlips.registration_number directly.
```

The direct path should:

1. Find student:

   ```php
   Student::find()
       ->where(['student_number' => $this->reg_number])
       ->one();
   ```

2. Find student programme curriculum.

3. Find academic progress.

4. Create `fss_fee_payments` with:

   ```text
   pay_mode = 12
   trans_id = banking slip trans_id
   trans_amount = eCitizen amount
   student_prog_curriculum_id = resolved student programme curriculum
   ```

5. Create `fss_fee_transactions` with:

   ```text
   trans_type = CR
   trans_amount = eCitizen amount
   trans_desc = eCitizen reference or narration
   ```

6. Mark banking slip:

   ```text
   post_status = POSTED
   ```

### Suggested eCitizen Callback Flow

1. Add a controller endpoint, for example:

   ```text
   /fees-management/ecitizen/callback
   ```

2. Validate the callback signature or token.

3. Extract the eCitizen payment details and resolve the student from the local payment request created by the logged-in user:

   ```text
   amount
   ecitizen_reference
   payment_date
   ```

4. Create a `BankingSlips` record:

   ```text
   deposit_date = payment_date
   deposit_amount = amount
   reg_number = resolved logged-in user's registration number
   registration_number = resolved logged-in user's registration number
   pay_mode = 12
   trans_reference = ecitizen_reference
   source_reference = ecitizen_reference
   post_status = NOT POSTED
   post_comment = eCitizen
   ```

5. Call:

   ```php
   $bankingSlip->postFeePayments();
   ```

6. Return success to eCitizen only after the payment and transaction rows are saved.

## Validation Queries

Use these after posting a payment for the logged-in student.

```sql
SELECT trans_id, reg_number, deposit_amount, pay_mode, post_status, post_comment, trans_reference
FROM smis.fss_banking_slips
WHERE reg_number = :registration_number
ORDER BY trans_id DESC;
```

```sql
SELECT fee_paymt_id, receipt_no, trans_amount, pay_mode, trans_id, student_prog_curriculum_id
FROM smis.fss_fee_payments
WHERE pay_mode = 12
ORDER BY fee_paymt_id DESC;
```

```sql
SELECT trans_id, academic_progress_id, trans_type, trans_amount, trans_desc, receipt_status
FROM smis.fss_fee_transactions
WHERE trans_type = 'CR'
ORDER BY trans_id DESC;
```

## Recommended Implementation Order

1. Confirm eCitizen mode `12` and the logged-in student's records exist.
2. Create or identify an eCitizen settlement bank account.
3. Test the existing sponsor-beneficiary flow using `pay_mode = 12`.
4. Confirm records are created in `fss_banking_slips`, `fss_fee_payments`, and `fss_fee_transactions`.
5. Decide whether direct student eCitizen payments are required.
6. If direct payments are required, refactor `BankingSlips::postFeePayments()` to support payments without `sponsor_beneficiary_id`.
7. Add an eCitizen callback endpoint.
8. Add duplicate protection using `trans_reference` or `source_reference`.
9. Add logging and reconciliation reports for eCitizen payments.
