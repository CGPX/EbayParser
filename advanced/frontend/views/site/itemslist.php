<?php
/**
 * Created by PhpStorm.
 * User: bartleby
 * Date: 06.12.2015
 * Time: 22:46
 *
 * Файл отвечающий за вывод навигации по каталогу (так сказать айтем лист)
 */

use common\models\EbayCategory;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'BayEbay - Каталог';
$this->params['myMod'][] = $model;

?>
<div class="row">
    <?php
    if ($result!==false) {
        foreach ($result as $value1) {
            echo "
                  <div class=\"col-md-4\" style=\"height:400px;\">
                      <div class=\"panel panel-default item_box\">
                          <table class=\"table table-striped\">
                              <tr>
                                  <td class=\"item_title\" style=\"height:80px;\"><h5 class=\"\"><a href=\"".Url::to(['site/single', 'ebayitemid' => $value1[ebay_item_id]])."\" >". $value1[title] ."</a></h5></td>
                              </tr>
                              <tr>
                                  <td align=\"center\"><img src=\"". $value1[galleryURL] ."\" class=\"img-responsive\" style=\"height:220px; width:100%;\" alt=\"Image\"></td>
                              </tr>
                              <tr class=\"\">
                                  <td class=\"\">

                      <span class=\"glyphicon glyphicon-rub btn-sm\"></span><span class=\"item_price\">". $value1[price_shipping_sum] ."</span>
                      <button class=\"btn btn-default btn-sm pull-right addToCart\" data-id=\"". $value1[ebay_item_id] ."\">Купить сейчас <span class=\"glyphicon glyphicon-shopping-cart\" aria-hidden=\"true\"></span>
                    </td>
                              </tr>
                          </table>
                      </div>
                  </div>
        ";
        }
    }elseif($model->emptyResponse) {
        $ebay_cat = EbayCategory::findOne([
            'category_id' => $model->queryCategory,
        ]);
        echo '<div class="col-md-4">К сожалению, по вашему запросу <b>"'.$model->queryText.'"</b> в категории <b>"'.$ebay_cat->category_name.'"</b> ничего не найдено... Попробуйте изменить параметры поиска.</div>';
    }
    ?>
</div>

<?php
$modelPageCount =  $model->pageCount;

$pageOut=""; $pageActive="";
$pageStart=(int)$model->queryPage - 5;
if($pageStart<=0){$pageStart=1;}
$pageLast=(int)$model->queryPage + 5;
if($pageLast>(int)$model->pageCount){$pageLast=(int)$model->pageCount;}

$iScheti=$pageLast-$pageStart;


for ($i=0; $i<=$iScheti; $i++){
    $pageOlolo = $pageStart + $i;
    if ((int)$model->queryPage == $pageOlolo) {$pageActive="active";} else {$pageActive="";}
    $pagesOut .='<li class="'.$pageActive.'"><a href="#" class="pageChange" data-target="'.$pageOlolo.'">'. $pageOlolo .'</a></li>';
}

if (isset($model->pageCount)){
    echo "
        <div class=\"row\">
            <div class=\"col-sm-12\">
                <ul class=\"pagination\">

                    <li><a href=\"#\" title=\"Первая страница\" class=\"pageChange\" data-target=\"1\">&laquo;</a></li>
                    ". $pagesOut ."
                    <li><a href=\"#\" title=\"Последняя страница\" class=\"pageChange\" data-target=\"". $modelPageCount  ."\">&raquo;</a></li>

                </ul>
            </div>
        </div>
";}
?>