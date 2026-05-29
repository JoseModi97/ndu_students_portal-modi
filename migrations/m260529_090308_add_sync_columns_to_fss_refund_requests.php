<?php

use yii\db\Migration;

class m260529_090308_add_sync_columns_to_fss_refund_requests extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('smisportal.fss_refund_requests', 'sync_status', $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn('smisportal.fss_refund_requests', 'sync_error', $this->text());
        $this->addColumn('smisportal.fss_refund_requests', 'last_synced_at', $this->timestamp());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('smisportal.fss_refund_requests', 'last_synced_at');
        $this->dropColumn('smisportal.fss_refund_requests', 'sync_error');
        $this->dropColumn('smisportal.fss_refund_requests', 'sync_status');
    }
}
