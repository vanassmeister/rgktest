<?php

use yii\db\Migration;

class m160412_192545_create_article extends Migration
{
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'text' => $this->text(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
        
        $this->addForeignKey('fk_article_author_id', 'article', ['author_id'], 'user', ['id']);
    }

    public function down()
    {
        $this->dropTable('article');
    }
}
