<?php

use yii\db\Migration;

/**
 * Class m260524_000002_create_caution_refund_tables_smis
 */
class m260524_000002_create_caution_refund_tables_smis extends Migration
{
    /**
     * @return string
     */
    public function getDb()
    {
        return Yii::$app->get('smisDb');
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('smis.sm_caution_refund_official', [
            'refund_official_id' => $this->primaryKey(),
            'registration_number' => $this->string(20)->notNull(),
            'amount' => $this->decimal(12, 2),
            'status' => $this->string(20)->defaultValue('PENDING'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createTable('smis.sm_approval_level', [
            'level_id' => $this->primaryKey(),
            'level_name' => $this->string(50)->notNull(),
            'level_order' => $this->integer()->notNull(),
        ]);

        $this->createTable('smis.sm_approval_process', [
            'process_id' => $this->primaryKey(),
            'refund_official_id' => $this->integer()->notNull(),
            'level_id' => $this->integer()->notNull(),
            'status' => $this->string(20)->notNull(), // APPROVED, REJECTED, PENDING
            'comments' => $this->text(),
            'approver_id' => $this->integer(),
            'approval_date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-sm_approval_process-refund_official_id',
            'smis.sm_approval_process',
            'refund_official_id',
            'smis.sm_caution_refund_official',
            'refund_official_id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-sm_approval_process-level_id',
            'smis.sm_approval_process',
            'level_id',
            'smis.sm_approval_level',
            'level_id',
            'CASCADE'
        );

        // Seed approval levels
        $this->batchInsert('smis.sm_approval_level', ['level_name', 'level_order'], [
            ['Dean of Students', 1],
            ['Finance Office', 2],
            ['Audit Office', 3],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-sm_approval_process-level_id', 'smis.sm_approval_process');
        $this->dropForeignKey('fk-sm_approval_process-refund_official_id', 'smis.sm_approval_process');
        $this->dropTable('smis.sm_approval_process');
        $this->dropTable('smis.sm_approval_level');
        $this->dropTable('smis.sm_caution_refund_official');
    }
}
