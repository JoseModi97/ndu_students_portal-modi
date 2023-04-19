<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */
namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "smisportal.org_rooms".
 *
 * @property int $room_id
 * @property string|null $room_code
 * @property string|null $room_name
 * @property int|null $fk_building_id
 * @property int|null $fk_floor_id
 * @property int|null $room_capacity
 * @property int|null $fk_room_type
 * @property int|null $fk_room_status_id
 */
class Room extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smisportal.org_rooms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['room_id'], 'required'],
            [['room_id', 'fk_building_id', 'fk_floor_id', 'room_capacity', 'fk_room_type', 'fk_room_status_id'], 'default', 'value' => null],
            [['room_id', 'fk_building_id', 'fk_floor_id', 'room_capacity', 'fk_room_type', 'fk_room_status_id'], 'integer'],
            [['room_code'], 'string', 'max' => 40],
            [['room_name'], 'string', 'max' => 80],
            [['room_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'room_id' => 'Room ID',
            'room_code' => 'Room Code',
            'room_name' => 'Room Name',
            'fk_building_id' => 'Fk Building ID',
            'fk_floor_id' => 'Fk Floor ID',
            'room_capacity' => 'Room Capacity',
            'fk_room_type' => 'Fk Room Type',
            'fk_room_status_id' => 'Fk Room Status ID',
        ];
    }
}
