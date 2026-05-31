<?php

use yii\db\Migration;

class m260529_090308_add_sync_columns_to_fss_refund_requests extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        echo "Skipped: sync_status, sync_error, and last_synced_at are not part of the SMIS fss_refund_requests DDL.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "Skipped: no schema change was applied.\n";
    }
}
