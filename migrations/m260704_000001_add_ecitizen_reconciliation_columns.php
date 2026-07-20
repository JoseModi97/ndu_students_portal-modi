<?php

use yii\db\Migration;

class m260704_000001_add_ecitizen_reconciliation_columns extends Migration
{
    public function safeUp()
    {
        $table = 'smisportal.ecitizen';
        $schema = $this->db->schema->getTableSchema($table, true);
        $columns = $schema ? $schema->columns : [];

        $definitions = [
            'remote_status' => 'varchar(32) DEFAULT NULL',
            'reconciliation_status' => "varchar(20) NOT NULL DEFAULT 'unchecked'",
            'last_status_checked_at' => 'timestamp without time zone DEFAULT NULL',
            'status_check_error' => 'text DEFAULT NULL',
            'last_status_response' => 'text DEFAULT NULL',
            'reversal_detected_at' => 'timestamp without time zone DEFAULT NULL',
        ];

        foreach ($definitions as $column => $definition) {
            if (!isset($columns[$column])) {
                $this->execute("ALTER TABLE {$table} ADD COLUMN {$column} {$definition}");
            }
        }

        $this->execute(
            'CREATE INDEX IF NOT EXISTS idx_ecitizen_reconciliation_due
                ON smisportal.ecitizen (status, last_status_checked_at)
             WHERE status = \'Settled\''
        );
        $this->execute(
            'CREATE INDEX IF NOT EXISTS idx_ecitizen_reconciliation_review
                ON smisportal.ecitizen (reconciliation_status, reversal_detected_at)
             WHERE reconciliation_status = \'review_required\''
        );
    }

    public function safeDown()
    {
        $this->execute('DROP INDEX IF EXISTS smisportal.idx_ecitizen_reconciliation_review');
        $this->execute('DROP INDEX IF EXISTS smisportal.idx_ecitizen_reconciliation_due');

        foreach ([
            'reversal_detected_at',
            'last_status_response',
            'status_check_error',
            'last_status_checked_at',
            'reconciliation_status',
            'remote_status',
        ] as $column) {
            $this->execute("ALTER TABLE smisportal.ecitizen DROP COLUMN IF EXISTS {$column}");
        }
    }
}
