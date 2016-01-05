<?php

namespace common\models;


use yii\db\ActiveRecord;

class ImageGallery extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%image}}';
    }
}