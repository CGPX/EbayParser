<?php

use yii\db\Schema;
use yii\db\Migration;

class m151206_113922_create_links_table extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%links}}', [
            'itemId' => 'int(11) unsigned NOT NULL',
            'hashId' => 'int(11) unsigned NOT NULL',
            'PRIMARY KEY (`itemId`, `hashId`)'
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%links}}');
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
