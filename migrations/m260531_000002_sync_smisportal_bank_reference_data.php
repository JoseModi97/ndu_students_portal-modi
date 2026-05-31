<?php

use yii\db\Migration;

/**
 * Copies SMIS bank and branch reference data into smisportal without truncating
 * existing portal tables.
 */
class m260531_000002_sync_smisportal_bank_reference_data extends Migration
{
    public function safeUp()
    {
        $smisDb = Yii::$app->get('smisDb');
        $portalDb = $this->db;

        $this->syncBanks($smisDb, $portalDb);
        $this->syncBranches($smisDb, $portalDb);
        $this->resetIdentity('fss_banks', 'brank_id');
        $this->resetIdentity('fss_bank_branches', 'branch_id');
    }

    public function safeDown()
    {
        echo "m260531_000002_sync_smisportal_bank_reference_data is non-destructive and cannot be reverted safely.\n";
        return false;
    }

    private function syncBanks(\yii\db\Connection $smisDb, \yii\db\Connection $portalDb): void
    {
        $banks = (new yii\db\Query())
            ->select(['bank_code', 'bank_name', 'brank_id', 'status'])
            ->from('smis.fss_banks')
            ->orderBy(['brank_id' => SORT_ASC])
            ->all($smisDb);

        foreach ($banks as $bank) {
            $exists = (new yii\db\Query())
                ->from('smisportal.fss_banks')
                ->where(['brank_id' => $bank['brank_id']])
                ->exists($portalDb);

            if ($exists) {
                $portalDb->createCommand()
                    ->update('smisportal.fss_banks', [
                        'bank_code' => $bank['bank_code'],
                        'bank_name' => $bank['bank_name'],
                        'status' => $bank['status'],
                    ], ['brank_id' => $bank['brank_id']])
                    ->execute();
            } else {
                $portalDb->createCommand(
                    'INSERT INTO smisportal.fss_banks (bank_code, bank_name, brank_id, status) OVERRIDING SYSTEM VALUE VALUES (:bank_code, :bank_name, :brank_id, :status)',
                    $bank
                )->execute();
            }
        }
    }

    private function syncBranches(\yii\db\Connection $smisDb, \yii\db\Connection $portalDb): void
    {
        $branches = (new yii\db\Query())
            ->select(['branch_code', 'branch_name', 'bank_code', 'branch_id'])
            ->from('smis.fss_bank_branches')
            ->orderBy(['branch_id' => SORT_ASC])
            ->all($smisDb);

        foreach ($branches as $branch) {
            $exists = (new yii\db\Query())
                ->from('smisportal.fss_bank_branches')
                ->where(['branch_id' => $branch['branch_id']])
                ->exists($portalDb);

            if ($exists) {
                $portalDb->createCommand()
                    ->update('smisportal.fss_bank_branches', [
                        'branch_code' => $branch['branch_code'],
                        'branch_name' => $branch['branch_name'],
                        'bank_code' => $branch['bank_code'],
                    ], ['branch_id' => $branch['branch_id']])
                    ->execute();
            } else {
                $portalDb->createCommand(
                    'INSERT INTO smisportal.fss_bank_branches (branch_code, branch_name, bank_code, branch_id) OVERRIDING SYSTEM VALUE VALUES (:branch_code, :branch_name, :bank_code, :branch_id)',
                    $branch
                )->execute();
            }
        }
    }

    private function resetIdentity(string $table, string $pk): void
    {
        $maxId = (new yii\db\Query())
            ->select("MAX($pk)")
            ->from("smisportal.$table")
            ->scalar($this->db);

        if ($maxId) {
            $nextVal = (int)$maxId + 1;
            $this->db->createCommand("ALTER TABLE smisportal.$table ALTER COLUMN $pk RESTART WITH $nextVal")->execute();
        }
    }
}
