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
        echo "Skipped: payment_method is not part of the SMIS fss_refund_requests DDL.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "Skipped: no schema change was applied.\n";
    }
}
