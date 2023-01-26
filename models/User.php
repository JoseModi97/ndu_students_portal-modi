<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use app\helpers\SmisHelper;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
 * @property string|null $admission_status to take care of a case where an admission is revoked or recalled for the sake of module II
 * @property int|null $application_refno to link to applicant incase a report of admitted student is required
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
 * @property string|null $clearance_status Indicates clearance status of a student. PENDING, CLEARED, NOT CLEARED
 * @property string|null $password_changed_date
 * @property string|null $service
 * @property bool|null $document_sync_status
 * @property string|null $service_number
 * @property string|null $nationality
 * @property string|null $date_of_birth
 * @property bool|null $profile_sync_status
 * @property int|null $sponsor
 * @property string|null $blood_group
 */
class User extends ActiveRecord implements IdentityInterface
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
            [['adm_refno', 'source_id', 'application_refno', 'intake_code', 'student_category_id', 'sponsor'], 'default', 'value' => null],
            [['adm_refno', 'source_id', 'application_refno', 'intake_code', 'student_category_id', 'sponsor'], 'integer'],
            [['doc_submission_status', 'document_sync_status', 'profile_sync_status'], 'boolean'],
            [['primary_email_verified_date', 'secondary_email_verified_date', 'password_changed_date', 'date_of_birth'], 'safe'],
            [['kcse_index_no', 'primary_phone_no', 'alternative_phone_no', 'post_code', 'post_address', 'kuccps_prog_code', 'uon_prog_code', 'national_id', 'birth_cert_no', 'passport_no', 'service'], 'string', 'max' => 20],
            [['kcse_year'], 'string', 'max' => 10],
            [['primary_email', 'alternative_email', 'surname', 'nationality'], 'string', 'max' => 50],
            [['town', 'admission_status', 'clearance_status', 'service_number'], 'string', 'max' => 30],
            [['password', 'primary_email_salt', 'secondary_email_salt'], 'string', 'max' => 255],
            [['other_names'], 'string', 'max' => 150],
            [['blood_group'], 'string', 'max' => 5],
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
            'service' => 'Service',
            'document_sync_status' => 'Document Sync Status',
            'service_number' => 'Service Number',
            'nationality' => 'Nationality',
            'date_of_birth' => 'Date Of Birth',
            'profile_sync_status' => 'Profile Sync Status',
            'sponsor' => 'Sponsor',
            'blood_group' => 'Blood Group',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): User|IdentityInterface|null
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null){}

    /**
     * {@inheritdoc}
     */
    public function getId(): int|string
    {
        return $this->adm_refno;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(){}

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey){}

    /**
     * @param string $username admission reference number or registration number
     * @return bool|array|ActiveRecord|null
     */
    public static function findByUsername(string $username): bool|array|ActiveRecord|null
    {
        $option = 'not_registered'; // Admitted student logging in to complete registration process.

        if(str_contains($username, '/')){
            $option = 'registered';
        }

        $admissionRefNumber = $username;

        if($option === 'registered'){
            $studentProgramme = StudentProgramme::find()->select(['adm_refno'])->where(['registration_number' => $username])
                ->asArray()->one();

            if(empty($studentProgramme)){
                return false;
            }

            $admissionRefNumber = $studentProgramme['adm_refno'];
        }

        return self::find()->where(['adm_refno' => $admissionRefNumber])->one();
    }

    /**
     * Validates password
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     * @throws Exception
     */
    public function validatePassword(string $password): bool
    {
        /**
         * An admitted student is given their admission ref number as their default password.
         * This password is hashed with md5. However, passwords in the application are hashed using the Yii security component.
         * Therefore, for default md5 passwords, we rehash them using the Yii security component.
         * This is only done when the student logs in the system for the first time.
         */
        if(md5($password) === $this->password){
            $transaction = Yii::$app->db->beginTransaction();
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($password);
            if(!$this->save()){
                if(!$this->validate()){
                    $transaction->rollBack();
                    $errorMessage = SmisHelper::getModelErrors($this->getErrors());
                    throw new Exception($errorMessage);
                }else{
                    throw new Exception('Password not updated.');
                }
            }
            $transaction->commit();
        }

        try{
            if(Yii::$app->getSecurity()->validatePassword($password, $this->password)){
                return true;
            }else{
                return false;
            }
        }catch (InvalidArgumentException $ex){
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    #[ArrayShape(['plain' => "string", 'hash' => "string"])]
    public function generatePassword(): array
    {
        $passwordMaker = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz-*/_$#()&!+';
        $plainPassword = substr(str_shuffle($passwordMaker), 0, 8);

        try{
            $hashPassword = Yii::$app->getSecurity()->generatePasswordHash($plainPassword);
        }catch(Exception $ex){
            $message = 'Failed to generate password hash.';
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            throw new Exception($message);
        }

        return [
            'plain' => $plainPassword,
            'hash' => $hashPassword
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
