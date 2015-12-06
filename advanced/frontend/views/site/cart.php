<?php
/**
 * Created by PhpStorm.
 * User: bartleby
 * Date: 06.12.2015
 * Time: 22:47
 *
 * Файл отвечающий за вывод шаблона корзины
 */
?>

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
                <table class="table table-hover">

                    <tr class="new_cart_item_box">
                        <td align=left class="item_name">Название</td>
                        <td class="item_price">
                            10
                        </td>
                        <td class="item_quantity">

                            2

                        </td>
                        <td>
                            <button class="btn btn-default item_remove" type="submit" data-id="0"><span class="glyphicon glyphicon-remove"></span></button>
                        </td>
                    </tr>

                    <tr class="new_cart_item_box">
                        <td align=left class="item_name">Название</td>
                        <td class="item_price">
                            320
                        </td>
                        <td class="item_quantity">

                            4

                        </td>
                        <td>
                            <button class="btn btn-default item_remove" type="submit" data-id="0"><span class="glyphicon glyphicon-remove"></span></button>
                        </td>
                    </tr>

                </table>

                <div class="panel-footer"><strong></strong><button class="btn btn-default " type="submit">Оформить</button></div>
                <!-- Форма корзины END -->

            </div>
        </div>
    </div>
</div>