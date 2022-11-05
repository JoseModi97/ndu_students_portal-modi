<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use JetBrains\PhpStorm\ArrayShape;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_stud_submitted_document".
 *
 * @property int $student_document_id
 * @property int $required_document_id
 * @property string $document_path
 * @property string|null $ip_address
 * @property string $upload_date
 * @property string $verify_status
 * @property string|null $doc_comments
 * @property int $adm_refno
 */
class SubmittedDocument extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_stud_submitted_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['required_document_id', 'document_path', 'upload_date', 'verify_status', 'adm_refno'], 'required'],
            [['required_document_id', 'adm_refno'], 'default', 'value' => null],
            [['required_document_id', 'adm_refno'], 'integer'],
            [['upload_date'], 'safe'],
            [['document_path', 'doc_comments'], 'string', 'max' => 100],
            [['ip_address'], 'string', 'max' => 60],
            [['verify_status'], 'string', 'max' => 20],
            [['adm_refno'], 'exist', 'skipOnError' => true, 'targetClass' => AdmittedStudent::class,
                'targetAttribute' => ['adm_refno' => 'adm_refno']],
            [['required_document_id'], 'exist', 'skipOnError' => true, 'targetClass' => RequiredDocument::class,
                'targetAttribute' => ['required_document_id' => 'required_document_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['student_document_id' => "string", 'required_document_id' => "string", 'document_path' => "string",
        'ip_address' => "string", 'upload_date' => "string", 'verify_status' => "string", 'doc_comments' => "string",
        'adm_refno' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'student_document_id' => 'Student Document ID',
            'required_document_id' => 'Required Document ID',
            'document_path' => 'Document Path',
            'ip_address' => 'Ip Address',
            'upload_date' => 'Upload Date',
            'verify_status' => 'Verify Status',
            'doc_comments' => 'Doc Comments',
            'adm_refno' => 'Adm Refno',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAdmittedStudent(): ActiveQuery
    {
        return $this->hasOne(AdmittedStudent::class, ['adm_refno' => 'adm_refno']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRequiredDocument(): ActiveQuery
    {
        return $this->hasOne(RequiredDocument::class, ['required_document_id' => 'required_document_id']);
    }
}
