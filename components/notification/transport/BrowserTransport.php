<?php
/*
 * @author Ivan Nikiforov
 * Apr 13, 2016
 */
namespace app\components\notification\transport;

use app\models\NotificationBrowser;

/**
 * Description of BrowserTransport
 *
 * @author ivan
 */
class BrowserTransport extends AbstractTransport
{

    public function send()
    {
        $notificationBrowser = new NotificationBrowser();
        $notificationBrowser->setAttributes([
            'recipient_id' => $this->_notification->recipient_id,
            'subject' => $this->getSubject(),
            'text' => $this->getText()
        ]);
        
        return $notificationBrowser->save();
    }
}
