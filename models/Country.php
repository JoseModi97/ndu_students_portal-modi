<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\models;

use JetBrains\PhpStorm\ArrayShape;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_country".
 *
 * @property string $country_code
 * @property string $country_name
 * @property string|null $continent
 * @property string|null $region
 * @property string $code2
 * @property string|null $nationality
 */
class Country extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['country_code', 'country_name', 'code2'], 'required'],
            [['country_code', 'code2'], 'string', 'max' => 5],
            [['country_name', 'continent', 'region', 'nationality'], 'string', 'max' => 100],
            [['country_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['country_code' => "string", 'country_name' => "string", 'continent' => "string", 'region' => "string",
        'code2' => "string", 'nationality' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'country_code' => 'Country Code',
            'country_name' => 'Country Name',
            'continent' => 'Continent',
            'region' => 'Region',
            'code2' => 'Code2',
            'nationality' => 'Nationality',
        ];
    }
}
