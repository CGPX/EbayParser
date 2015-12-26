<?php

use yii\db\Schema;
use yii\db\Migration;

class m151205_190809_create_item_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%item}}', [
            'id' => $this->primaryKey(),
            'ebay_item_id' => $this->bigInteger()->notNull()->unique(),
            'title' => $this->string()->notNull(),
            'categoryId' => $this->integer()->notNull(),
            'categoryName' => $this->string()->notNull(),
            'galleryURL' => $this->string(),
            'viewItemURL' => $this->string()->notNull(),
            'current_price_value' => $this->money(null,0),
            'condition_id' => $this->integer(),
            'condition_display_name' => $this->string(),
            'shipping_service_cost' => $this->money(null,0),
            'price_shipping_sum' => $this->money(null,0),
            'sellingState' => $this->string()->notNull(),
            'timeLeft' => $this->string()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%item}}');
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
