<?php
/**
 * Created by PhpStorm.
 * User: bartleby
 * Date: 06.12.2015
 * Time: 22:46
 *
 * Файл отвечающий за вывод навигации по каталогу (так сказать айтем лист)
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'BayEbay - Каталог';
?>

    <?php $form = ActiveForm::begin(['id' => 'ebay-form']); ?>
<div class="col-sm-12" xmlns="http://www.w3.org/1999/html">
    <div class="input-group">

        <input type="text" class="form-control" placeholder="Ищем запчасти..." id="ebayform-querytext" name="EbayForm[queryText]">
        <input type="text" placeholder="Определяем категорию..." id="ebayform-querycategory" name="EbayForm[queryCategory]" value="173511" >

                              <span class="input-group-btn">
                                <?= Html::submitButton('Найти <span class="glyphicon glyphicon-search" aria-hidden="true"></span>', ['class' => 'btn btn-success', 'name' => 'ebay-button']) ?>
                              </span>

    </div>
    </div>
    <?php ActiveForm::end(); ?>
<br /><br /><br />

<?php
if ($result!==false) {
    foreach ($result as $value1) {
        //echo"<hr>"; <td class="item_number">5</td>
        //var_dump($value1);
        //echo $value1->title;
        //echo $value1[title];

        echo "
            <div class=\"col-md-4\" style=\"height:400px;\">
                <div class=\"panel panel-default item_box\">
                    <table class=\"table table-striped\">
                        <tr>
                            <td class=\"item_title\" style=\"height:80px;\">". $value1[title] ."</td>
                        </tr>
                        <tr>
                            <td><center><img src=\"". $value1[galleryURL] ."\" class=\"img-responsive\" style=\"height:220px; width:200px;\" alt=\"Image\"></center></td>
                        </tr>
                        <tr>
                            <td>Цена: <span class=\"item_price\">". $value1[current_price_value] ."</span>р <button class=\"btn btn-default pull-right item_add\" data-id=\"". $value1[ebay_item_id] ."\">Купить сейчас <span class=\"glyphicon glyphicon-shopping-cart\" aria-hidden=\"true\"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        ";
    }
}

?>
