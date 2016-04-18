<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "notification_browser".
 *
 * @property integer $id
 * @property integer $recipient_id
 * @property string $subject
 * @property string $text
 * @property integer $is_viewed
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $recipient
 */
class NotificationBrowser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_browser';
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
            [['recipient_id', 'is_viewed'], 'integer'],
            [['subject', 'text'], 'string', 'max' => 255],
            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['recipient_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'recipient_id' => 'Recipient ID',
            'subject' => 'Subject',
            'text' => 'Text',
            'is_viewed' => 'Is Viewed',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(User::className(), ['id' => 'recipient_id']);
    }
}
