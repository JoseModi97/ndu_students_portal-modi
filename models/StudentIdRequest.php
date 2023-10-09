<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the base model class for table "smisportal.sm_student_id_request".
 *
 * @property integer $request_id
 * @property integer $request_type_id
 * @property integer $student_prog_curr_id
 * @property string $request_date
 * @property integer $status_id
 * @property string $source
 * @property boolean $student_id_sync_status
 *
 * @property IdRequestStatus $status
 * @property IdRequestType $requestType
 * @property StudentProgramme $studentProgCurr
 */
class StudentIdRequest extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_student_id_request';
    }


    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['request_type_id', 'student_prog_curr_id', 'request_date', 'status_id', 'source'], 'required'],
            [['request_type_id', 'student_prog_curr_id', 'status_id', 'receipt_number'], 'default', 'value' => null],
            [['student_id_sync_status'], 'default', 'value' => false],
            [['request_type_id', 'student_prog_curr_id', 'status_id', 'receipt_number'], 'integer'],
            [['request_date'], 'safe'],
            [['student_id_sync_status'], 'boolean'],
            [['source'], 'string', 'max' => 30],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => IdRequestStatus::class, 'targetAttribute' => ['status_id' => 'status_id']],
            [['request_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => IdRequestType::class, 'targetAttribute' => ['request_type_id' => 'request_type_id']],
            [
                ['student_prog_curr_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => StudentProgramme::class,
                'targetAttribute' => ['student_prog_curr_id' => 'student_prog_curriculum_id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'request_id' => 'Request ID',
            'request_type_id' => 'Request type',
            'student_prog_curr_id' => 'Current programme',
            'request_date' => 'Request Date',
            'status_id' => 'Request status',
            'source' => 'Request reason',
            'student_id_sync_status' => 'Student Id Sync Status'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(IdRequestStatus::class, ['status_id' => 'status_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRequestType(): ActiveQuery
    {
        return $this->hasOne(IdRequestType::class, ['request_type_id' => 'request_type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStudentProgCurr(): ActiveQuery
    {
        return $this->hasOne(StudentProgramme::class, ['student_prog_curriculum_id' => 'student_prog_curr_id']);
    }

    /**
     * @return bool
     */
    public static function hasOpenIdRequest(): bool
    {
        $idRequest = self::find()
            ->joinWith(['studentProgCurr', 'status'])
            ->where(['adm_refno' => \Yii::$app->user->identity->adm_refno])
            ->andWhere(['<>', 'status_name', IdRequestStatus::STATUS_CLOSED])
            ->asArray()
            ->one();

        return $idRequest != null;
    }

    private static function baseSmisQuery(): string
    {
        $schema = "smisportal";
        return <<<SQL
SELECT
	sm_student_id.student_id_serial_no,
	sm_student_id.valid_from,
	sm_student_id.valid_to,
	sm_student_id.barcode,
	sm_student_id.id_status,
	sm_student.student_number,
	sm_student_category.std_category_name,
	sm_student_programme_curriculum.registration_number AS reg_no,
	org_programme_curriculum.prog_curriculum_desc,
	org_programmes.prog_code,
	org_programmes.prog_short_name,
	org_programmes.prog_full_name,
	org_prog_type.prog_type_name,
	CONCAT ( sm_student.surname, ' ', sm_student.other_names ) AS full_name,
	COALESCE ( sm_student.id_no, sm_student.passport_no ) AS id_pp 
FROM
	$schema.sm_student_id
	INNER JOIN $schema.sm_student_programme_curriculum ON sm_student_id.student_prog_curr_id = sm_student_programme_curriculum.student_prog_curriculum_id
	INNER JOIN $schema.org_programme_curriculum ON sm_student_programme_curriculum.prog_curriculum_id = org_programme_curriculum.prog_curriculum_id
	INNER JOIN $schema.org_programmes ON org_programme_curriculum.prog_id = org_programmes.prog_id
	INNER JOIN $schema.org_prog_type ON org_programmes.prog_type_id = org_prog_type.prog_type_id
	INNER JOIN $schema.sm_student ON sm_student_programme_curriculum.student_id = sm_student.student_id
	INNER JOIN $schema.sm_student_category ON sm_student_programme_curriculum.student_category_id = sm_student_category.std_category_id
SQL;
    }


    /**
     * @param int $currProgId
     * @param $statusName
     * @return array|\yii\db\DataReader|null
     * @throws \yii\db\Exception
     */
    public static function findOneByCurrProgId(int $currProgId, $statusName): \yii\db\DataReader|array|null
    {
        $query = self::baseSmisQuery();
        $query .= <<<FILTER
            WHERE sm_student_id.student_prog_curr_id = :currProgId
            AND sm_student_id.id_status = :statusName
FILTER;

        $query = self::getDb()->createCommand($query);
        $data = $query->bindValues([
            'currProgId' => $currProgId,
            'statusName' => $statusName
        ])->queryOne();

        if (!$data) {
            return null;
        }
        return $data;

    }

}
