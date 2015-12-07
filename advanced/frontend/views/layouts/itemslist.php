<?php
/**
 * Created by PhpStorm.
 * User: bartleby
 * Date: 06.12.2015
 * Time: 22:04
 *
 * Рисуем 3 колонки для отображение каталога, контента, корзины
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>



<div class="container-fluid">
    <div class="row content">
        <div class="col-sm-2 text-left">
            <div class="row">
                <div class="col-sm-2 text-left affix">
                    <br>
                    <?php
                    $this->beginContent('@frontend/views/site/catalog.php');
                    echo $content;
                    $this->endContent();
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-8 text-left">
            <br>



            <?= $content ?>
        </div>
        <div class="col-sm-2 text-left">
            <div class="row">
                <div class="col-sm-2 text-left affix">
                    <br>
                    <?php
                    $this->beginContent('@frontend/views/site/cart.php');
                    echo $content;
                    $this->endContent();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>