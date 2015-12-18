/**
 * Created by bartleby on 08.12.2015.
 */

$(function() {
    /**
     * Объявление переменных
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

    /** Перечень функций js/jq
     */

    /**
     * post передача запроса на смену категории
     */
    function catChange(){
        // забираем данные атрибута ИД
        catId=$(this).attr('data-target');
        //alert(catId);
        // закидываем в инпут новые данные ИД перехода

        /*$('#ebayform-querycategory').each(function(){
            alert($(this).attr('value', "6030"));
        });*/
        $('#ebayform-querycategory').attr('value', catId.substr(1));
        //$("title = 'EbayForm[queryCategory]'").attr('value', "6030");
        //$("name = 'EbayForm[queryCategory]'").html('');

        // жмём сабмит ;(
        $("#ebay-form").submit();

        return false;
    }

    /**
     * Инициализируем клик
     * Для инициализации дописываем class="catChange"
     */
    $('.catChange').click(catChange);


    /**
     * Post Передача информации из корзины в форму оформления заказа
     */
    function orderPlace(){
        $('.OrderForm-itemslist').text(JSON.stringify(getCartData()));
    }


    /**
     * Инициализируем заполнение формы оформления заказа
     * Активация по загрузке страницы
     */
    $(document).ready(orderPlace);
});