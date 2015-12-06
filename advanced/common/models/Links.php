<?php
namespace common\models;

use yii\db\ActiveRecord;

class Links extends ActiveRecord {

    public static function tableName()
    {
        return '{{%links}}';
    }

    public function getItems($id)
    {
        return $this->hasMany(Item::className(), ['id' => 'itemId'])->where('hashId = :idhash',['idhash' => $id])->all();
    }

}