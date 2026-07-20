<?php

namespace app\modules\caution_refund\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "smis.sm_approval_level".
 *
 * @property int $level_id
 * @property string $level_name
 * @property int $level_order
 *
 * @property ApprovalProcess[] $approvalProcesses
 */
class ApprovalLevel extends ActiveRecord
{
    /**
     * @return Connection the database connection used by this AR class.
     */
    public static function getDb(): Connection
    {
        return Yii::$app->get('smisDb');
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.sm_approval_level';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['level_name', 'level_order'], 'required'],
            [['level_order'], 'default', 'value' => null],
            [['level_order'], 'integer'],
            [['level_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'level_id' => 'Level ID',
            'level_name' => 'Level Name',
            'level_order' => 'Level Order',
        ];
    }

    /**
     * Gets query for [[ApprovalProcesses]].
     *
     * @return ActiveQuery
     */
    public function getApprovalProcesses(): ActiveQuery
    {
        return $this->hasMany(ApprovalProcess::class, ['level_id' => 'level_id']);
    }
}
