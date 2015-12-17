<?php
/**
 * Created by PhpStorm.
 * User: Hank
 * Date: 17.12.15
 * Time: 18:40
 */

namespace frontend\models;


use common\models\Item;
use yii\base\Model;

class SingleForm extends Model {

    public $singleItemId;

    public function rules()
    {
        return [
            [['singleItemId'], 'required'],
        ];
    }

    public function getSingleItem() {
        return Item::find()->where(['ebay_item_id' => $this->singleItemId])->asArray()->all();
    }

}