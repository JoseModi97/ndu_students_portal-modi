<?php

use yii\db\Migration;

/**
 * Class m260529_024348_create_fss_refund_tables_portal
 */
class m260529_024348_create_fss_refund_tables_portal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Drop existing if requested
        $this->execute('DROP TABLE IF EXISTS smisportal.fss_refund_requests CASCADE');
        $this->execute('DROP TABLE IF EXISTS smisportal.fss_refund_approval_process CASCADE');
        $this->execute('DROP TABLE IF EXISTS smisportal.fss_refund_approvers CASCADE');
        $this->execute('DROP TABLE IF EXISTS smisportal."fss_refund_approval levels" CASCADE');
        $this->execute('DROP TABLE IF EXISTS smisportal.fss_refund_types CASCADE');
        $this->execute('DROP TABLE IF EXISTS smisportal.fss_bank_branches CASCADE');
        $this->execute('DROP TABLE IF EXISTS smisportal.fss_banks CASCADE');

        // Create Tables in smisportal schema
        
        $this->createTable('smisportal.fss_banks', [
            'brank_id' => $this->getDb()->getSchema()->createColumnSchemaBuilder('int4 GENERATED ALWAYS AS IDENTITY'),
            'bank_code' => $this->string(40),
            'bank_name' => $this->string(255),
            'status' => $this->integer()->defaultValue(1),
            'PRIMARY KEY (brank_id)'
        ]);

        $this->createTable('smisportal.fss_bank_branches', [
            'branch_id' => $this->getDb()->getSchema()->createColumnSchemaBuilder('int4 GENERATED ALWAYS AS IDENTITY'),
            'branch_code' => $this->string(40),
            'branch_name' => $this->string(255),
            'bank_code' => $this->string(40),
            'PRIMARY KEY (branch_id)'
        ]);

        $this->createTable('smisportal.fss_refund_types', [
            'refund_type_id' => $this->integer()->notNull(),
            'refund_type_name' => $this->string()->notNull(),
            'refund_type_status' => $this->boolean()->notNull(),
            'PRIMARY KEY (refund_type_id)'
        ]);

        $this->createTable('smisportal."fss_refund_approval levels"', [
            'approval_level_id' => $this->integer()->notNull(),
            'approval_order' => $this->string(30)->notNull(),
            'description' => $this->string(150)->notNull(),
            'PRIMARY KEY (approval_level_id)'
        ]);

        $this->createTable('smisportal.fss_refund_approvers', [
            'approver_id' => $this->integer()->notNull(),
            'user_id' => $this->string(30)->notNull(),
            'approval_level_id' => $this->integer()->notNull(),
            'approver_status' => $this->string()->notNull(),
            'PRIMARY KEY (approver_id)'
        ]);

        $this->addForeignKey(
            'fk_fss_refund_approvers_approval_level_id',
            'smisportal.fss_refund_approvers',
            'approval_level_id',
            'smisportal."fss_refund_approval levels"',
            'approval_level_id',
            'CASCADE'
        );

        // We check if sm_student_programme_curriculum exists in smisportal
        // Based on models/StudentProgCurriculum.php, it does.
        // User DDL provided a full CREATE TABLE for it.
        // "only the tables that doe not exist in smisportal side"
        // I will use execute with IF NOT EXISTS if possible, or just skip if it exists.
        
        $tableSchema = $this->db->getTableSchema('smisportal.sm_student_programme_curriculum');
        if ($tableSchema === null) {
            $this->createTable('smisportal.sm_student_programme_curriculum', [
                'student_prog_curriculum_id' => $this->getDb()->getSchema()->createColumnSchemaBuilder('int8 GENERATED ALWAYS AS IDENTITY'),
                'student_id' => $this->bigInteger()->notNull(),
                'registration_number' => $this->string(20)->notNull(),
                'prog_curriculum_id' => $this->bigInteger()->notNull(),
                'student_category_id' => $this->integer()->notNull(),
                'adm_refno' => $this->integer()->notNull(),
                'status_id' => $this->integer()->notNull(),
                'userid' => $this->string(20),
                'ip_address' => $this->string(50),
                'user_action' => $this->string(10),
                'last_update' => $this->timestamp(),
                'PRIMARY KEY (student_prog_curriculum_id)'
            ]);
        }

        $this->createTable('smisportal.fss_refund_requests', [
            'request_id' => $this->bigInteger()->notNull(),
            'student_prog_curriculum_id' => $this->bigInteger()->notNull(),
            'mobile_no' => $this->string(20)->notNull(),
            'email' => $this->string(100)->notNull(),
            'application_date' => $this->timestamp()->notNull(),
            'refund_status' => $this->string(30)->notNull(),
            'account_no' => $this->string(50),
            'account_name' => $this->string(120),
            'bank_id' => $this->integer(),
            'branch_id' => $this->integer(),
            'passport_id' => $this->string(30)->notNull(),
            'declaration_status' => $this->string(3)->notNull(),
            'amount_requested' => $this->decimal(12, 2)->notNull(),
            'approval_status' => $this->string(50)->notNull(),
            'voucher_no' => $this->bigInteger(),
            'amount_approved' => $this->decimal(12, 2),
            'refund_type' => $this->integer()->notNull(),
            'PRIMARY KEY (request_id)'
        ]);

        $this->addForeignKey(
            'fk_fss_refund_requests_bank_id',
            'smisportal.fss_refund_requests',
            'bank_id',
            'smisportal.fss_banks',
            'brank_id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk_fss_refund_requests_branch_id',
            'smisportal.fss_refund_requests',
            'branch_id',
            'smisportal.fss_bank_branches',
            'branch_id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk_fss_refund_requests_refund_type',
            'smisportal.fss_refund_requests',
            'refund_type',
            'smisportal.fss_refund_types',
            'refund_type_id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_fss_refund_requests_student_prog_curr',
            'smisportal.fss_refund_requests',
            'student_prog_curriculum_id',
            'smisportal.sm_student_programme_curriculum',
            'student_prog_curriculum_id',
            'CASCADE'
        );

        $this->createTable('smisportal.fss_refund_approval_process', [
            'approval_process_id' => $this->bigInteger()->notNull(),
            'request_id' => $this->bigInteger()->notNull(),
            'approval_status' => $this->string(30)->notNull(),
            'remarks' => $this->string(150),
            'approval_date' => $this->timestamp()->notNull(),
            'approver_id' => $this->integer()->notNull(),
            'PRIMARY KEY (approval_process_id)'
        ]);

        $this->addForeignKey(
            'fk_fss_refund_approval_process_request_id',
            'smisportal.fss_refund_approval_process',
            'request_id',
            'smisportal.fss_refund_requests',
            'request_id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_fss_refund_approval_process_approver_id',
            'smisportal.fss_refund_approval_process',
            'approver_id',
            'smisportal.fss_refund_approvers',
            'approver_id',
            'CASCADE'
        );

        // Seed some basic data if needed
        $this->batchInsert('smisportal.fss_refund_types', ['refund_type_id', 'refund_type_name', 'refund_type_status'], [
            [1, 'STANDARD', true],
            [2, 'CHSS', true],
        ]);
        
        $this->batchInsert('smisportal."fss_refund_approval levels"', ['approval_level_id', 'approval_order', 'description'], [
            [1, '1', 'Dean of Students'],
            [2, '2', 'Finance Office'],
            [3, '3', 'Audit Office'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('smisportal.fss_refund_approval_process');
        $this->dropTable('smisportal.fss_refund_requests');
        $this->dropTable('smisportal.fss_refund_approvers');
        $this->dropTable('smisportal."fss_refund_approval levels"');
        $this->dropTable('smisportal.fss_refund_types');
        $this->dropTable('smisportal.fss_bank_branches');
        $this->dropTable('smisportal.fss_banks');
    }
}
