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

//var_dump($categories['auto']['autocat']['CategoryArray']['Category'][0]);
//echo $categories['cats'][1][2];

// вложенный массив имеется у нас, как мы порешаем его?
// думаю с главными категориями нужно определиться без кода. то-есть вызвать 4 раза функцию
// а значит у нас будет функция. верний уровень аккордиона будет уже составленным.


//categoriesAcc2(0, 0);
//var_dump($categories['auto']['autocat']['CategoryArray']['Category'][1]['CategoryName']);
//echo $categories['auto']['autocat']['CategoryArray']['Category'][1]['CategoryName'];
// $startPoint - это верхний уровень массива: auto, moto, snow, avt

$model = new \common\models\EbayForm();
$categories=$model->getCategories();
//var_dump($categories['auto']['autocat']['CategoryArray']['Category']);
/*
foreach ($categories['auto']['autocat']['CategoryArray']['Category'] as $value){

	$renewedArray[$value["CategoryID"][0]][] = [
			'id' 	=> $value['CategoryID'],
			'name'	=> $value['CategoryName'],
			'parent'=> $value['CategoryParentID'][0],
			'level'=> $value['CategoryLevel']

	];


}*/

/*
//var_dump($categories);
// первый уроень - главные шаблоны
//foreach($renewedArray as $value) {
foreach($categories['auto']['autocat']['CategoryArray'] as $value) {
	// начинаем рисовать аккордион
	foreach($value as $item){
		//var_dump($item);
		//echo "<br><a class=\"list-group - item small\" style='margin-left:" . ($item['level'] * 10) . "px;'><span class=\"glyphicon glyphicon - chevron - right\"></span> ".$item['name']."</a>";
		echo "<br><a class=\"list-group - item small\" style='margin-left:" . ($item['CategoryLevel'] * 10) . "px;'><span class=\"glyphicon glyphicon - chevron - right\"></span> ".$item['CategoryName']."</a>";
	}
	// ну собственно рисуем конец аккордиона
}
*/
function catalogArray($auto, $autocat, $level, $categories){

	foreach($categories[$auto][$autocat]['CategoryArray']['Category'] as $value){

		$abber['glyphicon'][0]="";
		$abber['glyphicon'][1]="";
		$abber['glyphicon'][2]="";
		$abber['glyphicon'][3]="glyphicon-list";
		$abber['glyphicon'][4]="glyphicon-chevron-right";
		$abber['glyphicon'][5]="";
		$abber['glyphicon'][6]="glyphicon-chevron-right";

		$abber['action'][5]="catChange";

		$echotext="<a href=\"#\" class=\"list-group-item small ".$abber['action'][$value['CategoryLevel']]." \" data-toggle=\"collapse\" data-target=\"#".$value['CategoryID']."\" data-parent=\"".$value['CategoryParentID'][0]."\">".  $value['CategoryName']." <span class=\"glyphicon pull-right ". $abber['glyphicon'][$value['CategoryLevel']] ." \"></span></a> \n";

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


			//echo "<br><a class=\"list-group - item small\" style='margin-left:" . ($value['CategoryLevel'] * 10) . "px;'><span class=\"glyphicon glyphicon - chevron - right\"></span> ".$value['CategoryName']."</a>";


	}
	echo "</div>\n";
}



?>
				<div class="row">

					<div id="menu">

						<div class="panel list-group">
								 <?php catalogArray('auto','autocat',3,$categories); ?>
								 <?php catalogArray('moto','motocat',3,$categories); ?>
								 <?php catalogArray('snow','snowcat',3,$categories); ?>
								 <?php catalogArray('atv','atvcat',3,$categories); ?>
						</div>

					</div>

				</div>
