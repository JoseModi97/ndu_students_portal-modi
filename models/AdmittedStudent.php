<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_admitted_student".
 *
 * @property int $adm_refno
 * @property string|null $kcse_index_no
 * @property string|null $kcse_year
 * @property string|null $primary_phone_no
 * @property string|null $alternative_phone_no
 * @property string|null $primary_email
 * @property string|null $alternative_email
 * @property string|null $post_code
 * @property string|null $post_address
 * @property string|null $town
 * @property string|null $kuccps_prog_code
 * @property string $uon_prog_code
 * @property string|null $national_id
 * @property string|null $birth_cert_no
 * @property int $source_id
 * @property string|null $passport_no
 * @property string|null $admission_status
 * @property int|null $application_refno
 * @property int $intake_code
 * @property int $student_category_id
 * @property string|null $password
 * @property bool|null $doc_submission_status
 * @property string|null $primary_email_salt
 * @property string|null $secondary_email_salt
 * @property string|null $primary_email_verified_date
 * @property string|null $secondary_email_verified_date
 * @property string|null $surname
 * @property string|null $other_names
 * @property string|null $clearance_status
 * @property string|null $password_changed_date
 */
class AdmittedStudent extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_admitted_student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['adm_refno', 'uon_prog_code', 'source_id', 'intake_code', 'student_category_id'], 'required'],
            [['adm_refno', 'source_id', 'application_refno', 'intake_code', 'student_category_id'], 'default', 'value' => null],
            [['adm_refno', 'source_id', 'application_refno', 'intake_code', 'student_category_id'], 'integer'],
            [['doc_submission_status'], 'boolean'],
            [['primary_email_verified_date', 'secondary_email_verified_date', 'password_changed_date'], 'safe'],
            [['kcse_index_no', 'kuccps_prog_code', 'uon_prog_code'], 'string', 'max' => 20],
            [['kcse_year'], 'string', 'max' => 10],
            [['primary_phone_no', 'alternative_phone_no', 'post_address', 'national_id', 'birth_cert_no', 'passport_no'], 'string', 'max' => 12],
            [['primary_email', 'alternative_email'], 'string', 'max' => 25],
            [['post_code'], 'string', 'max' => 5],
            [['town'], 'string', 'max' => 15],
            [['admission_status', 'clearance_status'], 'string', 'max' => 30],
            [['password', 'primary_email_salt', 'secondary_email_salt'], 'string', 'max' => 255],
            [['surname'], 'string', 'max' => 50],
            [['other_names'], 'string', 'max' => 150],
            [['adm_refno'], 'unique'],
            [['source_id'], 'exist', 'skipOnError' => true, 'targetClass' => IntakeSource::class, 'targetAttribute' => ['source_id' => 'source_id']],
            [['intake_code'], 'exist', 'skipOnError' => true, 'targetClass' => Intake::class, 'targetAttribute' => ['intake_code' => 'intake_code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'adm_refno' => 'Adm Refno',
            'kcse_index_no' => 'Kcse Index No',
            'kcse_year' => 'Kcse Year',
            'primary_phone_no' => 'Primary Phone No',
            'alternative_phone_no' => 'Alternative Phone No',
            'primary_email' => 'Primary Email',
            'alternative_email' => 'Alternative Email',
            'post_code' => 'Post Code',
            'post_address' => 'Post Address',
            'town' => 'Town',
            'kuccps_prog_code' => 'Kuccps Prog Code',
            'uon_prog_code' => 'Uon Prog Code',
            'national_id' => 'National ID',
            'birth_cert_no' => 'Birth Cert No',
            'source_id' => 'Source ID',
            'passport_no' => 'Passport No',
            'admission_status' => 'Admission Status',
            'application_refno' => 'Application Refno',
            'intake_code' => 'Intake Code',
            'student_category_id' => 'Student Category ID',
            'password' => 'Password',
            'doc_submission_status' => 'Doc Submission Status',
            'primary_email_salt' => 'Primary Email Salt',
            'secondary_email_salt' => 'Secondary Email Salt',
            'primary_email_verified_date' => 'Primary Email Verified Date',
            'secondary_email_verified_date' => 'Secondary Email Verified Date',
            'surname' => 'Surname',
            'other_names' => 'Other Names',
            'clearance_status' => 'Clearance Status',
            'password_changed_date' => 'Password Changed Date',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getIntake(): ActiveQuery
    {
        return $this->hasOne(Intake::class, ['intake_code' => 'intake_code']);
    }

    /**
     * @return ActiveQuery
     */
    public function getIntakeSource(): ActiveQuery
    {
        return $this->hasOne(IntakeSource::class, ['source_id' => 'source_id']);
    }
}
