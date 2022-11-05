<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use JetBrains\PhpStorm\ArrayShape;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_reg_documents".
 *
 * @property int $document_id
 * @property string $document_name
 * @property string $document_desc
 * @property bool $required
 */
class RegistrationDocument extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_reg_documents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['document_name', 'document_desc'], 'required'],
            [['required'], 'boolean'],
            [['document_name'], 'string', 'max' => 150],
            [['document_desc'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['document_id' => "string", 'document_name' => "string", 'document_desc' => "string", 'required' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'document_id' => 'Document ID',
            'document_name' => 'Document Name',
            'document_desc' => 'Document Desc',
            'required' => 'Required',
        ];
    }
}
