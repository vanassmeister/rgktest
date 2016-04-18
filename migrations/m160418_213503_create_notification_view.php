<?php

use yii\db\Migration;

class m160418_213503_create_notification_view extends Migration
{
    public function up()
    {
        $this->createTable('notification_view', [
            'id' => $this->primaryKey(),
            'notification_id' => $this->integer(),
            'user_id' => $this->integer(),
            'created_at' => $this->integer(11)
        ]);
        
        $this->addForeignKey('fk_notification_view_notification_id',
            'notification_view', ['notification_id'], 'notification', ['id']);
        
        $this->addForeignKey('fk_notification_view_user_id',
            'notification_view', ['user_id'], 'user', ['id']);        
    }

    public function down()
    {
        $this->dropTable('notification_view');
    }
}
