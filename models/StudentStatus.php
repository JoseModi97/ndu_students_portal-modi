<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use JetBrains\PhpStorm\ArrayShape;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_student_status".
 *
 * @property int $status_id
 * @property string $status
 * @property bool $current_status
 */
class StudentStatus extends ActiveRecord
{
    const STUDENT_STATUS_ACTIVE = 'ACTIVE';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_student_status';
    }


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['status_id', 'status', 'current_status'], 'required'],
            [['status_id'], 'default', 'value' => null],
            [['status_id'], 'integer'],
            [['current_status'], 'boolean'],
            [['status'], 'string', 'max' => 12],
            [['status_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['status_id' => "string", 'status' => "string", 'current_status' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'status_id' => 'Status ID',
            'status' => 'Status',
            'current_status' => 'Current Status',
        ];
    }

    /**
     * @param string $status
     * @return int
     */
    public static function evaluateStudentStatus(string $status = self::STUDENT_STATUS_ACTIVE): int
    {
        $status = self::findOne(['status' => $status]);
        if ($status != null) {
            return $status->status_id;
        }
        return 0;
    }
}
