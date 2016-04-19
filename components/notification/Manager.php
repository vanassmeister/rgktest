<?php
/*
 * @author Ivan Nikiforov
 * Apr 13, 2016
 */
namespace app\components\notification;

use yii\base\Component;
use app\models\Notification;
use yii\base\Event;

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

    }
}
