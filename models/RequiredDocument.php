<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use JetBrains\PhpStorm\ArrayShape;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_reg_required_document".
 *
 * @property int $required_document_id
 * @property int $fk_document_id
 * @property int $fk_category_id
 */
class RequiredDocument extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_reg_required_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fk_document_id', 'fk_category_id'], 'required'],
            [['fk_document_id', 'fk_category_id'], 'default', 'value' => null],
            [['fk_document_id', 'fk_category_id'], 'integer'],
            [['fk_document_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistrationDocument::class, 'targetAttribute' => ['fk_document_id' => 'document_id']],
            [['fk_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => StudentCategory::class, 'targetAttribute' => ['fk_category_id' => 'std_category_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['required_document_id' => "string", 'fk_document_id' => "string", 'fk_category_id' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'required_document_id' => 'Required Document ID',
            'fk_document_id' => 'Fk Document ID',
            'fk_category_id' => 'Fk Category ID',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getDocument(): ActiveQuery
    {
        return $this->hasOne(RegistrationDocument::class, ['document_id' => 'fk_document_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(StudentCategory::class, ['std_category_id' => 'fk_category_id']);
    }
}
