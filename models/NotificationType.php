<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notification_type".
 *
 * @property integer $notification_id
 * @property integer $type_id
 *
 * @property Notification $notification
 */
class NotificationType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notification_id', 'type_id'], 'required'],
            [['notification_id', 'type_id'], 'integer'],
            [['notification_id'], 'exist', 'skipOnError' => true, 'targetClass' => Notification::className(), 'targetAttribute' => ['notification_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'notification_id' => 'Notification ID',
            'type_id' => 'Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotification()
    {
        return $this->hasOne(Notification::className(), ['id' => 'notification_id']);
    }
}
