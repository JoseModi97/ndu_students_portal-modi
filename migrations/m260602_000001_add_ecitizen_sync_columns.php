<?php

use yii\db\Migration;

class m260602_000001_add_ecitizen_sync_columns extends Migration
{
    public function safeUp()
    {
        $this->addColumns(Yii::$app->db, 'smisportal.ecitizen');
        $this->createIndex(
            'idx_ecitizen_sync_status',
            'smisportal.ecitizen',
            ['sync_status', 'status']
        );
    }

    public function safeDown()
    {
        $this->dropIndex('idx_ecitizen_sync_status', 'smisportal.ecitizen');
        $this->dropColumns(Yii::$app->db, 'smisportal.ecitizen');
    }

    private function addColumns(\yii\db\Connection $db, string $table): void
    {
        $schema = $db->schema->getTableSchema($table, true);
        $columns = $schema ? $schema->columns : [];

        $definitions = [
            'paid_amount' => 'numeric(10, 2) DEFAULT NULL',
            'payment_date' => 'date DEFAULT NULL',
            'gateway_reference' => 'varchar(100) DEFAULT NULL',
            'synced_trans_id' => 'bigint DEFAULT NULL',
            'sync_status' => 'integer NOT NULL DEFAULT 0',
            'sync_error' => 'text DEFAULT NULL',
            'last_synced_at' => 'timestamp without time zone DEFAULT NULL',
        ];

        foreach ($definitions as $column => $definition) {
            if (!isset($columns[$column])) {
                $db->createCommand("ALTER TABLE {$table} ADD COLUMN {$column} {$definition}")->execute();
            }
        }
    }

    private function dropColumns(\yii\db\Connection $db, string $table): void
    {
        foreach (['last_synced_at', 'sync_error', 'sync_status', 'synced_trans_id', 'gateway_reference', 'payment_date', 'paid_amount'] as $column) {
            $db->createCommand("ALTER TABLE {$table} DROP COLUMN IF EXISTS {$column}")->execute();
        }
    }
}
