/**
 * Created by bartleby on 08.12.2015.
 */

$(function() {
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
        $('#ebayform-querycategory').attr('value', catId);
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
});