<?php
/**
 * Created by PhpStorm.
 * User: bartleby
 * Date: 06.12.2015
 * Time: 22:44
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
				<div class="row">

					<div id="menu">

						<div class="panel list-group">

							 <a href="#" class="list-group-item text-uppercase" data-toggle="collapse" data-target="#123" data-parent="#menu">АВТОМОБИЛИ <span class="glyphicon glyphicon-th-list pull-right"></span></a>
							 <div id="123" class="sublinks collapse">
								  <a class="list-group-item small"><span class="glyphicon glyphicon-chevron-right"></span> Кузовщина <span class="glyphicon glyphicon-tasks pull-right"></span></a>
								  <a class="list-group-item small"><span class="glyphicon glyphicon-chevron-right"></span> Электроника</a>
							 </div>

							 <a href="#" class="list-group-item text-uppercase" data-toggle="collapse" data-target="#sl" data-parent="#menu">МОТОЦИКЛЫ <span class="glyphicon glyphicon-th-list pull-right"></span></a>
							 <div id="sl" class="sublinks collapse">
								  <a href="#" class="list-group-item small" data-toggle="collapse" data-target="#ss" data-parent="#sl"><span class="glyphicon glyphicon-chevron-right"></span> Электроника</a>
									 <div id="ss" class="sublinks collapse">
										  <a class="list-group-item small">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-chevron-right"></span> Стартеры</a>
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

				</div>
<?php /*
	<div class="row">

		<div id="menu">

			<div class="panel list-group">

//var_dump($categories);
//var_dump($categories[cats][1]);

// старое значение
$older=3; $olderHash[$older]="menu";
for ($i=0; $i!==count($categories[cats]);$i++){
	if ($older<$categories[cats][$i][2]) {
		echo "
			<div id=\"sm\" class=\"sublinks collapse\">
				<a href='#' class=\"list-group-item\" data-toggle=\"collapse\" data-target=\"#".md5($categories[cats][$i][1]+$categories[cats][$i][0])."\" data-parent=\"#".$olderHash[$categories[cats][$i][2]]."\"><span class=\"glyphicon glyphicon-chevron-right\"></span> ".$categories[cats][$i][1]." <span class=\"glyphicon glyphicon-tasks pull-right\"></span></a>
		";
	} elseif ($older==$categories[cats][$i][2]){
		echo "
				<a href='#' class=\"list-group-item\" data-toggle=\"collapse\" data-target=\"#".md5($categories[cats][$i][1]+$categories[cats][$i][0])."\" data-parent=\"#".$olderHash[$categories[cats][$i][2]]."\">".$categories[cats][$i][1]." <span class=\"glyphicon glyphicon-th-list pull-right\"></span></a>
		";
	} elseif ($older>$categories[cats][$i][2]) {
		echo "
			</div>
				<a href='#' class=\"list-group-item\" data-toggle=\"collapse\" data-target=\"#".md5($categories[cats][$i][1]+$categories[cats][$i][0])."\" data-parent=\"#".$olderHash[$categories[cats][$i][2]]."\"><span class=\"glyphicon glyphicon-chevron-right\"></span> ".$categories[cats][$i][1]."</a>
		";
		$olderHash[$categories[cats][$i][2]]=md5($categories[cats][$i][1]+$categories[cats][$i][0]);
	}

	//echo $categories[cats][$i][1]; echo $categories[cats][$i][2];
	//echo "<br>";
	//for ($j=0; $j!==count($categories[cats][$i]);$j++){
	//	echo $categories[cats][$i][$j];
	//}

	// запомним старое значение
	$older=$i;
}

			</div>

		</div>

	</div>*/
?>