<?php

use yii\db\Migration;

class m160418_214325_fix_fk_notification_view_notification_id extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_notification_view_notification_id', 'notification_view');
        $this->addForeignKey('fk_notification_view_notification_id', 'notification_view',
            ['notification_id'], 'notification_browser', ['id']);
    }

    public function down()
    {
        echo "m160418_214325_fix_fk_notification_view_notification_id cannot be reverted.\n";

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
