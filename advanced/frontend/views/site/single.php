<?php
/**
 * Created by PhpStorm.
 * User: Hank
 * Date: 06.12.2015
 * Time: 22:44
 */
$this->title .= 'BayEbay - Просмотр товара';
$this->params['myMod'][] = $model;

$tabs = '';
$img = '';
$active = 'active';
$i = 0;
foreach ($images as $image) {
    if ($i > 0) {
        $active = '';
    }
    $img .= '<div class="item ' . $active . '" align="center"><img class="zoom image img-thumbnail item_img" style="max-height: 450px;" src="' . $image['image_url'] . '" alt=""></div>';
    $tabs .= '<li data-target="" data-slide-to="' . $i . '" class="active"></li>';
    $i++;
}
?>

    <div class="row">
        <div class="col-md-4">
            <?php
            $image = array_shift($images);
            echo '<img class="img-responsive" src="' . $image['image_url'] . '" alt="">';
            ?>
        </div>

        <div class="col-md-8">
            <div class="item_box">
                <div class="row">
                    <div class="col-md-12 item_title2">
                        <h1><?= $result[0]['title']; ?></h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h2>Лот номер: <?= $result[0]['ebay_item_id']; ?></h2>
                        <h2>Состояние: <?= $result[0]['condition_display_name']; ?></h2>
                        <h2><a target="_blank" href="<?= $result[0]['viewItemURL']; ?>" rel="nofollow noindex">Описание
                                на Ebay</a></h2>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="item_title hidden"><?= $result[0]['title']; ?></div>
                        <div class="item_img hidden"><img src="<?= $result[0]['galleryURL']; ?>"></div>
                        <div class="input-group">
                            <div class="input-group-addon item_price"><?= $result[0]['price_shipping_sum']; ?> <span
                                    class="glyphicon glyphicon-rub"></span></div>
                            <div class="input-group-addon hidden"><span class="glyphicon glyphicon-chevron-left"></span>
                            </div>
                            <input name="quantity" type="text" value="1" min="1" max="9999" maxlength="5"
                                   class="form-control item_number hidden" autocomplete="off"/>
                            <div class="input-group-addon hidden"><span
                                    class="glyphicon glyphicon-chevron-right"></span></div>
                            <div class="input-group-btn">
                                <button class="btn btn-default addToCart btn-success"
                                        data-id="<?= $result[0]['ebay_item_id']; ?>" type="submit"> Оформить заявку
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

<?php if (sizeof($images) > 0) { ?>
    <div class="row">
        <h2>Изображения товара</h2>
        <?php

        foreach ($images as $image) {
            echo '<p><img class="image" src="' . $image['image_url'] . '" alt=""></p>';
        }
        ?>
        <button class="btn btn-default addToCart btn-success" data-id="<?= $result[0]['ebay_item_id']; ?>"
                type="submit"> Оформить заявку на покупку
        </button>
    </div>
<?php } ?>