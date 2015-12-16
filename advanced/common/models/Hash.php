<?php
namespace common\models;

use yii\db\ActiveRecord;

class Hash extends ActiveRecord {

    public static function tableName() {
        return '{{%hash}}';
    }

    public function getItems() {
        return $this->hasMany(Item::className(), ['id' => 'itemId'])
            ->viaTable('links', ['hashId' => 'id']);
    }


    public function beforeDelete() {
        if (parent::beforeDelete()) {
            $links =Links::find()->where(['hashId' => $this->id])->all();
            foreach($links as $link) {
                $link->delete();
            }
            return true;
        } else {
            return false;
        }
    }
}