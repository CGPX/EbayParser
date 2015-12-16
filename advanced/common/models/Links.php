<?php
namespace common\models;

use yii\db\ActiveRecord;

class Links extends ActiveRecord {

    public static function tableName()
    {
        return '{{%links}}';
    }

    public function beforeDelete() {
        if (parent::beforeDelete()) {
            $items =Item::find()->where(['id' => $this->itemId])->all();
            foreach($items as $item) {
                $item->delete();
            }
            return true;
        } else {
            return false;
        }
    }

}