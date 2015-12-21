<?php

use yii\db\Schema;
use yii\db\Migration;

class m151206_124530_create_hash_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%hash}}', [
            'id' => $this->primaryKey(),
            'hash' => $this->string()->notNull()->unique(),
            'life_time' => $this->dateTime()->notNull(),
            'page' => $this->integer()->notNull(),
            'page_count' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%hash}}');
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
