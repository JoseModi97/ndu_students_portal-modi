<?php

namespace app\modules\Registration\services;

use Yii;
use yii\db\Connection;
use yii\db\Query;
use app\models\Students;

class DocumentService
{
    /** @var Connection MySQL (smis schema) */
    private Connection $db;

    /** @var Connection Oracle (MUTHONI schema) */
    private Connection $oracleDb;

    public function __construct()
    {
        $this->db       = Yii::$app->db;
        $this->oracleDb = Yii::$app->dbOracle;
    }

    public function getIdentity(): Students
    {
        $identity = Yii::$app->user->identity;

        if (!$identity instanceof Students) {
            throw new \yii\web\ForbiddenHttpException('Authentication required.');
        }

        return $identity;
    }
    public function getRegNo(): string
    {
        return (string) $this->getIdentity()->registration_number;
    }

    public function getCategoryCode(): string
    {
        return (string) $this->getIdentity()->category_code;
    }

    public function getStudentUploadStatus(): array
    {
        $row = (new Query())
            ->select(['reporting_intake', 'document_submit_status', 'document_check'])
            ->from('smis.students')
            ->where(['registration_number' => $this->getRegNo()])
            ->one($this->db);

        return array_merge($row ?: [], [
            'registration_number' => $this->getRegNo(),
            'category_code'       => $this->getCategoryCode(),
        ]);
    }
    //     public function getStudentUploadStatus(): array
    // {
    //     // ---- SIMULATION BLOCK — remove before production ----
    //     return [
    //         'reporting_intake'       => 1,       // 1 = first year, anything else = continuing
    //         'document_submit_status' => 'NO',    // 'NO' = new, 'YES' = submitted, 'REVIEW' = re-upload
    //         'document_check'         => 'PENDING', // 'CLEARED' = verified
    //         'registration_number'    => $this->getRegNo(),
    //         'category_code'          => $this->getCategoryCode(),
    //     ];
    //     // ---- END SIMULATION BLOCK ----
    // }
    public function markDocumentsSubmitted(): void
    {
        $this->db->createCommand(
            'UPDATE smis.students
             SET document_submit_status = :status,
                 conduct_status         = :conduct
             WHERE registration_number  = :regNo'
        )->bindValues([
            ':status'  => 'YES',
            ':conduct' => 'ACCEPTED',
            ':regNo'   => $this->getRegNo(),
        ])->execute();
    }

    public function getStudentBalance(): ?int
    {
        $row = $this->oracleDb->createCommand(
            'SELECT STATUTORY_FEES, PAID_FEES
             FROM MUTHONI.VW_FIRST_YEAR_BALANCES
             WHERE REGISTRATION_NUMBER = :regNo'
        )->bindValue(':regNo', $this->getRegNo())->queryOne();

        if (!$row) {
            return null;
        }

        return (int) $row['PAID_FEES'] - (int) $row['STATUTORY_FEES'];
    }
    public function countRequiredDocuments(): int
    {
        return (int) (new Query())
            ->from('smis.required_documents')
            ->where([
                'fk_category_id'         => $this->getCategoryCode(),
                'required_doc_is_active' => 'Y',
            ])
            ->count('*', $this->db);
    }

    public function countCompulsoryDocuments(): int
    {
        return (int) (new Query())
            ->from('smis.required_documents rd')
            ->innerJoin('smis.documents d', 'd.document_id = rd.fk_document_id')
            ->where([
                'rd.fk_category_id'        => $this->getCategoryCode(),
                'rd.required_doc_is_active' => 'Y',
                'd.document_required'       => 'Y',
            ])
            ->count('*', $this->db);
    }

    public function getRequiredDocuments(string $submitStatus): array
    {
        $submittedCount = $this->countSubmittedDocuments();

        $query = (new Query())
            ->select([
                'rd.*',
                'd.document_name',
                'd.document_type',
                'd.document_desc',
                'd.document_required',
            ])
            ->from('smis.required_documents rd')
            ->innerJoin('smis.documents d', 'd.document_id = rd.fk_document_id')
            ->where([
                'rd.fk_category_id'        => $this->getCategoryCode(),
                'rd.required_doc_is_active' => 'Y',
            ]);

        if ($submitStatus === 'REVIEW' && $submittedCount > 0) {
            $uploaded = $this->getSubmittedRequiredIds(['VALID', 'UNKNOWN']);
            if (!empty($uploaded)) {
                $query->andWhere(['not in', 'rd.required_document_id', $uploaded]);
            }
        } elseif ($submittedCount > 0) {
            // was: $this->getSubmittedRequiredIds() — no filter, excluded INVALID docs too
            $uploaded = $this->getSubmittedRequiredIds(['VALID', 'UNKNOWN']);
            if (!empty($uploaded)) {
                $query->andWhere(['not in', 'rd.required_document_id', $uploaded]);
            }
        }

        return $query->all($this->db);
    }

    private function getSubmittedRequiredIds(array $statuses = []): array
    {
        $query = (new Query())
            ->select('fk_required_doc_id')
            ->from('smis.student_submitted_documents')
            ->where(['fk_registration_number' => $this->getRegNo()]);

        if (!empty($statuses)) {
            $query->andWhere(['in', 'verify_status', $statuses]);
        }

        return $query->column($this->db);
    }
    public function countSubmittedDocuments(): int
    {
        return (int) (new Query())
            ->from('smis.student_submitted_documents')
            ->where(['fk_registration_number' => $this->getRegNo()])
            ->andWhere(['in', 'verify_status', ['UNKNOWN', 'VALID']])
            ->count('*', $this->db);
    }

    public function getSubmittedDocuments(): array
    {
        return (new Query())
            ->select([
                'ssd.student_document_id',
                'ssd.document_path',
                'ssd.upload_date',
                'ssd.verify_status',
                'd.document_name',
            ])
            ->from('smis.student_submitted_documents ssd')
            ->innerJoin('smis.required_documents rd', 'rd.required_document_id = ssd.fk_required_doc_id')
            ->innerJoin('smis.documents d', 'd.document_id = rd.fk_document_id')
            ->where(['ssd.fk_registration_number' => $this->getRegNo()])
            ->andWhere(['in', 'ssd.verify_status', ['UNKNOWN', 'VALID']])
            ->all($this->db);
    }

    /**
     * Upsert a submitted document record for the current student.
     * Updates path + resets status to UNKNOWN if record exists, inserts otherwise.
     */
    public function saveSubmittedDocument(int $requiredDocId, string $path): void
    {
        $regNo = $this->getRegNo();

        $exists = (new Query())
            ->from('smis.student_submitted_documents')
            ->where([
                'fk_registration_number' => $regNo,
                'fk_required_doc_id'     => $requiredDocId,
            ])
            ->exists($this->db);

        if ($exists) {
            $this->db->createCommand(
                'UPDATE smis.student_submitted_documents
                 SET document_path  = :path,
                     upload_date    = :date,
                     verify_status  = :status
                 WHERE fk_registration_number = :regNo
                   AND fk_required_doc_id     = :docId'
            )->bindValues([
                ':path'   => $path,
                ':date'   => date('Y-m-d'),
                ':status' => 'UNKNOWN',
                ':regNo'  => $regNo,
                ':docId'  => $requiredDocId,
            ])->execute();
        } else {
            $this->db->createCommand(
                'INSERT INTO smis.student_submitted_documents
                     (fk_registration_number, fk_required_doc_id, document_path, upload_date)
                 VALUES
                     (:regNo, :docId, :path, :date)'
            )->bindValues([
                ':regNo' => $regNo,
                ':docId' => $requiredDocId,
                ':path'  => $path,
                ':date'  => date('Y-m-d'),
            ])->execute();
        }
    }
    public function invalidateDocument(int $studentDocumentId): bool
    {
        $rows = $this->db->createCommand(
            'UPDATE smis.student_submitted_documents
             SET verify_status = :status
             WHERE student_document_id    = :docId
               AND fk_registration_number = :regNo'
        )->bindValues([
            ':status' => 'INVALID',
            ':docId'  => $studentDocumentId,
            ':regNo'  => $this->getRegNo(),
        ])->execute();

        return $rows > 0;
    }
}
