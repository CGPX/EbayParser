<?php
/**
 * Created by PhpStorm.
 * User: bartleby
 * Date: 18.12.2015
 * Time: 15:59
 */
$this->title .= 'BayEbay - Успех';
?>

<div class="row">
    <div class="col-lg-12">
        <div class="jumbotron">
            <h1>Ваша заказ успешно размещен.</h1>
            <p>Данные успешно отправлены оператору. После обработки вашей заявки с вами свяжутся. Спасибо за то, что пользуетесь услугами нашего сайта.</p>
            <p><button class="btn btn-primary btn-lg" role="button" action="site/index">Вернуться к сайту</button></p>
        </div>
    </div>


</div>

<script>
    // очистка корзины
    localStorage.removeItem('items_cart');
</script>