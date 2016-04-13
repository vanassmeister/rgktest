<?php

use yii\db\Migration;

class m160413_082036_remove_not_null extends Migration
{
    public function up()
    {
        $this->alterColumn('notification', 'sender_id', 'integer');
        $this->alterColumn('notification', 'recipient_id', 'integer');
    }

    public function down()
    {
        echo "m160413_082036_remove_not_null cannot be reverted.\n";

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
