<?php
/**
 * Created by PhpStorm.
 * User: Hank
 * Date: 24.12.2015
 * Time: 9:15
 */

namespace common\models;


use yii\db\ActiveRecord;

class EbayCategory extends ActiveRecord {

    public static function tableName() {
        return '{{%ebaycategory}}';
    }

}