<?php

use yii\db\Migration;

class m160418_194519_change_fk_notification_type_notification_id extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_notification_type_notification_id', 'notification_type');
        $this->addForeignKey('fk_notification_type_notification_id', 'notification_type',
            ['notification_id'], 'notification', ['id'], 'CASCADE');
    }

    public function down()
    {
        echo "m160418_194519_change_fk_notification_type_notification_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
