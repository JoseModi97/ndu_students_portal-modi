<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.sm_student".
 *
 * @property int $student_id
 * @property string $student_number
 * @property string $surname
 * @property string $other_names
 * @property string $gender
 * @property string $country_code
 * @property string $dob
 * @property string|null $id_no
 * @property string|null $passport_no
 * @property string|null $service_number
 * @property string|null $blood_group
 * @property int|null $sponsor
 * @property string|null $registration_date
 * @property string|null $primary_phone_no
 * @property string|null $alternative_phone_no
 * @property string|null $primary_email
 * @property string|null $alternative_email
 * @property string|null $post_code
 * @property string|null $post_address
 * @property string|null $town
 */
class Student extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.sm_student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['student_id', 'student_number', 'surname', 'other_names', 'gender', 'country_code', 'dob'], 'required'],
            [['student_id', 'sponsor'], 'default', 'value' => null],
            [['student_id', 'sponsor'], 'integer'],
            [['dob', 'registration_date'], 'safe'],
            [['student_number', 'passport_no', 'service_number', 'post_address'], 'string', 'max' => 20],
            [['surname', 'primary_phone_no', 'alternative_phone_no', 'town'], 'string', 'max' => 50],
            [['other_names', 'primary_email', 'alternative_email'], 'string', 'max' => 100],
            [['gender'], 'string', 'max' => 1],
            [['country_code'], 'string', 'max' => 3],
            [['id_no', 'post_code'], 'string', 'max' => 10],
            [['blood_group'], 'string', 'max' => 5],
            [['student_id'], 'unique'],
            [['country_code'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['country_code' => 'country_code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'student_id' => 'Student ID',
            'student_number' => 'Student Number',
            'surname' => 'Surname',
            'other_names' => 'Other Names',
            'gender' => 'Gender',
            'country_code' => 'Country Code',
            'dob' => 'Dob',
            'id_no' => 'Id No',
            'passport_no' => 'Passport No',
            'service_number' => 'Service Number',
            'blood_group' => 'Blood Group',
            'sponsor' => 'Sponsor',
            'registration_date' => 'Registration Date',
            'primary_phone_no' => 'Primary Phone No',
            'alternative_phone_no' => 'Alternative Phone No',
            'primary_email' => 'Primary Email',
            'alternative_email' => 'Alternative Email',
            'post_code' => 'Post Code',
            'post_address' => 'Post Address',
            'town' => 'Town',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['country_code' => 'country_code']);
    }
}
