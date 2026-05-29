<?php

use yii\db\Migration;

/**
 * Class m260529_030100_reset_sequences_portal
 */
class m260529_030100_reset_sequences_portal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $db = $this->db;
        $tables = [
            'fss_banks' => 'brank_id',
            'fss_bank_branches' => 'branch_id',
            'sm_student_programme_curriculum' => 'student_prog_curriculum_id',
        ];

        foreach ($tables as $table => $pk) {
            $maxId = (new \yii\db\Query())->select("MAX($pk)")->from("smisportal.$table")->scalar($db);
            if ($maxId) {
                $nextVal = $maxId + 1;
                echo "Resetting sequence for $table to $nextVal...\n";
                $db->createCommand("ALTER TABLE smisportal.$table ALTER COLUMN $pk RESTART WITH $nextVal")->execute();
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
