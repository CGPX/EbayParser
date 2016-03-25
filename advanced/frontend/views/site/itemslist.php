<?php
/**
 * Файл отвечающий за вывод навигации по каталогу (так сказать айтем лист)
 */

use common\models\EbayCategory;
use frontend\widgets\CustomFilter;
use yii\helpers\Url;

$this->title = 'BayEbay - Каталог';
$this->params['myMod'][] = $model;

?>
<?= CustomFilter::widget(['model' => $model])?>
<div class="row">
    <?php
    if ($result!==false) {
        foreach ($result as $value1) {
            if (!isset($value1['galleryURL'])){
                $value1['galleryURL']="imgs/image-not-found.jpg";
            }else{

            }
    ?>
    
    <?php
    $value1['galleryURL'] = preg_replace("/http:\/\//", "https://", $value1['galleryURL']);
    echo '<div class="item">
                      
                          <table class="table table-striped">
                              <tr>
                                  <td rowspan="2" class="item_img" align="center" style="width:140px;"><a href='.Url::to(['site/single', 'ebayitemid' => $value1['ebay_item_id']]).'" >'
            . '<img style="max-width:140px;max-height:140px;" src="'. $value1['galleryURL'] .'" class="img-responsive" style=""></a></td>
<td class="item_title" >
    <h2 class=""><a href="'.Url::to(['site/single', 'ebayitemid' => $value1['ebay_item_id']]).'" >'. $value1['title'] .'</a></h2>
       <span class="item_price">Цена: '. $value1['price_shipping_sum'] .' Руб.</span>
           <button class="addToCart" data-id="'. $value1['ebay_item_id'] .'">Заказать</button>
            </td>
                            </tr>
                          </table>
                  </div>';
        }
    }elseif(isset($model->emptyResponse)) {
        if ($model->emptyResponse) {
        $ebay_cat = EbayCategory::findOne([
            'category_id' => $model->queryCategory,
        ]);
        echo '<div class="col-md-4">К сожалению, по вашему запросу <b>"' . $model->queryText . '"</b> в категории <b>"' . $ebay_cat->category_name . '"</b> ничего не найдено... Попробуйте изменить параметры поиска.</div>';
    }
    }
    ?>
</div>

<?php
if(!$model == false) {
$modelPageCount =  $model->pageCount;

$pageOut=""; $pageActive="";
$pageStart=(int)$model->queryPage - 5;
if($pageStart<=0){$pageStart=1;}
$pageLast=(int)$model->queryPage + 5;
if($pageLast>(int)$model->pageCount){$pageLast=(int)$model->pageCount;}

$iScheti=$pageLast-$pageStart;

    $pagesOut = '';
for ($i=0; $i<=$iScheti; $i++){
    $pageOlolo = $pageStart + $i;
    if ((int)$model->queryPage == $pageOlolo) {$pageActive="active";} else {$pageActive="";}
    //$pagesOut .='<li class="'.$pageActive.'"><a href="#" class="pageChange" data-target="'.$pageOlolo.'">'. $pageOlolo .'</a></li>';
    $pagesOut .='<li class="'.$pageActive.'"><a href="'.$urlFromModel.'&page='.$pageOlolo.'" class="pageChange" data-target="'.$pageOlolo.'">'. $pageOlolo .'</a></li>';
}

if ($model->pageCount>0) {
    echo "
        <div class=\"row\">
            <div class=\"col-sm-12\">
                <ul class=\"pagination\">

                    <li><a href=\"".$urlFromModel."\" title=\"Первая страница\" class=\"pageChange\" data-target=\"1\">&laquo;</a></li>
                    ". $pagesOut ."
                    <li><a href=\"".$urlFromModel."&page=".$modelPageCount."\" title=\"Последняя страница\" class=\"pageChange\" data-target=\"". $modelPageCount  ."\">&raquo;</a></li>

                </ul>
            </div>
        </div>
";}
}