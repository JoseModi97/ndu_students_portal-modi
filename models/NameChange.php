<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_name_change".
 *
 * @property int $name_change_id
 * @property string $request_date
 * @property int $student_id
 * @property string|null $new_surname
 * @property string|null $new_othernames
 * @property string $reason
 * @property string|null $document_url
 * @property string|null $current_surname
 * @property string|null $current_othernames
 * @property string $status
 */
class NameChange extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_name_change';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['request_date', 'student_id', 'reason', 'status'], 'required'],
            [['student_id'], 'default', 'value' => null],
            [['student_id'], 'integer'],
            [['request_date'], 'safe'],
            [['new_surname', 'current_surname', 'status'], 'string', 'max' => 20],
            [['new_othernames', 'current_othernames'], 'string', 'max' => 50],
            [['reason', 'document_url'], 'string', 'max' => 200],
            [['name_change_id'], 'unique'],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::class, 'targetAttribute' => ['student_id' => 'student_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'name_change_id' => 'Name Change ID',
            'request_date' => 'Request Date',
            'student_id' => 'Student ID',
            'new_surname' => 'New Surname',
            'new_othernames' => 'New Othernames',
            'reason' => 'Reason',
            'document_url' => 'Document Url',
            'current_surname' => 'Current Surname',
            'current_othernames' => 'Current Othernames',
            'status' => 'Status',
        ];
    }
}
