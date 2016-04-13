<?php namespace app\models;

use app\models\User;
use app\components\notification\transport\BrowserTransport;
use app\components\notification\transport\EmailTransport;
use ReflectionClass;
use Exception;
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
            [['event',], 'required'],
            [['sender_id', 'recipient_id'], 'filter', 'filter' => function($val) {
                return $val ? $val : null;
            }],
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
        $events = [];
        foreach ($this->getModelClasses() as $class) {
            $events += $this->getModelEvents($class);
        }

        return $events;
    }

    public function getTypeList()
    {
        return [
            self::TYPE_EMAIL => 'Email',
            self::TYPE_BROWSER => 'Browser'
        ];
    }

    public function getModelClass()
    {
        $parts = explode('::', $this->event);
        return $parts[0];
    }

    public function getModelClasses()
    {
        return [
            '\app\models\Article',
            '\app\models\User'
        ];
    }

    public function getModelEvents($class)
    {
        $reflection = new ReflectionClass($class);
        $constants = array_map(function($val) use($class) {
            return "$class::$val";
        }, array_keys($reflection->getConstants()));

        return array_filter($constants, function($val) {
            return strpos($val, '::EVENT_') !== false;
        });
    }

    public function send($event)
    {
        $model = $event->sender;
        switch (get_class($model)) {
            case '\app\models\Article':
                $sender_id = $model->author_id;
                break;
            case '\app\models\User':
                $sender_id = $model->id;
        }
        
        if ($this->sender_id && $this->sender_id !== $sender_id) {
            return;
        }

        foreach ($this->getNotificationTypes() as $notificationType) {
            switch ($notificationType) {
                case self::TYPE_EMAIL:
                    $transport = new EmailTransport($this, $model);
                    break;
                case self::TYPE_BROWSER:
                    $transport = new BrowserTransport($this, $model);
                    break;
                default :
                    throw new Exception('Unknown transport');
            }
            $transport->send();
        }
    }
}
