<?php
/**
 * views/caution-refund/index.php
 *
 * Single view file that handles all four display states.
 * The controller passes $mode to switch between them.
 *
 * @var yii\web\View $this
 * @var string       $mode        – 'form' | 'form-chss' | 'status' | 'not-eligible'
 *
 * For mode = 'form' or 'form-chss':
 * @var array        $post        – repopulates fields after validation error
 * @var array        $errors      – ['field_name' => 'message']
 * @var array        $bankList    – [bank_id => bank_name]
 * @var array        $branchList  – [branch_id => branch_name]
 *
 * For mode = 'status':
 * @var array        $data        – caution_refunds row joined with bank/branch names
 * @var string       $approvalMessage
 * @var bool         $isChss
 *
 * For mode = 'not-eligible':
 * @var string       $message
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Caution Refund Application';

// ── Helpers (only used in form modes) ────────────────────────────────────────
$v = fn(string $k) => Html::encode($post[$k] ?? '');
$e = fn(string $k) => isset($errors[$k])
    ? '<p class="cr-error">'.Html::encode($errors[$k]).'</p>' : '';
?>

<?php /* ═══════════════════════════════════════════════════════════════════
         INLINE STYLES — paste this block into your main layout once,
         or keep it here and Yii will only load it on caution-refund pages.
       ═══════════════════════════════════════════════════════════════════ */ ?>
<?php $this->registerCssFile('@web/css/caution-refund.css') ?>

<div class="cr-page">
<div class="cr-container">

<?php /* ── Flash messages (shown after redirect) ────────────────────── */ ?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
  <div class="cr-flash cr-flash--success"><?= Html::encode(Yii::$app->session->getFlash('success')) ?></div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('danger')): ?>
  <div class="cr-flash cr-flash--danger"><?= Html::encode(Yii::$app->session->getFlash('danger')) ?></div>
<?php endif; ?>


<?php /* ╔══════════════════════════════════════════════════════════════════╗
         ║  NOT ELIGIBLE                                                    ║
         ╚══════════════════════════════════════════════════════════════════╝ */ ?>
<?php if ($mode === 'not-eligible'): ?>

  <div class="cr-header">
    <span class="cr-header__badge">University of Nairobi</span>
    <h1 class="cr-header__title">Caution Refund</h1>
  </div>
  <div class="cr-card">
    <div class="cr-card__body" style="text-align:center;padding:3rem 2rem;">
      <p style="font-size:1rem;line-height:1.7;color:var(--cr-slate-700);max-width:420px;margin:0 auto;">
        <?= Html::encode($message) ?>
      </p>
    </div>
  </div>


<?php /* ╔══════════════════════════════════════════════════════════════════╗
         ║  STATUS — submitted request progress                             ║
         ╚══════════════════════════════════════════════════════════════════╝ */ ?>
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
        <span class="cr-status-row__value"><span class="cr-badge <?= $badgeClass ?>"><?= $statusLabel ?></span></span>
      </div>
      <div class="cr-status-row">
        <span class="cr-status-row__label">Progress</span>
        <span class="cr-status-row__value"><?= Html::encode($approvalMessage) ?></span>
      </div>

      <?php if (!$isChss): ?>
        <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--cr-blue-50);">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
            <p style="font-size:.8rem;font-weight:700;color:var(--cr-slate-400);text-transform:uppercase;letter-spacing:.04em;margin:0;">
              Bank Details Provided
            </p>
            <a class="cr-edit-link" href="<?= Url::to(['/caution-refund/update',
              'bank_id'           => $data['bank_id']           ?? '',
              'mobile_no'         => $data['mobile_no']         ?? '',
              'email'             => $data['email']             ?? '',
              'amount_refundable' => $data['amount_refundable'] ?? '',
              'acc_name'          => $data['acc_name']          ?? '',
              'acc_no'            => $data['acc_no']            ?? '',
              'passport_id'       => $data['passport_id']       ?? '',
              'refund_type'       => $data['refund_type']       ?? '',
              'branch_id'         => $data['branch_id']         ?? '',
            ]) ?>">Incorrect? Update here</a>
          </div>
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


<?php /* ╔══════════════════════════════════════════════════════════════════╗
         ║  FORM — standard (bank account) or CHSS (M-PESA)                ║
         ╚══════════════════════════════════════════════════════════════════╝ */ ?>
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

  <?php /* ── Instructions ─────────────────────────────────────────────── */ ?>
  <div class="cr-notice cr-notice--warning">
    <p class="cr-notice__title">Read before submitting</p>
    <?php if ($isChssForm): ?>
    <ol>
      <li>Beginning 22nd January 2015, ALL CHSS undergraduate Module I students shall apply for caution money online through the student portal.</li>
      <li>Only graduated students who have returned the University gown can apply for caution refund.</li>
      <li>Refund will be paid through <strong>M-PESA</strong>. No refund through a third party or by cash.</li>
      <li>Ensure the Safaricom number is registered for M-PESA in the student's name. The College will not be liable for incorrect details.</li>
      <li>Payment will not exceed 30 working days after submission.</li>
      <li>Other refunds: download the manual form <a href="./files/Refund-Form-Revised _2_ 002.pdf" style="color:var(--cr-blue-600);font-weight:700;">here</a>.</li>
      <li>Fields marked <span style="color:var(--cr-red);font-weight:700;">*</span> are required.</li>
    </ol>
    <?php else: ?>
    <ol>
      <li>Beginning 1st September 2013 ALL graduated students shall apply for caution money refund online through the student portal.</li>
      <li>Only graduated students who have returned the University gown can apply for caution refund.</li>
      <li>Refund will be paid through the applicant's bank account ONLY. No cash or third-party payments.</li>
      <li>Ensure details are correct before submitting — UNES will not be held liable for incorrect bank details.</li>
      <li>Payment will not exceed 30 working days after submission.</li>
      <li>Other refunds (e.g. Tuition Fees): download the manual form <a href="./files/Refund-Form-Revised _2_ 002.pdf" style="color:var(--cr-blue-600);font-weight:700;">here</a>.</li>
      <li>Fields marked <span style="color:var(--cr-red);font-weight:700;">*</span> are required.</li>
    </ol>
    <?php endif; ?>
  </div>

  <?php /* ── Form ──────────────────────────────────────────────────────── */ ?>
  <form method="post" action="<?= Url::to(['/caution-refund/index']) ?>" enctype="multipart/form-data">
    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

    <?php /* ── Contact Details ─────────────────────────────────────────── */ ?>
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
              value="<?= $v('email') ?>" placeholder="<?= $isChssForm ? 'Optional' : 'you@example.com' ?>">
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

    <?php /* ── Amount Refundable ─────────────────────────────────────────── */ ?>
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
              <option value="CAUTION" <?= ($post['refund_type'] ?? '') === 'CAUTION' ? 'selected' : '' ?>>Caution</option>
            </select>
            <?= $e('refund_type') ?>
          </div>
        </div>
      </div>
    </div>

    <?php /* ── Bank Account Details (standard students only) ─────────────── */ ?>
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

    <?php /* ── Declaration ──────────────────────────────────────────────── */ ?>
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
            <?= isset($post['declarataion_status']) ? 'checked' : '' ?>>
          <p>I declare that the information I have given is true to the best of my knowledge and I understand that making a false claim shall lead to severe disciplinary action as per the University of Nairobi statutes.</p>
        </label>
        <?= $e('declarataion_status') ?>
      </div>
    </div>

    <div style="text-align:center;margin-top:1rem;margin-bottom:2rem;">
      <button type="submit" class="cr-btn cr-btn--primary">Submit Application</button>
    </div>

  </form>

  <?php /* AJAX branch loader */ ?>
  <script>
  function loadBranches(bankId) {
    var sel = document.getElementById('branch_id');
    sel.innerHTML = '<option value="">Loading\u2026</option>';
    if (!bankId) { sel.innerHTML = '<option value="">— Select Branch —</option>'; return; }
    fetch('<?= Url::to(['/caution-refund/branches']) ?>?bank_id=' + bankId)
      .then(function(r){ return r.json(); })
      .then(function(data) {
        sel.innerHTML = '<option value="">— Select Branch —</option>';
        data.forEach(function(b) {
          sel.innerHTML += '<option value="' + b.branch_id + '">' + b.branch_name + '</option>';
        });
      });
  }
  </script>

<?php endif; ?>

</div><!-- /.cr-container -->
</div><!-- /.cr-page -->
