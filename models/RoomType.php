<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_room_type".
 *
 * @property int $room_type_id
 * @property string $room_type_name
 */
class RoomType extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_room_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['room_type_name'], 'required'],
            [['room_type_name'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'room_type_id' => 'Room Type ID',
            'room_type_name' => 'Room Type Name',
        ];
    }
}
