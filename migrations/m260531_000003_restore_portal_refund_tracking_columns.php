<?php

use yii\db\Migration;

/**
 * Restores portal-only refund request tracking columns.
 */
class m260531_000003_restore_portal_refund_tracking_columns extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            'smisportal.fss_refund_requests',
            'payment_method',
            $this->string(20)->notNull()->defaultValue('bank')
        );
        $this->addColumn(
            'smisportal.fss_refund_requests',
            'sync_status',
            $this->smallInteger()->notNull()->defaultValue(0)
        );
        $this->addColumn('smisportal.fss_refund_requests', 'sync_error', $this->text());
        $this->addColumn('smisportal.fss_refund_requests', 'last_synced_at', $this->timestamp());
    }

    public function safeDown()
    {
        $this->dropColumn('smisportal.fss_refund_requests', 'last_synced_at');
        $this->dropColumn('smisportal.fss_refund_requests', 'sync_error');
        $this->dropColumn('smisportal.fss_refund_requests', 'sync_status');
        $this->dropColumn('smisportal.fss_refund_requests', 'payment_method');
    }
}
