<?php

use yii\db\Migration;

/**
 * smisportal.fss_fee_transactions.trans_id has fallen behind the highest
 * trans_id actually stored in the table: PaymentService::
 * ensureFeeTransactionDescription() (the console eCitizen-sync flow) inserts
 * rows with an explicit trans_id borrowed from the banking slip's own
 * primary key, which never advances this table's own auto-increment
 * sequence. The web "Complete Payment" flow (PaymentService::
 * creditPortalFeeStatement()) inserts without specifying trans_id, relying
 * on the sequence, and eventually collides with an already-used value
 * (unique violation on fss_fee_transactions_pkey). This is a one-time bump
 * to clear the current backlog; PaymentService::advanceFeeTransactionSequence()
 * keeps it from recurring going forward.
 */
class m260820_000001_reconcile_fee_transactions_sequence extends Migration
{
    public function safeUp()
    {
        $seq = $this->db->createCommand(
            "SELECT pg_get_serial_sequence('smisportal.fss_fee_transactions', 'trans_id')"
        )->queryScalar();
        if (empty($seq)) {
            return;
        }

        $maxTransId = (int) $this->db
            ->createCommand('SELECT COALESCE(MAX(trans_id), 0) FROM smisportal.fss_fee_transactions')
            ->queryScalar();
        if ($maxTransId <= 0) {
            return;
        }

        $this->execute("SELECT setval('{$seq}', {$maxTransId}, true)");
    }

    public function safeDown()
    {
        echo "m260820_000001_reconcile_fee_transactions_sequence cannot be reverted (sequence value is not undoable).\n";
        return false;
    }
}
