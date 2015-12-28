<?php
/**
 * Created by PhpStorm.
 * User: bartleby
 * Date: 06.12.2015
 * Time: 22:44
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Collapse;
?>

<?php
$textToEcho="";
function catalogArray($rootParentId){
	global $textToEcho;
	$catArrayFirst=\common\models\EbayCategory::find()->where(['category_parent_id'=>$rootParentId])->asArray()->all();
	foreach($catArrayFirst as $value){
		$blank="";
		for($i=0;$i<$value['category_level'];$i++){$blank.="&nbsp";}
		$lastStandCheck=\common\models\EbayCategory::find()->where(['category_parent_id'=>$value['category_id']])->asArray()->all();
		if(isset($lastStandCheck[0][category_id])){
			$textToEcho.="<a href=\"#\" class=\"list-group-item small\" data-toggle=\"collapse\" data-target=\"#".$value['category_id']."\" data-parent=\"#".$value['category_parent_id']."\">". $blank . $value['category_name']." <span class=\"glyphicon pull-right glyphicon-list\"></span ></a>\n";
			$textToEcho.="<div id=\"".$value['category_id']."\" class=\"sublinks collapse\" > \n";
			catalogArray($value['category_id']);
			$textToEcho.="</div>";
		}else{
			$textToEcho.="<a href=\"#\" class=\"list-group-item small catChange\" data-toggle=\"collapse\" data-target=\"#".$value['category_id']."\" data-parent=\"#".$value['category_parent_id']."\">". $blank . $value['category_name']." <span class=\"glyphicon pull-right glyphicon-circle-arrow-right\"></span ></a>\n";
		}

	}
	return $textToEcho;
}
$textToShow=catalogArray('6028');
?>

	<div id="6028" class="menu">
		<div class="panel list-group">
			<?= $textToShow; ?>
		</div>
	</div>