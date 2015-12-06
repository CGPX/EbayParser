<?php
namespace common\models;

use yii\db\ActiveRecord;

class Links extends ActiveRecord {

    public static function tableName()
    {
        return '{{%links}}';
    }

    public  function getItems()
    {
        return $this->hasMany(Item::className(), ['item_id' => 'id'])->all();
    }

}