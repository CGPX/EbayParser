<?php

use yii\db\Schema;
use yii\db\Migration;

class m151230_061331_create_image_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%image}}', [
            'id'           => $this->primaryKey(),
            'ebay_item_id' => $this->bigInteger()->notNull(),
            'image_url'    => $this->string(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%image}}');
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
