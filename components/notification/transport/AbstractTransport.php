<?php
/*
 * @author Ivan Nikiforov
 * Apr 13, 2016
 */
namespace app\components\notification\transport;

use app\models\Notification;
use app\components\notification\PlaceholdersInterface;

/**
 * Description of AbstractTransport
 *
 * @author ivan
 */
abstract class AbstractTransport
{

    /**
     *
     * @var \app\models\Notification
     */
    protected $_notification;
    
    /**
     *
     * @var \app\components\notification\PlaceholdersInterface
     */
    protected $_model;

    public function __construct(Notification $notification, PlaceholdersInterface $model)
    {
        $this->_notification = $notification;
        $this->_model = $model;
    }

    protected function fillPlaceholders($text)
    {
        $placeholders = $this->_model->getPlaceholders();
        return str_replace(array_keys($placeholders), array_values($placeholders), $text);
    }

    protected function getSubject()
    {
        return $this->fillPlaceholders($this->_notification->subject);
    }

    protected function getText()
    {
        return $this->fillPlaceholders($this->_notification->text);
    }

    abstract public function send();
}
