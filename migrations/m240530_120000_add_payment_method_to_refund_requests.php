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
        $this->addColumn(
            'smisportal.fss_refund_requests',
            'payment_method',
            $this->string(20)->notNull()->defaultValue('bank')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(
            'smisportal.fss_refund_requests',
            'payment_method'
        );
    }
}