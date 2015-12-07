/**
 * Created by bartleby on 07.12.2015.
 */
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 * class=""
 *  item_title - название товара
 *  item_price - цена товара
 *  item_add - добавить товар в корзину
 *
 *  cart_list - DIV элемент с данными корзины
 *
 *  удалить/переписать функции
 *  checkout
 *  clear_cart
 *
 *  нужно добавить
 *  item_remove - удалить из карзины товар
 *  item_increment - увеличить на 1 кол-во товара в корзине
 *  item_decrement - уменьшить на 1 кол-во товара в корзине
 */

$(function() {
    // Всего 2 DIV мы используем фукнцией для отрисовки ALERT и непосредственно КОРЗИНЫ

    // Переменная блока вывода данных корзины
    // class="cart_box"
    var cartBox = $('.cart_box');
    // Переменная блока вывода информационной строки
    // class="cart_alert"
    var cartBoxAlert = $('.cart_alert');
    //var cartBoxTpl = load('tpl_cart.html');
    //alert(cartBoxTpl);

    var ololosh="pipiska";

    // функция получения данных из LocalStorage
    // Через массив "items_cart"
    function getCartData() {
        return JSON.parse(localStorage.getItem('items_cart'));
    }
    // функция записи данных в LocalStorage
    // Через массив "items_cart"
    function setCartData(o) {
        localStorage.setItem('items_cart', JSON.stringify(o));
        return false;
    }

    // Рисуем Alert сообщение
    // cartAlert
    function cartAlert(e) {

        return false;

    }
    /*
     function itemRemove(e){
     cartData = getCartData() || {},
     parentBox = $that.parentsUntil('.cart_item_box'),
     itemId = $that.data('id');

     //$(document).ready(function(){
     $('itemId').remove();
     //});

     return false;
     }
     */


    // Открываем корзину со списком добавленных товаров
    // cartShow
    function cartShow(e) {
        // вытаскиваем данные корзины
        var cartData = getCartData(),
            totalItems = '';

        // если что-то в корзине уже есть, начинаем формировать данные для вывода
        if (cartData !== null) {

            // шаблончик корзины Я ДУМАЮ ВОТ ТУТ НАДО ИНИЦИАЛИЗИРОВАТЬ ЗАГРУЗКУ ШАБЛОНА ИЗ ФАЙЛА
            // а все остальные операции производить внутри функции анонимной в get()

            //totalItems = load('tpl_cart.html');

            // очистим корзину для начала
            $(".cart_box").html('');
            $(".cart_box").append('<tr class="cart_item_box"><th>Наименование</th><th>Цена</th><th>Кол-во</th><th></th></tr>');
            //totalItems = '<tr class="cart_item_box"><th>Наименование</th><th>Цена</th><th>Кол-во</th><th></th></tr>';
            for (var items in cartData) {
                // пока не разобрался с отключением асинхронности или в принципе в подходе к реализации этой функции
                // отключим от греха подальше и просто в переменную передадим нужный нам шаблон
                //$.get("tpl_cart.html",{},function (returnedData){$(".cart_box").append(returnedData);});

                returnedData = '';
                returnedData += '<tr class="new_cart_item_box">';
                returnedData += '    <td align=left class="item_name small">Название</td>';
                returnedData += '    <td class="item_price small">';
                returnedData += '            Цена';
                returnedData += '    </td>';
                returnedData += '    <td class="item_quantity small">';

                returnedData += '            Количество';

                returnedData += '    </td>';

                returnedData += '</tr> ';

                $(".cart_box").append(returnedData);


                //for (var i = 0; i < cartData[items].length; i++){
                // определяем родителя только вставленного
                parentBox = $('.new_cart_item_box');

                $('.new_cart_item_box .item_name').text(cartData[items][1]);
                $('.new_cart_item_box .item_price').text(cartData[items][2]);
                $('.new_cart_item_box .item_quantity').text(cartData[items][3]);
                // высчитываем кол-во товара
                $('.new_cart_item_box .item_summ').text(cartData[items][2] * cartData[items][3]);

                // обновляем значение id товара
                var item_id = cartData[items][0];
                $('.new_cart_item_box .item_remove').data('id', item_id).attr('data-id', item_id);

                //alert (itemName);
                //}
                // заменим new класс на обыденный
                $(".new_cart_item_box").addClass("cart_item_box");
                $(".cart_item_box").removeClass("new_cart_item_box");
                /*
                 totalItems += '<tr>';
                 for (var i = 0; i < cartData[items].length; i++) {
                 totalItems += '<td>' + cartData[items][i] + '</td>';
                 }
                 totalItems += '<td><button class="btn btn-default item_remove" type="submit" data-id=""><span class="glyphicon glyphicon-remove"></span></button></td>';
                 totalItems += '</tr>';
                 }
                 //totalItems += '<table>';

                 cartBox.html(totalItems);
                 */
            }
        } else {
            // если в корзине пусто, то сигнализируем об этом
            cartBox.text('В корзине пусто!');
        }


        return false;
    }

    function openCart(e) {
        var cartData = getCartData(), // вытаскиваем все данные корзины
            totalItems = '';

        // если что-то в корзине уже есть, начинаем формировать данные для вывода
        if (cartData !== null) {

            // шаблончик корзины
            //cart_box_tpl = $('.cart_box').load('tpl_cart.html');

            totalItems = '<table class="cart_list"><tr><th>Наименование</th><th>Цена</th><th>Кол-во</th></tr>';
            for (var items in cartData) {
                totalItems += '<tr>';
                for (var i = 0; i < cartData[items].length; i++) {
                    totalItems += '<td>' + cartData[items][i] + '</td>';
                }
                totalItems += '</tr>';
            }
            totalItems += '<table>';
            cartBox.html(totalItems);

        } else {
            // если в корзине пусто, то сигнализируем об этом
            cartBox.text('В корзине пусто!');
        }
        return false;



    }

    // Функция добавления товара в корзину
    // это дерьмо нужно перестроить логически, так сказать рефакторить
    function addToCart() {
        var $that = $(this),o

        // получаем данные корзины или создаём новый объект, если данных еще нет
        cartData = getCartData() || {},
            // родительские элементы кнопки Добавить в корзину
            parentBox = $that.parentsUntil('.item_box'),

            // ID товара
            itemId = $that.data('id'),
            itemTitle = $('.item_title', parentBox).text(), // название товара
            itemPrice = $('.item_price', parentBox).text(); // стоимость товара
        // тут через запятые нужно всё что содержится внутри локал стоража. так что кол-во чтобы записать тоже надо включить в это множество

        var itemNumber = $('.item_number', parentBox).text(); // количество добавляемого товара
        if (itemNumber < 2){itemNumber=1;};

        $that.prop('disabled', true); // блокируем кнопку на время операции с корзиной
        if (cartData.hasOwnProperty(itemId)) { // если такой товар уже в корзине, то добавляем +N к его количеству
            /* решить вопрос первого добавления. если там значение 0, то он делает + 1 */
            if (itemNumber == 1 || itemNumber == 0 || itemNumber == null) {
                cartData[itemId][3] += 1; // добавим 1
            } else {
                cartData[itemId][3] += +itemNumber; // столько сколько указано
            }

        } else { // если товара в корзине еще нет, то добавляем в объект
            // вот тут непосредственно определяем что пишем в стораж. надо учесть
            // что 0 - это ID товара, а последнее значение числовое сменить на переменную обозначающую кол-во
            cartData[itemId] = [itemId, itemTitle, itemPrice, +itemNumber];
        }

        // Обновляем данные в LocalStorage
        if (!setCartData(cartData)) {
            $that.prop('disabled', false); // разблокируем кнопку после обновления LS
            cartBoxAlert.text('Товар добавлен в корзину.');
            setTimeout(function() {
                cartBoxAlert.text('Продолжить покупки...');
            }, 1000);
        }
        return false;

    }

    /* Добавляем  товар в корзину */
    // class="item_add" - запускает добавление в корзину и перерисовку корзины
    $('.item_add').click(addToCart),
        $('.item_add').click(cartShow);


    /* удаляем товар из корзины */
    /*$('.item_add').click(itemRemove),
     $('.item_add').click(cartShow);*/

    /* Открыть корзину */
    // class="cart_redraw" - запускает перерисовку корзины
    $('.cart_redraw').on('click', cartShow);



    /* Очистить корзину */
    // class="cart_clear" - запускает фукнцию очистки корзины и перерисовки
    $('.cart_clear').on('click', function(e) {
        localStorage.removeItem('items_cart');
        // передаём значение в ALERT
        cartBoxAlert.text('Корзина очишена.');
    });
    $('.cart_clear').on('click', cartShow);


    // Вывод информации о корзине во время прорисовки страницы
    $(document).ready(cartShow);

});