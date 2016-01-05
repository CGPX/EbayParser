<?php

use yii\db\Schema;
use yii\db\Migration;

class m151229_101321_create_priceconfig_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%priceconfig}}', [
            'id'                        => $this->primaryKey(),
            'use_price_politic'         => $this->boolean()->notNull(),
            'price_coefficient'         => $this->double()->notNull(),
            'price_percent'             => $this->double()->notNull(),
            'current_usd_exchange_rate' => $this->money(null,2)->notNull(),
            'last_update_time'          => $this->date(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%priceconfig}}');
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
