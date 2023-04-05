<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_courses".
 *
 * @property int $course_id
 * @property string $course_code
 * @property string $course_name
 * @property int $level_of_study
 * @property int $semester
 * @property int $academic_hours
 * @property string $status
 * @property int $org_unit_id
 * @property int|null $billing_factor
 * @property int $category_id
 */
class Course extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_courses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['course_id', 'course_code', 'course_name', 'level_of_study', 'semester', 'academic_hours', 'org_unit_id', 'category_id'], 'required'],
            [['course_id', 'level_of_study', 'semester', 'academic_hours', 'org_unit_id', 'billing_factor', 'category_id'], 'default', 'value' => null],
            [['course_id', 'level_of_study', 'semester', 'academic_hours', 'org_unit_id', 'billing_factor', 'category_id'], 'integer'],
            [['course_code'], 'string', 'max' => 8],
            [['course_name'], 'string', 'max' => 150],
            [['status'], 'string', 'max' => 10],
            [['course_id'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CourseCategory::class, 'targetAttribute' => ['category_id' => 'category_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'course_id' => 'Course ID',
            'course_code' => 'Course Code',
            'course_name' => 'Course Name',
            'level_of_study' => 'Level Of Study',
            'semester' => 'Semester',
            'academic_hours' => 'Academic Hours',
            'status' => 'Status',
            'org_unit_id' => 'Org Unit ID',
            'billing_factor' => 'Billing Factor',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(CourseCategory::class, ['category_id' => 'category_id']);
    }
}
