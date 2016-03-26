/**
 * Created by bartleby on 18.12.2015.
 */
$(function() {
    /**
     * Объявление глобальных переменных
     */

    /**
     * Функции работы с LocalStorage
     */
    function getCartData() {
        return JSON.parse(localStorage.getItem('items_cart'));
    }
    function setCartData(o) {
        localStorage.setItem('items_cart', JSON.stringify(o));
        return false;
    }

    /**
     * Post Передача информации из корзины в форму оформления заказа
     * ===============================================================
     */
    function orderPlace(){
        $('.OrderForm-itemslist').text(JSON.stringify(getCartData()));
    }
    // Инициализируем заполнение формы оформления заказа
    // Активация по загрузке страницы
    $(document).ready(orderPlace);

    /**
     * Отобразить информацию о корзине
     * ===============================================================
     * ищем класс new_cart_item_box
     * копируем всё дерево dom и переименовываем класс в cart_item_box
     * удаляем hidden класс из дива cart_item_box
     * заполняем данные
     *
     * classes
     * ================
     * cat_items_list - заглавный класс содержимого корзины
     * new_cart_item_box - класс hidden элемента корзины оригинала
     * cart_item_box_edit - промежуточный класс элемента корзины для заполнения
     * cart_item_box - финальный класс элемента корзины
     */
    function cartDraw(){
        // вынимаем данные о корзине
        var cartData = getCartData();
        var totalPrice=0;
        // если корзина не пустая, то формируем данные для вывода
        $(".cat_items_list").text("");
        if (cartData !== null){
            for (var items in cartData) {

                // повторяем операцию в каждой итерации
                $(".new_cart_item_box").clone()
                    .addClass("cart_item_box_edit")
                    .appendTo(".cat_items_list").removeClass("new_cart_item_box").removeClass("hidden");

                    $(".cart_item_box_edit").attr('data-id',cartData[items][0]);
                    $(".cart_item_box_edit .item_name").html(cartData[items][1]);
                    $(".cart_item_box_edit .item_price").html(cartData[items][2]);
                    $(".cart_item_box_edit .item_quantity").html(cartData[items][3]);
                    $(".cart_item_box_edit .item_img").attr('src', cartData[items][4]);
                    $(".cart_item_box_edit .item_img").attr('alt', cartData[items][1]);
                $(".cart_item_box_edit").addClass("cart_item_box").removeClass("cart_item_box_edit");

                totalPrice = +totalPrice + +cartData[items][2] * +cartData[items][3];
            }
        } else {
            // корзина пуста, отображаем соответствующую информацию
            $(".new_cart_item_box").clone()
                .addClass("cart_item_box_empty")
                .appendTo(".cat_items_list").removeClass("new_cart_item_box").removeClass("hidden");
            $('.cart_item_box_empty').text("Корзина пуста.")
        }
        $(".itogo").html(totalPrice);
        return false;
    }
    // рисуем корзину при загрузке страницы
    $(document).ready(cartDraw);

    /**
     * Всплывающие сообщения в корзине
     * class = cartAlert
     */
    function cartAlert(text, hidden){
        if(hidden==1){
            $('.cartAlert').removeClass('hidden').html("").addClass('hidden');
        } else if (hidden==0){
            $('.cartAlert').removeClass('hidden').html(text);
        }
        return false;
    }

    /**
     * Очистка корзины
     */
    function cartClear(){
        localStorage.removeItem('items_cart');
        cartAlert('Корзина очищена.',0);
        setTimeout(function() {
            cartAlert('Продолжить покупки...',1);
        }, 2000);
        return false;
    }
    $('.cartClear').click(cartClear).click(cartDraw);

    /**
     * Добавляем товар в корзину
     */
    function addToCart() {
        /** тут надо зарефакторить, чтобы поиск айтем ида был в одном месте, в диве заголовке */
            var $that = $(this),
            // получаем данные корзины или создаём новый объект, если данных еще нет
            cartData = getCartData() || {},
            // родительские элементы кнопки Добавить в корзину
            parentBox = $that.parentsUntil('.item_box'),
            // ID товара
            itemId = $that.data('id'),
            itemTitle = $('.item_title', parentBox).text(), // название товара
            itemPrice = $('.item_price', parentBox).text(), // стоимость товара
            itemImg = $('.item_img', parentBox).children('img').attr('src'); // изображение товара
            // тут через запятые нужно всё что содержится внутри локал стоража. так что кол-во чтобы записать тоже надо включить в это множество
            var itemNumber = $('.item_number', parentBox).text(); // количество добавляемого товара
        if (itemNumber < 2){itemNumber=1;}
        //$that.prop('disabled', true); // блокируем кнопку на время операции с корзиной
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
            cartData[itemId] = [itemId, itemTitle, itemPrice, +itemNumber, itemImg];
        }
        // Обновляем данные в LocalStorage
        if (!setCartData(cartData)) {
            cartAlert('Товар добавлен в корзину.',0);
            setTimeout(function() {
                cartAlert('Продолжить покупки...',1);
            }, 2000);
        }
        return false;
    }
    $('.addToCart').click(addToCart).click(cartDraw);

    /**
     * Удаление единственного товара из корзины
     */
    function removeItem(itemRemove){
        cartData = getCartData();
        if (itemRemove == '[object Object]') {
            itemId = $(this).closest('.cart_item_box').data('id');
        } else {
            itemId = itemRemove;
        }
        if (cartData !== null){
            if (cartData.hasOwnProperty(itemId)) {
                askConfirm = confirm('Вы действительно хотите удалить этот товар из корзины?');
                if (askConfirm==true){
                    delete cartData[itemId];
                    if(!setCartData(cartData)){}
                }
            }
        } else {
            alert("Данный товар в корзине не обнаружен!");
        }
        return false;
    }
    $(document).on("click", ".removeItem", removeItem).on("click", ".removeItem", cartDraw);

    /**
     * Item Increment
     */
    function itemInc(){
            cartData = getCartData();
            itemId = $(this).closest('.cart_item_box').data('id');
        if (cartData.hasOwnProperty(itemId)) {
            cartData[itemId][3] += 1;
        }
        if (!setCartData(cartData)) {
        }
        return false;
    }
    $(document).on("click", ".itemInc", itemInc).on("click", ".itemInc", cartDraw);

    /**
     * Item Decrement
    */
    function itemDec(){
        cartData = getCartData();
        itemId = $(this).closest('.cart_item_box').data('id');
        if (cartData.hasOwnProperty(itemId)) {
            if (+cartData[itemId][3]>1) {
                cartData[itemId][3] -= 1;
            } else if (+cartData[itemId][3]===1) {
                removeItem(itemId);
            }
        }
        if (!setCartData(cartData)) {
        }
        return false;
    }
    $(document).on("click", ".itemDec", itemDec).on("click", ".itemDec", cartDraw);

    /**
     * Переход в просмотр товара
     */
    function singleChange(){
        itemId=$(this).attr('data-target');
        $('#ebayform-singleitemid').attr('value', itemId);
        $("#ebay-form").submit();

        return false;
    }
    $('.singleChange').click(singleChange);

    /**
     * Работа аккордеона каталога
     * одновременно с работой аккордеона должна отработать и окраска
     * <button class='catC2hange' onclick="$('#6030').collapse('toggle');">Test</button>
     */
    function accordActivation(){
        activateClass=$(this).data('target');
        $(activateClass).collapse('toggle');
        return false;
    }
    $('.catChange').click(accordActivation);

    /**
     * Применяем фильтр
     * class
     * filter_box - содержимое всех выпадающих меню
     * filter_change - активатор (клавиша) функции применения фильтра
     *
     * filter_ts - select типов транспортных средств
     * filter_brands - select производителей
     * filter_models - select моделей
     */

    $('.filter_change').click(FilterControl.filterAction);

    /**
     * Пушим запрос
     */

    $('.filter_query').click(FilterControl.queryTextAction);
    $('.filter_query_input').keydown(function( event ){if ( event.which == 13 ){FilterControl.queryTextAction()}});

    /**
     * Смена страницы
     */

    $('.pageChange').click(FilterControl.pageAction);
    $('.categoryChange').click(FilterControl.categoryAction);

    /**
     * post передача запроса на смену категории
     * ===============================================================
     */
    $('.catChange').click(FilterControl.categoryAction);

    /**
     * Триггер на изменении выбора ТС
     */
    function filterTs(onload) {
        var tsId = $( ".filter_ts option" ).filter(':selected').attr('value');
        //if (onload=="onload"){alert("OLOLoSH")};
        if (tsId=='null'){
            //alert('Вы не выбрали тип ТС');
        }else{
            $('.filter_box .filter_brands option').each(function(){
                //$(this).removeClass('hidden');
                //$(this).addClass('hidden');
                brandIdThis=$(this).data('id');
                if (brandIdThis == tsId) {
                    $(this).removeClass('hidden');
                }
                if (onload!=="onload") {
                    if ($(this).attr('value')=='null')
                    {
                        $(this).removeClass('hidden').attr("selected", "selected");
                    } else {
                        this.selected=false;
                    }
                };
            });
            $('.filter_box .filter_models option').each(function() {
                $(this).removeClass('hidden');
                $(this).addClass('hidden');
                if (onload!=="onload") {
                    if ($(this).attr('value')=='null')
                    {
                        $(this).removeClass('hidden').attr("selected", "selected");
                    }else{
                        this.selected=false;
                    }
                };
            });
        }
    }
    $( ".filter_ts" ).change(filterTs);
    $(document).ready(function(){filterTs('onload')});

    /**
     * Триггер на изменение выбора Бренда
     */
    function filterBs(onload) {
        var brandId = $(".filter_brands option").filter(':selected').attr('value');
        if(brandId=='null'){
            //alert('Вы не выбрали марку ТС');
        }else{
            $('.filter_box .filter_models option').each(function()
            {
                $(this).removeClass('hidden');
                $(this).addClass('hidden');
                modelIdThis=$(this).data('id');
                if (modelIdThis == brandId) {
                    $(this).removeClass('hidden')
                }
                if (onload!=="onload") {
                    if ($(this).attr('value')=='null') {
                        $(this).removeClass('hidden').attr("selected", "selected")
                    }else {
                        this.selected=false;
                    }
                };
            });
        }
    }
    $( ".filter_brands" ).change(filterBs);
    $(document).ready(function(){filterBs('onload')});
});