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
$categories = Yii::$app->getCache()->get('postModel')->getCategories();
//$categories = $this->params['myMod'][0]['queryText'];

function catalogArray($auto,$autocat,$level,$categories){

	foreach($categories[$auto][$autocat]['CategoryArray']['Category'] as $value){

		$abber['glyphicon'][0]="";
		$abber['glyphicon'][1]="";
		$abber['glyphicon'][2]="";
		$abber['glyphicon'][3]="<span data-toggle=\"collapse\" data-target=\"#".$value['CategoryID']."\" data-parent=\"#".$value['CategoryParentID'][0]."\" class=\"glyphicon pull-right glyphicon-list\"></span >";
		$abber['glyphicon'][]="<span data-toggle=\"collapse\" data-target=\"#".$value['CategoryID']."\" data-parent=\"#".$value['CategoryParentID'][0]."\" class=\"glyphicon pull-right glyphicon-chevron-right\"></span >";
		//$abber['glyphicon'][4]="<span class=\"glyphicon pull-right glyphicon-chevron-right\"></span >";
		$abber['glyphicon'][5]="<span class=\"glyphicon pull-right glyphicon-chevron-right\"></span >";
		$abber['glyphicon'][6]="";

        $abber['space'][0]="";
        $abber['space'][1]="";
        $abber['space'][2]="";
        $abber['space'][3]="";
        $abber['space'][4]="&nbsp;&nbsp;";
        $abber['space'][5]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        $abber['space'][6]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

		$abber['action'][6]="catChange";
		// эта борода использовалась для смены категории напрямую
		// catSet - Эта функция jq привязаная к каждой ветке категорий будет менять значение категории
		// но при этом не будет делать submit, для смены, а будет работать в качестве фильтра
		// дополнительно помечая активную категорию в данный момент.

		//$echotext="<a href=\"#\" class=\"list-group-item small catChange\" data-toggle=\"collapse\" data-target=\"#".$value['CategoryID']."\" data-parent=\"#".$value['CategoryParentID'][0]."\">". $abber['space'][$value['CategoryLevel']] ." ".  $value['CategoryName']." ". $abber['glyphicon'][$value['CategoryLevel']] ."</a>\n";
		$echotext="<a href=\"#\" class=\"list-group-item small catChange\" data-toggle=\"collapse\" data-target=\"#".$value['CategoryID']."\" data-parent=\"#".$value['CategoryParentID'][0]."\">". $abber['space'][$value['CategoryLevel']] ." ".  $value['CategoryName']." ". $abber['glyphicon'][$value['CategoryLevel']] ."</a>\n";

		if($level==$value['CategoryLevel']){ // мы остаёмся на том же уровне вложенности
			echo $echotext."\n";
			$level=$value['CategoryLevel'];

		}elseif ($level<$value['CategoryLevel']){ // отпускаемся на уровень вниз
			echo "<div id=\"".$value['CategoryParentID'][0]."\" class=\"sublinks collapse\">\n";
			echo $echotext."\n";
			$level=$value['CategoryLevel'];

		}elseif ($level>$value['CategoryLevel']){ // поднимаемся на уровень вверх
			echo "</div>\n\n\n";
			echo $echotext."\n";
			$level=$value['CategoryLevel'];
		}
	}
	echo "</div>\n";
}

?>



					<div id="6028" class="menu">

						<div class="panel list-group">
								 <?= catalogArray('auto','autocat',3,$categories); ?>
								 <?= catalogArray('moto','motocat',3,$categories); ?>
								 <?= catalogArray('snow','snowcat',3,$categories); ?>
								 <?= catalogArray('atv','atvcat',3,$categories); ?>
						</div>

					</div>




<?php
/*
<div id="menu" class="">

	<div class="panel list-group">

		<a href="#" class="list-group-item text-uppercase" data-toggle="collapse" data-target="#sm" data-parent="#menu">АВТОМОБИЛИ <span class="glyphicon glyphicon-th-list pull-right"></span></a>
		<div id="sm" class="sublinks collapse">
			<a class="list-group-item small"><span class="glyphicon glyphicon-chevron-right"></span> Кузовщина <span class="glyphicon glyphicon-tasks pull-right"></span></a>
			<a class="list-group-item small"><span class="glyphicon glyphicon-chevron-right"></span> Электроника</a>
		</div>

		<a href="#" class="list-group-item text-uppercase" data-toggle="collapse" data-target="#sl" data-parent="#menu">МОТОЦИКЛЫ <span class="glyphicon glyphicon-th-list pull-right"></span></a>
		<div id="sl" class="sublinks collapse in">
			<a href="#" class="list-group-item small in" data-toggle="collapse" data-target="#ss" data-parent="#sl"><span class="glyphicon glyphicon-chevron-right"></span> Электроника</a>
			<div id="ss" class="sublinks collapse in">
				<a class="list-group-item small active in">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-chevron-right"></span> Стартеры</a>
				<a class="list-group-item small">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-chevron-right"></span> Свечи</a>
				<a class="list-group-item small">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-chevron-right"></span> Генераторы</a>
				<a class="list-group-item small">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-chevron-right"></span> Датчики</a>
				<a class="list-group-item small">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-chevron-right"></span> Клавиши</a>
			</div>
			<a class="list-group-item small"><span class="glyphicon glyphicon-chevron-right"></span> Колёса</a>
		</div>

		<a href="#" class="list-group-item text-uppercase">СНЕГОХОДЫ<span class="glyphicon glyphicon-th-list pull-right"></span></a>

	</div>

</div>
*/

/*
$parentka="6023";
foreach($categories['auto']['autocat']['CategoryArray']['Category'] as $value){
	$testerka[] = array(
		"CategoryID" => $value['CategoryID'],
		"CategoryLevel" => $value['CategoryLevel']-3,
		"CategoryName" => $value['CategoryName'],
		"CategoryParentID" => $value['CategoryParentID'][0]
	);
};
*/
?>