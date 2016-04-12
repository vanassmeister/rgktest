<?php

use yii\db\Migration;

class m160412_183500_create_notification extends Migration
{
    public function up()
    {
        $this->createTable('notification', [
            'id' => $this->primaryKey(),
            'event' => $this->string()->notNull(),
            'sender_id' => $this->integer()->notNull(),
            'recipient_id' => $this->integer()->notNull(),
            'subject' => $this->string(),
            'text' => $this->string()
        ]);
        
        $this->addForeignKey('fk_notification_sender_id', 'notification', ['sender_id'], 'user', ['id']);
        $this->addForeignKey('fk_notification_recipient_id', 'notification', ['recipient_id'], 'user', ['id']);        
        
        $this->createTable('notification_type', [
            'notification_id' => $this->integer(),
            'type_id' => $this->integer(),
        ]);
        
        $this->addPrimaryKey('pk_notification_type', 'notification_type', ['notification_id', 'type_id']);

    }

    public function down()
    {
        return false;
    }
}
