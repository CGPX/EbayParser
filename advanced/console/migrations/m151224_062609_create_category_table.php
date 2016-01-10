<?php

use yii\db\Schema;
use yii\db\Migration;

class m151224_062609_create_category_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%ebaycategory}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->bigInteger()->notNull()->unique(),
            'category_parent_id' => $this->bigInteger(),
            'category_level' => $this->integer(),
            'category_name' => $this->string()->notNull(),
            'category_root_parent' => $this->bigInteger()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%ebaycategory}}');
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
