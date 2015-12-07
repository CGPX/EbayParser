<?php
/**
 * Created by PhpStorm.
 * User: bartleby
 * Date: 07.12.2015
 * Time: 0:13
 */

namespace common\models;


use yii\db\ActiveRecord;

class Hash extends ActiveRecord
{
    public static function tablename(){
        return '{{%hash}}';
    }

    public function getitems(){
        return $this->hasMany(Item::className(),['id'=>'itemId'])->viaTable('links',['hashId'=>'id']);
    }
}