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
    
    public $notificationTypeIds = [];

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
            [['event', 'notificationTypeIds'], 'required'],
            [['sender_id', 'recipient_id'], 'filter', 'filter' => function($val) {
                return $val ? $val : null;
            }],
            [['sender_id', 'recipient_id'], 'integer'],
            [['event', 'subject', 'text'], 'string', 'max' => 255],
            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['recipient_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
            [['notificationTypeIds'], 'in', 'range' => array_keys(self::getTypeList()), 'allowArray' => true]
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
        foreach (self::getModelClasses() as $class) {
            $events = array_merge($events, $this->getModelEvents($class));
        }

        return array_combine($events, $events);
    }

    public static function getTypeList()
    {
        return [
            self::TYPE_EMAIL => 'Email',
            self::TYPE_BROWSER => 'Browser'
        ];
    }
    
    public static function getTypeName($typeId) {
        $types = self::getTypeList();
        return isset($types[$typeId]) ? $types[$typeId] : 'Unknown type';
    }
    
    public function getTypeNames() {
        $names = array_map(function($val) {
            return Notification::getTypeName($val->type_id);}, $this->notificationTypes);
        return implode(', ', $names);
    }

    public function getModelClass()
    {
        $parts = explode('::', $this->event);
        return $parts[0];
    }

    public static function getModelClasses()
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
            case 'app\models\Article':
                $sender_id = $model->author_id;
                break;
            case 'app\models\User':
                $sender_id = $model->id;
                break;
            default :
                throw new Exception('Unknown model class');
        }

        if ($this->sender_id && $this->sender_id != $sender_id) {
            return;
        }

        foreach ($this->notificationTypes as $notificationType) {
            switch ($notificationType->type_id) {
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
    
    public function afterFind()
    {
        parent::afterFind();
        $this->notificationTypeIds = array_map(function($val){ 
            return $val->type_id;}, $this->notificationTypes);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        NotificationType::deleteAll(['notification_id' => $this->id]);
        foreach ($this->notificationTypeIds as $typeId) {
            $type = new NotificationType();
            $type->type_id = $typeId;
            $this->link('notificationTypes', $type);
        }        
    }
}
