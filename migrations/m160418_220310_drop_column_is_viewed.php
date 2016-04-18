<?php

use yii\db\Migration;

class m160418_220310_drop_column_is_viewed extends Migration
{
    public function up()
    {
        $this->dropColumn('notification_browser', 'is_viewed');
    }

    public function down()
    {
        $this->createTable('column_is_viewed', [
            'id' => $this->primaryKey()
        ]);
    }
}
