<?php

use yii\db\Migration;

/**
 * Class m260529_065832_add_payment_method_to_refund_requests
 */
class m260529_065832_add_payment_method_to_refund_requests extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Add payment_method to Portal table (uses default db connection)
        $this->addColumn('smisportal.fss_refund_requests', 'payment_method', $this->string(50)->notNull()->defaultValue('BANK'));

        // Add payment_method to Official SMIS table (uses smisDb connection)
        $smisDb = Yii::$app->get('smisDb');
        $smisDb->createCommand("ALTER TABLE smis.fss_refund_requests ADD COLUMN payment_method VARCHAR(50) NOT NULL DEFAULT 'BANK'")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('smisportal.fss_refund_requests', 'payment_method');

        $smisDb = Yii::$app->get('smisDb');
        $smisDb->createCommand("ALTER TABLE smis.fss_refund_requests DROP COLUMN payment_method")->execute();
    }
}
