<?php
/**
 * Created by PhpStorm.
 * User: bartleby
 * Date: 06.12.2015
 * Time: 22:44
 */
$this->title .= 'BayEbay - Просмотр товара';
$this->params['myMod'][] = $model;

//echo $model->singleItemId;
?>
<div class="row">
    <div class="col-lg-6">

        <!-- Modal Всплывающее окно просмотра изображения-->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="width:900px;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    </div>
                    <div class="modal-body">
                        <center>
                            <img src="" id="preview">
                        </center>
                    </div>
                </div>
            </div>
        </div>


        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Indicators индикаторы на каком слайде находимся-->
            <ol class="carousel-indicators">

                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>

            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner">

                <div class="item active">
                    <center>
                        <img class="zoom image img-thumbnail" style="height: 450px;" src="<?= $result[0]['galleryURL']; ?>" alt="">
                    </center>
                </div>


            </div>

            <!-- Controls клавиши управления -->
            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </div>


    </div>

    <div class="col-lg-6">

        <div class="item_box">
            <div class="row">
                <div class="col-md-12 item_title2">
                    <h3><?= $result[0]['title']; ?></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3><small>Лот номер: <?= $result[0]['ebay_item_id']; ?></small></h3>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="item_title hidden"><?= $result[0]['title']; ?></div>
                    <div class="input-group">
                        <div class="input-group-addon item_price"><?= $result[0]['current_price_value']; ?> <span class="glyphicon glyphicon-rub"></span></div>
                        <div class="input-group-addon hidden"><span class="glyphicon glyphicon-chevron-left"></span></div>
                        <input name="quantity" type="text" value="1" min="1" max="9999" maxlength="5" class="form-control item_number hidden" autocomplete="off" />
                        <div class="input-group-addon hidden"><span class="glyphicon glyphicon-chevron-right"></span></div>
                        <div class="input-group-btn">
                            <button class="btn btn-default addToCart" data-id="<?= $result[0]['ebay_item_id']; ?>" type="submit"><span  class="glyphicon glyphicon-shopping-cart"></span></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>