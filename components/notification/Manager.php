<?php
/*
 * @author Ivan Nikiforov
 * Apr 13, 2016
 */
namespace app\components\notification;

use yii\base\Component;
use app\models\Notification;
use dektrium\user\models\User;
use yii\base\Event;
use Yii;

/**
 * Description of newPHPClass
 *
 * @author ivan
 */
class Manager extends Component
{

    private $_notifications;

    public function init()
    {
        parent::init();
        
        // Подключаем уведомления к событиям моделей
        $this->_notifications = Notification::find()->indexBy('id')->all();
        foreach ($this->_notifications as $notification) {
            Event::on($notification->getModelClass(), constant($notification->event), [$notification, 'send']);
        }

        // Добавляем новым пользователям роль "user"
        Event::on(User::className(), User::EVENT_AFTER_INSERT, function($event) {
            $auth = Yii::$app->authManager;
            $user = $auth->getRole('user');
            $auth->assign($user, $event->sender->id);
        });
    }
}
