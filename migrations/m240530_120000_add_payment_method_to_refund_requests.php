<?php

use yii\db\Migration;

/**
 * Class m240530_120000_add_payment_method_to_refund_requests
 */
class m240530_120000_add_payment_method_to_refund_requests extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Add payment_method to smisportal.fss_refund_requests
        $this->addColumn('smisportal.fss_refund_requests', 'payment_method', $this->string(20)->notNull()->defaultValue('bank'));

        // Add payment_method to smis.fss_refund_requests
        $this->db->createCommand()->addColumn('smis.fss_refund_requests', 'payment_method', 'VARCHAR(20) NOT NULL DEFAULT \'bank\'')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('smisportal.fss_refund_requests', 'payment_method');
        $this->db->createCommand()->dropColumn('smis.fss_refund_requests', 'payment_method')->execute();
    }
}
