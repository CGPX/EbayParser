<?php
/**
 * Created by PhpStorm.
 * User: Hank
 * Date: 17.03.2016
 * Time: 0:24
 */

namespace frontend\actions;


use common\models\EbayCategory;
use Yii;
use yii\base\Action;
use yii\web\Response;

class CustomAction extends Action
{

    public function run()
    {
        if (Yii::$app->request->getIsAjax()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (null === $catId = (int)Yii::$app->request->post('catId')) {
                return ['content' => Yii::t('vote', 'catId has not been sent')];
            }
            $category = EbayCategory::find()->where(['id' => $catId])->one();
            return ['success' => true, 'category' => $category->category_root_parent];
        }
    }

}