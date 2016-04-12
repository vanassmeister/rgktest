<?php

use yii\db\Migration;

class m160412_191351_create_fk_notification_type_notification_id extends Migration
{
    public function up()
    {
        $this->addForeignKey('fk_notification_type_notification_id', 'notification_type',
            ['notification_id'], 'notification', ['id']);
    }

    public function down()
    {
        return false;
    }
}
