<?php
/**
 * Created by PhpStorm.
 * User: bartleby
 * Date: 06.12.2015
 * Time: 22:47
 *
 * Файл отвечающий за вывод шаблона корзины



<div class="row">
    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title text-uppercase">
                    КОРЗИНА <span class="glyphicon glyphicon-shopping-cart pull-right" aria-hidden="true"></span>
                </h4>
            </div>
            <div id="collapse1" class="panel-collapse collapse in">

                <!-- Форма корзины START -->
                <table class="table table-hover cart_box">



                </table>

                <div class="panel-footer"><center><strong></strong><button class="btn btn-default cart_clear" type="submit">Очистить</button></center></div>
                <!-- Форма корзины END -->

            </div>
        </div>
    </div>
</div>
 */
?>

<div class="list-group">

    <!-- Всплывающее сообщение -->
    <div class="alert alert-success cgp-absolute col-sm-12 hidden cartAlert">Allert MSG</div>

    <div class="panel panel-default">

        <div class="panel-heading" style="height:40px;">
            <h4 class="panel-title text-uppercase">
                <span class="pull-left">КОРЗИНА</span> <span class="glyphicon glyphicon-shopping-cart pull-right" aria-hidden="true"></span>
            </h4>
        </div>

        <?= \Yii::$app->view->renderFile('@app/views/site/cart-inside.php'); ?>

        <div class="panel-footer text-center"><button class="btn btn-danger btn-sm cartClear" type="submit">Очистить</button> <button class="btn btn-primary btn-sm" type="submit" onclick="location.href = '?r=site/order'">Оформить</button></div>
    </div>
</div>
