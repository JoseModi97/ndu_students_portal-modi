<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the base model class for table "smisportal.org_programmes".
 *
 * @property integer $prog_id
 * @property string $prog_code
 * @property string $prog_short_name
 * @property string $prog_full_name
 * @property integer $prog_type_id
 * @property integer $prog_cat_id
 * @property string $status
 *
 * @property ProgrammeCurriculum[] $programmeCurriculums
 */
class Programmes extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'smisportal.org_programmes';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prog_id', 'prog_code', 'prog_short_name', 'prog_full_name', 'prog_type_id', 'prog_cat_id'], 'required'],
            [['prog_id', 'prog_type_id', 'prog_cat_id'], 'integer'],
            [['prog_code'], 'string', 'max' => 6],
            [['prog_short_name'], 'string', 'max' => 100],
            [['prog_full_name'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prog_id' => 'Prog ID',
            'prog_code' => 'Prog Code',
            'prog_short_name' => 'Prog Short Name',
            'prog_full_name' => 'Prog Full Name',
            'prog_type_id' => 'Prog Type ID',
            'prog_cat_id' => 'Prog Cat ID',
            'status' => 'Status',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProgrammeCurriculums(): ActiveQuery
    {
        return $this->hasMany(ProgrammeCurriculum::class, ['prog_id' => 'prog_id']);
    }
}
