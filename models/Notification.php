<?php namespace app\models;

use dektrium\user\models\User;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property integer $id
 * @property string $event
 * @property integer $sender_id
 * @property integer $recipient_id
 * @property string $subject
 * @property string $text
 *
 * @property User $recipient
 * @property User $sender
 * @property NotificationType[] $notificationTypes
 */
class Notification extends \yii\db\ActiveRecord
{

    const TYPE_EMAIL = 1;
    const TYPE_BROWSER = 2;

    private $_userList = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event', 'sender_id', 'recipient_id'], 'required'],
            [['sender_id', 'recipient_id'], 'integer'],
            [['event', 'subject', 'text'], 'string', 'max' => 255],
            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['recipient_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event' => 'Event',
            'sender_id' => 'Sender ID',
            'recipient_id' => 'Recipient ID',
            'subject' => 'Subject',
            'text' => 'Text',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(User::className(), ['id' => 'recipient_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationTypes()
    {
        return $this->hasMany(NotificationType::className(), ['notification_id' => 'id']);
    }

    public function getEventList()
    {
        $reflectionArticle = new \ReflectionClass('\app\models\Article');
        $articleConstants = array_map(function($val) {
            return '\app\models\Article::' . $val;
        }, array_keys($reflectionArticle->getConstants()));

        $reflectionUser = new \ReflectionClass('\dektrium\user\models\User');
        $userConstants = array_map(function($val) {
            return '\dektrium\user\models\User::' . $val;
        }, array_keys($reflectionUser->getConstants()));

        $events = array_filter(array_merge($articleConstants, $userConstants), function($val) {
            return strpos($val, '::EVENT_') !== false;
        });
        
        return array_combine($events, $events);
    }

    public function getUserList()
    {
        if (!is_null($this->_userList)) {
            return $this->_userList;
        }

        $users = User::find()
            ->where('blocked_at IS NULL')
            ->orderBy('username')
            ->all();

        $this->_userList = ArrayHelper::map($users, 'id', 'username');
        return $this->_userList;
    }

    public function getTypeList()
    {
        return [
            self::TYPE_EMAIL => 'Email',
            self::TYPE_BROWSER => 'Browser'
        ];
    }
}
