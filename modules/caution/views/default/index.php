<?php
/**
 * modules/caution/views/default/index.php
 *
 * Handles modes: 'form' | 'form-chss' | 'status' | 'not-eligible' | 'update'
 *
 * @var yii\web\View $this
 * @var string       $mode
 * @var array        $post        form values (repopulated on error)
 * @var array        $errors      ['field' => 'message']
 * @var array        $bankList    [bank_id => bank_name]
 * @var array        $branchList  [branch_id => branch_name]
 * @var array        $data        status mode: caution_refunds row
 * @var array        $existing    update mode: caution_refunds row
 * @var string       $approvalMessage
 * @var bool         $isChss
 * @var string       $message     not-eligible message
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Caution Refund Application';

// Ensure these are always defined so the view never throws "undefined variable"
$post       = $post       ?? [];
$errors     = $errors     ?? [];
$bankList   = $bankList   ?? [];
$branchList = $branchList ?? [];
$isChss     = $isChss     ?? false;
$existing   = $existing   ?? [];

// Shorthand helpers
$v = fn(string $k) => Html::encode($post[$k] ?? '');
$e = fn(string $k) => isset($errors[$k])
    ? '<p class="cr-error">' . Html::encode($errors[$k]) . '</p>' : '';
?>

<?php $this->registerCssFile('@web/css/caution-refund.css') ?>

<div class="cr-page">
<div class="cr-container">

  <?php /* Flash messages */ ?>
  <?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="cr-flash cr-flash--success"><?= Html::encode(Yii::$app->session->getFlash('success')) ?></div>
  <?php endif; ?>
  <?php if (Yii::$app->session->hasFlash('danger')): ?>
    <div class="cr-flash cr-flash--danger"><?= Html::encode(Yii::$app->session->getFlash('danger')) ?></div>
  <?php endif; ?>

  <?php /* DB/save error — shown inline when saveRequest() returns an error string */ ?>
  <?php if (!empty($errors['_save'])): ?>
    <div class="cr-flash cr-flash--danger">
      <strong>Save failed:</strong> <?= Html::encode($errors['_save']) ?>
    </div>
  <?php endif; ?>


  <?php /* ══════════════════════════════════════════════════════════════
           NOT ELIGIBLE
         ══════════════════════════════════════════════════════════════ */ ?>
  <?php if ($mode === 'not-eligible'): ?>

    <div class="cr-header">
      <span class="cr-header__badge">University of Nairobi</span>
      <h1 class="cr-header__title">Caution Refund</h1>
    </div>
    <div class="cr-card">
      <div class="cr-card__body" style="text-align:center;padding:3rem 2rem;">
        <p style="font-size:1rem;line-height:1.7;color:var(--cr-slate-700);max-width:420px;margin:0 auto;">
          <?= Html::encode($message ?? '') ?>
        </p>
      </div>
    </div>


  <?php /* ══════════════════════════════════════════════════════════════
           STATUS
         ══════════════════════════════════════════════════════════════ */ ?>
  <?php elseif ($mode === 'status'):
    $statusRaw  = strtoupper($data['approval_status'] ?? '');
    $badgeClass = match($statusRaw) {
      'APPROVED' => 'cr-badge--approved',
      'REJECTED' => 'cr-badge--rejected',
      default    => 'cr-badge--pending',
    };
    $statusLabel = match($statusRaw) {
      'APPROVED' => 'Approved',
      'REJECTED' => 'Rejected',
      default    => 'Pending Approval',
    };
  ?>

    <div class="cr-header">
      <span class="cr-header__badge">University of Nairobi</span>
      <h1 class="cr-header__title">Refund Processing Status</h1>
      <p class="cr-header__sub">Track the progress of your caution refund application</p>
    </div>

    <div class="cr-card">
      <div class="cr-card__header">
        <div class="cr-card__header-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
          </svg>
        </div>
        <h2 class="cr-card__title">Application Status</h2>
      </div>
      <div class="cr-card__body">

        <div class="cr-status-row">
          <span class="cr-status-row__label">Refund Type</span>
          <span class="cr-status-row__value"><?= Html::encode(strtoupper($data['refund_type'] ?? '')) ?></span>
        </div>
        <div class="cr-status-row">
          <span class="cr-status-row__label">Amount</span>
          <span class="cr-status-row__value">KES <?= number_format((float)($data['amount_refundable'] ?? 0), 2) ?></span>
        </div>
        <div class="cr-status-row">
          <span class="cr-status-row__label">Status</span>
          <span class="cr-status-row__value">
            <span class="cr-badge <?= $badgeClass ?>"><?= $statusLabel ?></span>
          </span>
        </div>
        <div class="cr-status-row">
          <span class="cr-status-row__label">Progress</span>
          <span class="cr-status-row__value"><?= Html::encode($approvalMessage ?? '') ?></span>
        </div>

        <?php if (!$isChss): ?>
          <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--cr-blue-50);">
            <!-- <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
              <p style="font-size:.8rem;font-weight:700;color:var(--cr-slate-400);text-transform:uppercase;letter-spacing:.04em;margin:0;">
                Bank Details Provided
              </p>
              <a class="cr-edit-link" href="<?= Url::to(['default/update']) ?>">
                Incorrect? Update here
              </a>
            </div> -->
            <div class="cr-status-row">
              <span class="cr-status-row__label">Bank</span>
              <span class="cr-status-row__value"><?= Html::encode($data['bank_name']   ?? '—') ?></span>
            </div>
            <div class="cr-status-row">
              <span class="cr-status-row__label">Branch</span>
              <span class="cr-status-row__value"><?= Html::encode($data['branch_name'] ?? '—') ?></span>
            </div>
            <div class="cr-status-row">
              <span class="cr-status-row__label">Account Number</span>
              <span class="cr-status-row__value"><?= Html::encode($data['acc_no']      ?? '—') ?></span>
            </div>
            <div class="cr-status-row">
              <span class="cr-status-row__label">Account Name</span>
              <span class="cr-status-row__value"><?= Html::encode($data['acc_name']    ?? '—') ?></span>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>


  <?php /* ══════════════════════════════════════════════════════════════
           UPDATE — edit existing request details
         ══════════════════════════════════════════════════════════════ */ ?>
  <?php elseif ($mode === 'update'): ?>

    <div class="cr-header">
      <span class="cr-header__badge">University of Nairobi</span>
      <h1 class="cr-header__title">Update Your Details</h1>
      <p class="cr-header__sub">Correct your contact or bank information before processing</p>
    </div>

    <div class="cr-notice">
      <p class="cr-notice__title">What you can change</p>
      <p style="font-size:.86rem;line-height:1.7;margin:0;color:var(--cr-slate-700);">
        You can update your contact and bank details below.
        The <strong>refund amount</strong>
        (KES <?= number_format((float)($existing['amount_refundable'] ?? 0), 2) ?>)
        and <strong>refund type</strong>
        (<?= Html::encode(strtoupper($existing['refund_type'] ?? '')) ?>)
        are locked and cannot be changed.
      </p>
    </div>

    <form method="post" action="<?= Url::to(['default/update']) ?>" enctype="multipart/form-data">
      <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

      <div class="cr-card">
        <div class="cr-card__header">
          <div class="cr-card__header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
          </div>
          <h2 class="cr-card__title">Contact Details</h2>
        </div>
        <div class="cr-card__body">
          <div class="cr-form-grid">
            <div class="cr-field">
              <label for="u_mobile_no">
                <?= $isChss ? 'Registered M-PESA Number' : 'Mobile Number' ?>
                <span class="required-star">*</span>
              </label>
              <input type="text" id="u_mobile_no" name="mobile_no"
                value="<?= $v('mobile_no') ?>" placeholder="07XX XXX XXX">
              <?= $e('mobile_no') ?>
            </div>
            <div class="cr-field">
              <label for="u_email">Email Address</label>
              <input type="email" id="u_email" name="email"
                value="<?= $v('email') ?>" placeholder="you@example.com">
            </div>
            <div class="cr-field">
              <label for="u_passport_id">ID / Passport No. <span class="required-star">*</span></label>
              <input type="text" id="u_passport_id" name="passport_id"
                value="<?= $v('passport_id') ?>" placeholder="National ID or Passport">
              <?= $e('passport_id') ?>
            </div>
          </div>
        </div>
      </div>

      <?php if (!$isChss): ?>
      <div class="cr-card">
        <div class="cr-card__header">
          <div class="cr-card__header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
            </svg>
          </div>
          <h2 class="cr-card__title">Bank Account Details</h2>
        </div>
        <div class="cr-card__body">
          <div class="cr-form-grid">
            <div class="cr-field">
              <label for="u_acc_name">Account Name <span class="required-star">*</span></label>
              <input type="text" id="u_acc_name" name="acc_name"
                value="<?= $v('acc_name') ?>" placeholder="As in your bank records">
              <?= $e('acc_name') ?>
            </div>
            <div class="cr-field">
              <label for="u_acc_no">Account Number <span class="required-star">*</span></label>
              <input type="text" id="u_acc_no" name="acc_no"
                value="<?= $v('acc_no') ?>" placeholder="Account number">
              <?= $e('acc_no') ?>
            </div>
            <div class="cr-field">
              <label for="u_bank_id">Bank Name <span class="required-star">*</span></label>
              <select id="u_bank_id" name="bank_id" onchange="loadBranchesUpdate(this.value)">
                <option value="">— Select Bank —</option>
                <?php foreach ($bankList as $id => $name): ?>
                  <option value="<?= Html::encode($id) ?>"
                    <?= (int)($post['bank_id'] ?? 0) === (int)$id ? 'selected' : '' ?>>
                    <?= Html::encode($name) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <?= $e('bank_id') ?>
            </div>
            <div class="cr-field">
              <label for="u_branch_id">Branch <span class="required-star">*</span></label>
              <select id="u_branch_id" name="branch_id">
                <option value="">— Select Branch —</option>
                <?php foreach ($branchList as $id => $name): ?>
                  <option value="<?= Html::encode($id) ?>"
                    <?= (int)($post['branch_id'] ?? 0) === (int)$id ? 'selected' : '' ?>>
                    <?= Html::encode($name) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <?= $e('branch_id') ?>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <div style="display:flex;gap:1rem;align-items:center;justify-content:center;margin-top:1rem;margin-bottom:2rem;">
        <a href="<?= Url::to(['default/index']) ?>"
           style="display:inline-flex;align-items:center;padding:.65rem 1.5rem;
                  font-family:var(--cr-font);font-size:.88rem;font-weight:700;border-radius:999px;
                  border:1.5px solid var(--cr-blue-200);color:var(--cr-blue-600);
                  background:transparent;text-decoration:none;">
          ← Cancel
        </a>
        <button type="submit" class="cr-btn cr-btn--primary">Save Changes</button>
      </div>
    </form>

    <script>
    function loadBranchesUpdate(bankId) {
      const sel = document.getElementById('u_branch_id');
      sel.innerHTML = '<option>Loading…</option>';
      if (!bankId) { sel.innerHTML = '<option value="">— Select Branch —</option>'; return; }
      fetch('<?= Url::to(['default/branches']) ?>?bank_id=' + bankId)
        .then(r => r.json())
        .then(data => {
          sel.innerHTML = '<option value="">— Select Branch —</option>';
          data.forEach(b => {
            const o = document.createElement('option');
            o.value = b.branch_id; o.textContent = b.branch_name;
            sel.appendChild(o);
          });
        });
    }
    </script>


  <?php /* ══════════════════════════════════════════════════════════════
           FORM (new application) — standard or CHSS
         ══════════════════════════════════════════════════════════════ */ ?>
  <?php else:
    $isChssForm = ($mode === 'form-chss');
  ?>

    <div class="cr-header">
      <span class="cr-header__badge">
        University of Nairobi<?= $isChssForm ? ' – CHSS' : '' ?>
      </span>
      <h1 class="cr-header__title">Caution Refund Application</h1>
      <p class="cr-header__sub">
        <?= $isChssForm ? 'M-PESA refund for CHSS Module I students' : 'Online portal for graduated students' ?>
      </p>
    </div>

    <div class="cr-notice cr-notice--warning">
      <p class="cr-notice__title">Read before submitting</p>
      <ol>
        <?php if ($isChssForm): ?>
          <li>Only graduated students who have returned the University gown can apply for caution refund.</li>
          <li>Refund will be paid through <strong>M-PESA</strong>. No refund through a third party or by cash.</li>
          <li>Ensure the Safaricom number is registered for M-PESA in the student's name. The College will not be liable for incorrect details.</li>
        <?php else: ?>
          <li>Only graduated students who have returned the University gown can apply for caution refund.</li>
          <li>Refund will be paid through the applicant's bank account ONLY. No cash or third-party payments.</li>
          <li>Ensure details are correct — UNES will not be held liable for incorrect bank details.</li>
        <?php endif; ?>
        <li>Payment will not exceed 30 working days after submission.</li>
        <li>Other refunds (e.g. Tuition Fees): download the manual form
          <a href="./files/Refund-Form-Revised _2_ 002.pdf" style="color:var(--cr-blue-600);font-weight:700;">here</a>.
        </li>
        <li>Fields marked <span style="color:var(--cr-red);font-weight:700;">*</span> are required.</li>
      </ol>
    </div>

    <?php /* ── KEY FIX: action URL uses module-relative route ─────────── */ ?>
    <form method="post" action="<?= Url::to(['default/index']) ?>" enctype="multipart/form-data">
      <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

      <!-- Contact Details -->
      <div class="cr-card">
        <div class="cr-card__header">
          <div class="cr-card__header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
          </div>
          <h2 class="cr-card__title">Contact Details</h2>
        </div>
        <div class="cr-card__body">
          <div class="cr-form-grid">
            <div class="cr-field">
              <label for="mobile_no">
                <?= $isChssForm ? 'Registered M-PESA Number' : 'Mobile Number' ?>
                <span class="required-star">*</span>
              </label>
              <input type="text" id="mobile_no" name="mobile_no"
                value="<?= $v('mobile_no') ?>" placeholder="07XX XXX XXX">
              <?= $e('mobile_no') ?>
            </div>
            <div class="cr-field">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email"
                value="<?= $v('email') ?>"
                placeholder="<?= $isChssForm ? 'Optional' : 'you@example.com' ?>">
            </div>
            <?php if ($isChssForm): ?>
              <div class="cr-field">
                <label for="passport_id">ID / Passport No. <span class="required-star">*</span></label>
                <input type="text" id="passport_id" name="passport_id"
                  value="<?= $v('passport_id') ?>" placeholder="National ID or Passport">
                <?= $e('passport_id') ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Amount Refundable -->
      <div class="cr-card">
        <div class="cr-card__header">
          <div class="cr-card__header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="1" x2="12" y2="23"/>
              <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
            </svg>
          </div>
          <h2 class="cr-card__title">Amount Refundable</h2>
        </div>
        <div class="cr-card__body">
          <div class="cr-form-grid">
            <div class="cr-field">
              <label for="amount_refundable">Caution Money <span class="required-star">*</span></label>
              <input type="text" id="amount_refundable" name="amount_refundable"
                value="<?= $v('amount_refundable') ?>" placeholder="e.g. 5000">
              <?= $e('amount_refundable') ?>
            </div>
            <div class="cr-field">
              <label for="refund_type">Refund Type <span class="required-star">*</span></label>
              <select id="refund_type" name="refund_type">
                <option value="">— Select —</option>
                <option value="CAUTION" <?= ($post['refund_type'] ?? '') === 'CAUTION' ? 'selected' : '' ?>>
                  Caution
                </option>
              </select>
              <?= $e('refund_type') ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Bank Details (non-CHSS only) -->
      <?php if (!$isChssForm): ?>
        <div class="cr-card">
          <div class="cr-card__header">
            <div class="cr-card__header-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
              </svg>
            </div>
            <h2 class="cr-card__title">Bank Account Details</h2>
          </div>
          <div class="cr-card__body">
            <div class="cr-form-grid">
              <div class="cr-field">
                <label for="acc_name">Account Name <span class="required-star">*</span></label>
                <input type="text" id="acc_name" name="acc_name"
                  value="<?= $v('acc_name') ?>" placeholder="As in your bank records">
                <?= $e('acc_name') ?>
              </div>
              <div class="cr-field">
                <label for="acc_no">Account Number <span class="required-star">*</span></label>
                <input type="text" id="acc_no" name="acc_no"
                  value="<?= $v('acc_no') ?>" placeholder="Account number">
                <?= $e('acc_no') ?>
              </div>
              <div class="cr-field">
                <label for="bank_id">Bank Name <span class="required-star">*</span></label>
                <select id="bank_id" name="bank_id" onchange="loadBranches(this.value)">
                  <option value="">— Select Bank —</option>
                  <?php foreach ($bankList as $id => $name): ?>
                    <option value="<?= Html::encode($id) ?>"
                      <?= (int)($post['bank_id'] ?? 0) === (int)$id ? 'selected' : '' ?>>
                      <?= Html::encode($name) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <?= $e('bank_id') ?>
              </div>
              <div class="cr-field">
                <label for="branch_id">Branch <span class="required-star">*</span></label>
                <select id="branch_id" name="branch_id">
                  <option value="">— Select Branch —</option>
                  <?php foreach ($branchList as $id => $name): ?>
                    <option value="<?= Html::encode($id) ?>"
                      <?= (int)($post['branch_id'] ?? 0) === (int)$id ? 'selected' : '' ?>>
                      <?= Html::encode($name) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <?= $e('branch_id') ?>
              </div>
              <div class="cr-field">
                <label for="passport_id">ID / Passport No. <span class="required-star">*</span></label>
                <input type="text" id="passport_id" name="passport_id"
                  value="<?= $v('passport_id') ?>" placeholder="National ID or Passport">
                <?= $e('passport_id') ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Declaration -->
      <div class="cr-card">
        <div class="cr-card__header">
          <div class="cr-card__header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <h2 class="cr-card__title">Declaration</h2>
        </div>
        <div class="cr-card__body">
          <label class="cr-declaration">
            <input type="checkbox" name="declarataion_status" value="YES"
              <?= !empty($post['declarataion_status']) ? 'checked' : '' ?>>
            <p>I declare that the information I have given is true to the best of my knowledge and I understand that making a false claim shall lead to severe disciplinary action as per the University of Nairobi statutes.</p>
          </label>
          <?= $e('declarataion_status') ?>
        </div>
      </div>

      <div style="text-align:center;margin-top:1rem;margin-bottom:2rem;">
        <button type="submit" class="cr-btn cr-btn--primary">Submit Application</button>
      </div>

    </form>

    <script>
    function loadBranches(bankId) {
      const sel = document.getElementById('branch_id');
      sel.innerHTML = '<option>Loading…</option>';
      if (!bankId) { sel.innerHTML = '<option value="">— Select Branch —</option>'; return; }
      fetch('<?= Url::to(['default/branches']) ?>?bank_id=' + bankId)
        .then(r => {
          if (!r.ok) throw new Error('HTTP ' + r.status);
          return r.json();
        })
        .then(data => {
          sel.innerHTML = '<option value="">— Select Branch —</option>';
          data.forEach(b => {
            const o = document.createElement('option');
            o.value = b.branch_id; o.textContent = b.branch_name;
            sel.appendChild(o);
          });
        })
        .catch(err => {
          console.error('Branch load error:', err);
          sel.innerHTML = '<option>Error loading branches</option>';
        });
    }
    </script>

  <?php endif; ?>

</div><!-- /.cr-container -->
</div><!-- /.cr-page -->