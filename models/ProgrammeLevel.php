<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/30/2023
 * @time: 11:50 AM
 */

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_prog_level".
 *
 * @property int $programme_level_id
 * @property string $programme_level_name
 */
class ProgrammeLevel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_prog_level';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['programme_level_name'], 'required'],
            [['programme_level_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'programme_level_id' => 'Programme Level ID',
            'programme_level_name' => 'Programme Level Name',
        ];
    }
}
