<?php
/*
 * @author Ivan Nikiforov
 * Apr 13, 2016
 */
namespace app\components\notification\transport;

use app\models\User;
use Yii;

/**
 * Description of EmailTransport
 *
 * @author ivan
 */
class EmailTransport extends AbstractTransport
{

    public function send()
    {

        $recipients = User::find()
            ->where('id = :recipient_id OR :recipient_id IS NULL', 
                ['recipient_id' => $this->_notification->recipient_id])
            ->all();

        foreach ($recipients as $recipient) {
            Yii::$app->mailer->compose('notification', ['text' => $this->getText()])
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                ->setTo($recipient->email)
                ->setSubject($this->getSubject())
                ->send();
        }
    }
}
