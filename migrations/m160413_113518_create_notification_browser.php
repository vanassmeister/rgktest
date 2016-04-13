<?php

use yii\db\Migration;

class m160413_113518_create_notification_browser extends Migration
{
    public function up()
    {
        $this->createTable('notification_browser', [
            'id' => $this->primaryKey(),
            'recipient_id' => $this->integer(),
            'subject' => $this->string(),
            'text' => $this->string(),
            'is_viewed' => $this->boolean(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
        
        $this->addForeignKey('fk_notification_browser_recipient_id', 'notification_browser', ['recipient_id'], 'user', ['id']);
    }

    public function down()
    {
        $this->dropTable('notification_browser');
    }
}
