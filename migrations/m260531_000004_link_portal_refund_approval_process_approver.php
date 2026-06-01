<?php

use yii\db\Migration;

/**
 * Ensures the portal refund approval process is linked to approvers through
 * approver_id, matching the SMIS fss_refund_approval_process relationship.
 */
class m260531_000004_link_portal_refund_approval_process_approver extends Migration
{
    public function safeUp()
    {
        $orphanCount = (int)$this->db->createCommand(<<<'SQL'
SELECT COUNT(*)
FROM smisportal.fss_refund_approval_process p
LEFT JOIN smisportal.fss_refund_approvers a ON a.approver_id = p.approver_id
WHERE p.approver_id IS NOT NULL
  AND a.approver_id IS NULL
SQL)->queryScalar();

        if ($orphanCount > 0) {
            throw new RuntimeException(
                "Cannot add fk_approver_id: {$orphanCount} approval-process row(s) reference missing approver_id values."
            );
        }

        $this->execute('ALTER TABLE smisportal.fss_refund_approval_process DROP CONSTRAINT IF EXISTS fk_fss_refund_approval_process_approver_id');
        $this->execute('ALTER TABLE smisportal.fss_refund_approval_process DROP CONSTRAINT IF EXISTS fk_approver_id');

        $this->addForeignKey(
            'fk_approver_id',
            'smisportal.fss_refund_approval_process',
            'approver_id',
            'smisportal.fss_refund_approvers',
            'approver_id'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_approver_id', 'smisportal.fss_refund_approval_process');
    }
}
