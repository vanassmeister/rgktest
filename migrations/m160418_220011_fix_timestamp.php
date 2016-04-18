<?php

use yii\db\Migration;

class m160418_220011_fix_timestamp extends Migration
{
    public function up()
    {
        $this->addColumn('notification_view', 'updated_at', 'integer(11)');
    }

    public function down()
    {
        echo "m160418_220011_fix_timestamp cannot be reverted.\n";

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
