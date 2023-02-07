<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_sponsor".
 *
 * @property int $sponsor_id
 * @property string $sponsor_name
 * @property string|null $country_code
 * @property bool|null $status
 */
class Sponsor extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_sponsor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['sponsor_id', 'sponsor_name'], 'required'],
            [['sponsor_id'], 'default', 'value' => null],
            [['sponsor_id'], 'integer'],
            [['status'], 'boolean'],
            [['sponsor_name'], 'string', 'max' => 100],
            [['country_code'], 'string', 'max' => 5],
            [['sponsor_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'sponsor_id' => 'Sponsor ID',
            'sponsor_name' => 'Sponsor Name',
            'country_code' => 'Country Code',
            'status' => 'Status',
        ];
    }
}
