<?php

use yii\db\Migration;

/**
 * Class m260529_025457_sync_data_from_smis_to_portal
 */
class m260529_025457_sync_data_from_smis_to_portal extends Migration
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
        $portalDb = $this->db;

        $tables = [
            'fss_banks' => 'brank_id',
            'fss_bank_branches' => 'branch_id',
            'fss_refund_types' => 'refund_type_id',
            '"fss_refund_approval levels"' => 'approval_level_id',
            'fss_refund_approvers' => 'approver_id',
            'fss_refund_requests' => 'request_id',
            'fss_refund_approval_process' => 'approval_process_id',
        ];

        foreach ($tables as $table => $pk) {
            echo "Syncing $table...\n";
            
            // Check if identity
            $isIdentity = in_array($pk, ['brank_id', 'branch_id']);

            // Clear portal table (safely)
            $portalDb->createCommand("TRUNCATE TABLE smisportal.$table CASCADE")->execute();

            // Fetch from SMIS
            $data = (new \yii\db\Query())
                ->from("smis.$table")
                ->all($smisDb);

            if (!empty($data)) {
                foreach ($data as $row) {
                    $columnNames = implode(', ', array_keys($row));
                    $placeholders = [];
                    foreach ($row as $k => $v) { $placeholders[] = ":" . $k; }
                    $placeholderStr = implode(', ', $placeholders);
                    
                    $sql = "INSERT INTO smisportal.$table ($columnNames) ";
                    if ($isIdentity) { $sql .= "OVERRIDING SYSTEM VALUE "; }
                    $sql .= "VALUES ($placeholderStr)";
                    
                    $portalDb->createCommand($sql, $row)->execute();
                }
            }

            // Reset sequence for identity columns
            if ($isIdentity) {
                $maxId = (new \yii\db\Query())->select("MAX($pk)")->from("smisportal.$table")->scalar($portalDb);
                if ($maxId) {
                    $nextVal = $maxId + 1;
                    $portalDb->createCommand("ALTER TABLE smisportal.$table ALTER COLUMN $pk RESTART WITH $nextVal")->execute();
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
