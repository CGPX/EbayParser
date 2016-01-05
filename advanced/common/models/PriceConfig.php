<?php

namespace common\models;


use yii\db\ActiveRecord;

class PriceConfig extends ActiveRecord {

    public static function tableName()
    {
        return '{{%priceconfig}}';
    }

}