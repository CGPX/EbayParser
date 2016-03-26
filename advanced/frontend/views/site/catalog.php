<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Collapse;

function catalogArray($rootParentId,$level){
    #$cache = yii::$app->getCache()->get('cat_'.$rootParentId);
    #if(!empty($cache)) { 
    #    return $cache;
    #}
    $textToEcho = "";
    $catArrayFirst = \common\models\EbayCategory::find()->where(['category_parent_id'=>$rootParentId])->asArray()->all();
    foreach($catArrayFirst as $value){
        $selected = do_next($value['category_id']);
        $active = $selected ? 'active' : '';
        $textToEcho.='<li class="lev'.$level.'">';
        $textToEcho.='<a href="#" class="alev'.$level.' '.$active.' categoryChange" data-target="'.$value['category_id'].'"><span>'. $value['category_name'].'</span></a>';
        if($selected){
            $data = catalogArray($value['category_id'],++$level);
            $textToEcho .= '<ul>'.$data.'</ul>';
        }
        $textToEcho.="</li>";
    }
    #yii::$app->getCache()->set('cat_'.$rootParentId, $textToEcho);
    return $textToEcho;
}



function do_next($cat_id){
    $root = !empty($_GET["category"]) ? $_GET["category"] : 6028;
    $BreadArray = createBread($root);
    if(sizeof($BreadArray) == 0){
        return FALSE;
    }
    foreach($BreadArray as $b){
        if ($b["category_id"] == $cat_id) {
            return true;
        }
    }
    return false;
}

#$cacheCats = yii::$app->getCache()->get('cats');
#$cacheCats = false;
#if($cacheCats == false) { 
    #$textToShow = catalogArray('6028',1);
#    yii::$app->getCache()->set('cats', $textToShow);
#} else {
#    $textToShow = $cacheCats;
#}

echo catalogArray('6028',1);
