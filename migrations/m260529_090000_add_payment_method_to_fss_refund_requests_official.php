<?php

use yii\db\Migration;

/**
 * Class m260529_090000_add_payment_method_to_fss_refund_requests_official
 */
class m260529_090000_add_payment_method_to_fss_refund_requests_official extends Migration
{
    /**
     * @return \yii\db\Connection
     */
    public function getSmisDb()
    {
        return Yii::$app->get('smisDb');
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $smisDb = $this->getSmisDb();

        // Add to official table
        $schemaSmis = $smisDb->getTableSchema('smis.fss_refund_requests');
        if ($schemaSmis && !isset($schemaSmis->columns['payment_method'])) {
            $smisDb->createCommand()->addColumn(
                'smis.fss_refund_requests',
                'payment_method',
                $this->string(20)->notNull()->defaultValue('bank')
            )->execute();
        }

        // Ensure it's in portal table as well (defensive)
        $schemaPortal = $this->db->getTableSchema('smisportal.fss_refund_requests');
        if ($schemaPortal && !isset($schemaPortal->columns['payment_method'])) {
            $this->addColumn(
                'smisportal.fss_refund_requests',
                'payment_method',
                $this->string(20)->notNull()->defaultValue('bank')
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $smisDb = $this->getSmisDb();

        $schemaSmis = $smisDb->getTableSchema('smis.fss_refund_requests');
        if ($schemaSmis && isset($schemaSmis->columns['payment_method'])) {
            $smisDb->createCommand()->dropColumn(
                'smis.fss_refund_requests',
                'payment_method'
            )->execute();
        }

        $schemaPortal = $this->db->getTableSchema('smisportal.fss_refund_requests');
        if ($schemaPortal && isset($schemaPortal->columns['payment_method'])) {
            $this->dropColumn(
                'smisportal.fss_refund_requests',
                'payment_method'
            );
        }
    }
}
