<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "notification_view".
 *
 * @property integer $id
 * @property integer $notification_id
 * @property integer $user_id
 * @property integer $created_at
 *
 * @property Notification $notification
 * @property User $user
 */
class NotificationView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_view';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notification_id', 'user_id'], 'integer'],
            [['notification_id'], 'exist', 'skipOnError' => true, 'targetClass' => Notification::className(), 'targetAttribute' => ['notification_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'notification_id' => 'Notification ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotification()
    {
        return $this->hasOne(NotificationBrowser::className(), ['id' => 'notification_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
