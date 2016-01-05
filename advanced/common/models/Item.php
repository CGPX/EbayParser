<?php

namespace common\models;

use yii\db\ActiveRecord;

class Item extends ActiveRecord {

    public static function tableName()
    {
        return '{{%item}}';
    }

    public function beforeDelete() {
        if (parent::beforeDelete()) {
            $items =ImageGallery::find()->where(['ebay_item_id' => $this->ebay_item_id])->all();
            foreach($items as $item) {
                $item->delete();
            }
            return true;
        } else {
            return false;
        }
    }
}