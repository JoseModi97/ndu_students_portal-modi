<?php

use yii\db\Migration;

/**
 * Class m260524_000001_create_caution_refund_tables_portal
 */
class m260524_000001_create_caution_refund_tables_portal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('smisportal.sm_bank', [
            'bank_id' => $this->primaryKey(),
            'bank_name' => $this->string(100)->notNull(),
            'bank_code' => $this->string(20),
        ]);

        $this->createTable('smisportal.sm_bank_branch', [
            'branch_id' => $this->primaryKey(),
            'bank_id' => $this->integer()->notNull(),
            'branch_name' => $this->string(100)->notNull(),
            'branch_code' => $this->string(20),
        ]);

        $this->addForeignKey(
            'fk-sm_bank_branch-bank_id',
            'smisportal.sm_bank_branch',
            'bank_id',
            'smisportal.sm_bank',
            'bank_id',
            'CASCADE'
        );

        $this->createTable('smisportal.sm_caution_refund', [
            'refund_id' => $this->primaryKey(),
            'student_id' => $this->integer()->notNull(),
            'registration_number' => $this->string(20)->notNull(),
            'bank_branch_id' => $this->integer(),
            'account_number' => $this->string(50),
            'mobile_number' => $this->string(20),
            'refund_amount' => $this->decimal(12, 2),
            'status' => $this->string(20)->defaultValue('PENDING'),
            'application_date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'remarks' => $this->text(),
        ]);

        $this->addForeignKey(
            'fk-sm_caution_refund-bank_branch_id',
            'smisportal.sm_caution_refund',
            'bank_branch_id',
            'smisportal.sm_bank_branch',
            'branch_id',
            'SET NULL'
        );
        
        // Seed some data for banks
        $this->batchInsert('smisportal.sm_bank', ['bank_name', 'bank_code'], [
            ['KCB Bank', 'KCB'],
            ['Equity Bank', 'EQUITY'],
            ['Co-operative Bank', 'COOP'],
            ['Absa Bank', 'ABSA'],
            ['Safaricom (M-PESA)', 'MPESA'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-sm_caution_refund-bank_branch_id', 'smisportal.sm_caution_refund');
        $this->dropTable('smisportal.sm_caution_refund');
        $this->dropForeignKey('fk-sm_bank_branch-bank_id', 'smisportal.sm_bank_branch');
        $this->dropTable('smisportal.sm_bank_branch');
        $this->dropTable('smisportal.sm_bank');
    }
}
