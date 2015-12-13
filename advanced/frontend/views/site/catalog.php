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

$model = new \common\models\EbayForm();
$categories=$model->getCategories();

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