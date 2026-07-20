<?php

namespace app\modules\refund_requests\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_student_programme_curriculum".
 *
 * @property int $student_prog_curriculum_id
 * @property int $student_id
 * @property string $registration_number
 * @property int $prog_curriculum_id
 * @property int $student_category_id
 * @property int $adm_refno
 * @property int $status_id
 * @property string|null $userid
 * @property string|null $ip_address
 * @property string|null $user_action
 * @property string|null $last_update
 */
class StudentProgCurriculum extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_student_programme_curriculum';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['student_id', 'registration_number', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'required'],
            [['student_id', 'prog_curriculum_id', 'student_category_id', 'adm_refno', 'status_id'], 'integer'],
            [['last_update'], 'safe'],
            [['registration_number', 'userid'], 'string', 'max' => 20],
            [['ip_address'], 'string', 'max' => 50],
            [['user_action'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'student_prog_curriculum_id' => 'Student Prog Curriculum ID',
            'student_id' => 'Student ID',
            'registration_number' => 'Registration Number',
            'prog_curriculum_id' => 'Prog Curriculum ID',
            'student_category_id' => 'Student Category ID',
            'adm_refno' => 'Adm Refno',
            'status_id' => 'Status ID',
            'userid' => 'Userid',
            'ip_address' => 'Ip Address',
            'user_action' => 'User Action',
            'last_update' => 'Last Update',
        ];
    }
}
