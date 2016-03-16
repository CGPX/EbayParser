<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\models\EbayCategory;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap\ActiveForm;

AppAsset::register($this);
$qText ='';
$SearchForm="hidden"; // форма поиска на всех страницах. по сути она либо скрыта, либо показана.
$controlAction = Yii::$app->controller->action->id;
if (Yii::$app->controller->action->id=="itemslist" or Yii::$app->controller->action->id=="get-items-by" or Yii::$app->controller->action->id=="get-item-by-query"){
    $FilterForm="";
    $createBreadArray = array();
    function createBread($whatCatSearch){
        global $createBreadArray;
        $catArray=\common\models\EbayCategory::find()->where(['category_id'=>$whatCatSearch])->asArray()->all();
        if(isset($catArray[0]['category_id'])){
            $createBreadArray[] = ['label' => $catArray[0]['category_name'], 'category_id' => $catArray[0]['category_id']];
            createBread($catArray[0]['category_parent_id']);
            return $createBreadArray;
        }
    }
    $BreadArray=createBread($this->params['myMod'][0]['queryCategory']);
    if(isset($BreadArray)){
        foreach (array_reverse($BreadArray) as $item) {
            $this->params['breadcrumbs'][] = ['label' => $item['label'], 'url' => '/category/'.$item['category_id'], 'category_id' => $item['category_id']];
        }
    }
}else{$FilterForm="hidden";}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
   <head>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta charset="<?= Yii::$app->charset ?>">
      <meta name="title" content="Доставка с EBAY | Автозапчасти из США на заказ от интернет-магазина Destinyparts.ru"/>
      <meta name="keywords" content="запчасти из сша заказать автозапчасти на заказ"/>
      <meta name="description" content="Интернет-магазин Destinyparts.ru предлагает оригинальные и неоригинальные запчасти из США. Доставка автозапчастей осуществляется в кратчайшие сроки."/>
      <?= Html::csrfMetaTags() /* вот это что за борода? */ ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
      
      <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
      <link rel="icon" href="/favicon.ico" type="image/x-icon"/>
      <link rel="apple-touch-icon" href="/_sysimg/ios/apple-touch-icon.png"/>
      <link rel="apple-touch-icon" href="/_sysimg/ios/apple-touch-icon-76x76.png" sizes="76x76"/>
      <link rel="apple-touch-icon" href="/_sysimg/ios/apple-touch-icon-120x120.png" sizes="120x120"/>
      <link rel="apple-touch-icon" href="/_sysimg/ios/apple-touch-icon-144x144.png" sizes="144x144"/>
      <link rel="apple-touch-icon" href="/_sysimg/ios/apple-touch-icon-152x152.png" sizes="152x152"/>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <link rel="stylesheet" type="text/css" href="/_css/main.css" />
      <link rel="stylesheet" type="text/css" href="/_css/ar2.css" />
      <link rel="stylesheet" type="text/css" href="/_css/mavselect.css" />
      <link rel="stylesheet" type="text/css" href="/_css/slide_menu.css" />
      <link rel="stylesheet" type="text/css" href="/_css/slider.css" />
      <style>
        .item table td {
            padding: 5px;
        }
        .item .item_price {
            font-size: 200%;
            color: #00A000;
        }
        .item button.addToCart{
            padding: 7px;
            font-size: 24px;
            cursor: pointer;
        }
        
        ul.menu {
            margin-left: 30px;
        }
        
        li.active a.alev1 {
            color: black !important;
            font-weight: bold;
        }
        
         a.active > span {
            color: black !important;
            font-weight: bold;
        }
        
        ul.menu  ul {
            margin-left: 15px;
        }
        .hidden {
            display: none;
            
        }
      </style>
   </head>
   <body id="page" class="h1_border">
       <?php $this->beginBody() ?>
      <div id="wrapper">
         <div id="header">
            <div id="header_inner">
               <div id="header_top" class="flc">
                  <p style="position: absolute;color: #fff;margin-left: 54px;margin-top: 68px;font-weight: bold;">Интернет-магазин автозапчастей</p>
                  <a class="logo" href="/">
                     <!--<img src="/images/template/logo.png"></a>-->
                     <img src="/images/logo1.png">
                  </a>
                  <div class="header_data_blocks">
                     <div class="auth_header">
                        <div class="auth_title">Авторизация:</div>
                        <div class="auth_link">
                           <a class="show_form">Вход</a> / <a href="/registration.html">Зарегистрироваться</a>
                        </div>
                        <div id="CustomerBasketId" class="">
                           <div class="auth_form">
                              <div class="auth_form_inner">
                                 <a class="auth_close"></a>  
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="contacts_header">
                        <div class="pageContent">
                           <div class="contacts_title">Наш телефон</div>
                           <div class="phone_header"><span>+7(499)</span> 994-994-3</div>
                        </div>
                     </div>
                     <div class="basket_header">
                        <a class="basket_link" href="/shop/basket.html"><img src='/images/template/header_basket.png'></a>
                        <div class="basket_title">Ваша корзина</div>
                        <div class="data_basket">
                           <span class="title_data_basket leftside">Товаров </span> 
                           <a href="/shop/basket.html">0</a>
                        </div>
                        <div class="data_basket">
                           <span class="title_data_basket leftside">Сумма: </span>
                           <a href="/shop/basket.html">0  руб</a>
                        </div>
                     </div>
                     <!---для растягивания inline-block по ширине--->
                     <div class="ftr"></div>
                  </div>
               </div>
               <div id="header_bottom" class="flc">
                  <div class="search_menu">
                     <ul>
                        <li >
                           <div>
                              <a class=""  href="/">Главная</a>
                           </div>
                        </li>
                        <li >
                           <div>
                              <a class=""  href="http://www.destinyparts.ru/about/kompany/">О компании</a>
                           </div>
                        </li>
                        <li >
                           <div>
                              <a class=""  href="/catalogs/">Каталог</a>
                           </div>
                        </li>
                        <li >
                           <div>
                              <a class=""  href="http://www.destinyparts.ru/dostavka.html">Доставка</a>
                           </div>
                        </li>
                        <li >
                           <div>
                              <a class=""  href="http://www.destinyparts.ru/kontacty.html">Контакты</a>
                           </div>
                        </li>
                     </ul>
                     <div class="search_form">
                        <form name="search_code" action="/search.html" method="GET" onsubmit="">
                           <input class="search_btn" type="image" src="/images/template/search_btn.png" />
                           <div id="search_input" class="">
                              <input class="TextBox_empty" type="text" name="article" value="Например, Carbon hood" onfocus="if (this.value == 'Например, VM12-89') {this.value = ''; this.className = 'TextBox_focus';}" onblur="if (this.value == '') {this.value = 'Например, Carbon hood'; this.className = 'TextBox_empty';} else {this.className = 'TextBox';}" />
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div id="middle">
            <div id="container">
               <div id="content">
                  <div id="content_inner">
                      <div class="pageContent">
                          <article class="col-sm-12 col-md-8 col-lg-8">
                                <!--Содержимое-->

                                <!-- хлебные крошки -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <!-- хлебные крошки -->
                                        <?= Breadcrumbs::widget([
                                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                        ]) ?>

                                        <!-- виджет alert сообщений -->
                                        <?= Alert::widget() ?>
                                    </div>
                                </div>

                                <!-- поисковая форма -->
                                <div class="row">
                                    <div class="col-lg-12">

                                        <div class="input-group">
                                            <?php  if(isset($this->params['myMod'])) {$qText = $this->params['myMod'][0]['queryTextShow']; } ?>
                                            <input type="text" class="form-control filter_query_input" placeholder="Ищем запчасти..." id="ebayform-querytext" name="EbayForm[queryText]" value="<?=$qText?>">
                                              <span class="input-group-btn">
                                                <button class="btn btn-success filter_query" type="button">Найти <span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                              </span>
                                        </div>

                                    </div>
                                </div>

                                <br />
                                <div class="row <?= $FilterForm ?>">
                                    <div class="col-lg-12">
                                        <form class="form-inline filter_box" role="form">
                                            <div class="form-group">
                                                <select class="form-control input-sm filter_ts">
                                                    <option value="null">Выберите тип ТС</option>
                                                    <option <?php if(isset($this->params['myMod'][0]['queryCategory'][0])) { if(EbayCategory::findOne(['category_id' => $this->params['myMod'][0]['queryCategory'][0]])->category_root_parent == 6030) { echo 'selected = "selected"';}}?> value="6001" data-id="6030" data-root="6030">Автомобили</option>
                                                    <option <?php if(isset($this->params['myMod'][0]['queryCategory'][0])) { if(EbayCategory::findOne(['category_id' => $this->params['myMod'][0]['queryCategory'][0]])->category_root_parent == 10063) { echo 'selected = "selected"';}}?> value="6024" data-id="10063" data-root="10063">Мотоциклы</option>
                                                    <option <?php if(isset($this->params['myMod'][0]['queryCategory'][0])) { if(EbayCategory::findOne(['category_id' => $this->params['myMod'][0]['queryCategory'][0]])->category_root_parent == 100448) { echo 'selected = "selected"';}}?> value="42595" data-id="100448" data-root="100448">Снегоходы</option>
                                                    <option <?php if(isset($this->params['myMod'][0]['queryCategory'][0])) { if(EbayCategory::findOne(['category_id' => $this->params['myMod'][0]['queryCategory'][0]])->category_root_parent == 43962) { echo 'selected = "selected"';}}?> value="6723" data-id="43962" data-root="43962">Квадроциклы</option>

                                                </select>

                                                <select class="form-control input-sm filter_brands">
                                                    <option value="null">Выберите марку</option>
                                                    <?php
                                                    // автомобили
                                                    $modelArray=\common\models\EbayCategory::find()->where(['category_parent_id'=>6001])->asArray()->all();
                                                    $modelCarArray = array();
                                                    foreach($modelArray as $value) {
//                                                        if( isset($this->params['myMod'])) {
                                                            //if (($value['category_name'] == $this->params['myMod'][0]['queryBrand']) and ($this->params['myMod'][0]['queryCategory'][0] == 6030)) {
                                                            if (($value['category_name'] == $this->params['myMod'][0]['queryBrand'])) {
                                                                $cssClass = '';
                                                                $cssSelected = 'selected = "selected"';
                                                            } else {
                                                                $cssClass = 'hidden';
                                                                $cssSelected = '';
                                                            }

                                                        echo "<option ".$cssSelected." class=\"".$cssClass."\" value=\"".$value['category_id']."\" data-id=\"".$value['category_parent_id']."\">".$value['category_name']."</option>";
                                                        $modelCarArray=array_merge($modelCarArray,\common\models\EbayCategory::find()->where(['category_parent_id'=>$value['category_id']])->asArray()->all());
//                                                    }
                                                    }
                                                    // мотоциклы
                                                    $modelArray=\common\models\EbayCategory::find()->where(['category_parent_id'=>6024])->asArray()->all();
                                                    foreach($modelArray as $value){
//                                                        if( isset($this->params['myMod'])) {
                                                            if ($value['category_name'] == $this->params['myMod'][0]['queryBrand'] and $this->params['myMod'][0]['queryCategory'][0] == 10063) {
                                                                $cssClass = '';
                                                                $cssSelected = 'selected = "selected"';
                                                            } else {
                                                                $cssClass = 'hidden';
                                                                $cssSelected = '';
                                                            }

                                                        echo "<option ".$cssSelected." class=\"".$cssClass."\" value=\"".$value['category_id']."\" data-id=\"".$value['category_parent_id']."\">".$value['category_name']."</option>";
                                                        $modelCarArray=array_merge($modelCarArray,\common\models\EbayCategory::find()->where(['category_parent_id'=>$value['category_id']])->asArray()->all());
//                                                    }
                                                    }
                                                    // снегоходы
                                                    $modelArray=\common\models\EbayCategory::find()->where(['category_parent_id'=>42595])->asArray()->all();
                                                    foreach($modelArray as $value){
//                                                        if(isset($this->params['myMod'])) {
                                                            if ($value['category_name'] == $this->params['myMod'][0]['queryBrand'] and $this->params['myMod'][0]['queryCategory'][0] == 100448) {
                                                                $cssClass = '';
                                                                $cssSelected = 'selected = "selected"';
                                                            } else {
                                                                $cssClass = 'hidden';
                                                                $cssSelected = '';
                                                            }

                                                        echo "<option ".$cssSelected." class=\"".$cssClass."\" value=\"".$value['category_id']."\" data-id=\"".$value['category_parent_id']."\">".$value['category_name']."</option>";
                                                        $modelCarArray=array_merge($modelCarArray,\common\models\EbayCategory::find()->where(['category_parent_id'=>$value['category_id']])->asArray()->all());
//                                                        }
                                                    }
                                                    // квадроциклы
                                                    $modelArray=\common\models\EbayCategory::find()->where(['category_parent_id'=>6723])->asArray()->all();
                                                    foreach($modelArray as $value){
//                                                        if(isset($this->params['myMod'])) {
                                                            if ($value['category_name'] == $this->params['myMod'][0]['queryBrand'] and $this->params['myMod'][0]['queryCategory'][0] == 43962) {
                                                                $cssClass = '';
                                                                $cssSelected = 'selected = "selected"';
                                                            } else {
                                                                $cssClass = 'hidden';
                                                                $cssSelected = '';
                                                            }

                                                        echo "<option ".$cssSelected." class=\"".$cssClass."\" value=\"".$value['category_id']."\" data-id=\"".$value['category_parent_id']."\">".$value['category_name']."</option>";
                                                        $modelCarArray=array_merge($modelCarArray,\common\models\EbayCategory::find()->where(['category_parent_id'=>$value['category_id']])->asArray()->all());
//                                                    }
                                                    }
                                                    ?>
                                                </select>

                                                <select class="form-control input-sm filter_models">
                                                    <option value="null">Выберите модель</option>
                                                    <?php

                                                    foreach ($modelCarArray as $ololosh) {
                                                        if($ololosh['category_name'] == $this->params['myMod'][0]['queryModel']) {
                                                            $cssClass = '';
                                                            $cssSelected = 'selected = "selected"';
                                                        } else {
                                                            $cssClass = 'hidden';
                                                            $cssSelected = '';
                                                        }
                                                        echo "<option ".$cssSelected." class=\"".$cssClass."\" value=\"".$ololosh['category_id']."\" data-id=\"".$ololosh['category_parent_id']."\">".$ololosh['category_name']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <select class="form-control input-sm filter_sort">
                                                    <option <?php  if(isset($this->params['myMod'][0]['querySort'])) {if($this->params['myMod'][0]['querySort']==0){echo"selected=\"selected\"";}} ?> value="0">Сортировать по возрастанию</option>
                                                    <option <?php if(isset($this->params['myMod'][0]['querySort'])) {if($this->params['myMod'][0]['querySort']==1){echo"selected=\"selected\"";}} ?> value="1">Сортировать по убыванию</option>
                                                    <option <?php if(isset($this->params['myMod'][0]['querySort'])) {if($this->params['myMod'][0]['querySort']==2){echo"selected=\"selected\"";}} ?> value="2">Наилучшее совпадение</option>
                                                </select>
                                            </div>
                                            <a class="btn btn-default btn-success filter_change">Применить</a>
                                        </form>

                                    </div>
                                </div>

                                <br />
                                <?= $content ?>

                            </article>
                      </div>
                      <br clear="all"/>
                     <div class="pageContent">
                        <!-- поисковая форма -->
                        <h1>Автозапчасти из США</h1>
                        <p>ООО «Альфа Парт» занимается поставками <strong>запчастей из США</strong>. Мы предлагаем оптовым и розничным клиентам заказать комплектующие для авто марок:</p>
                        <p>В наличии и на заказ оригинальная продукция для вышеперечисленных автомобилей, а также высококачественные дубликаты. Среди них – автозапчасти Dello (всё для автотехники марки Опель), Cardone (Насосы ГУР, приводные валы), колёсные ступицы от National и прочие американские бренды. Неоригинальные товары представлены в разных ценовых категориях.</p>
                        <p><span style='font-weight:bold'>Запчасти из США – это выгодно</span></p>
                        <p>Многие автолюбители заказывают автомобильную продукцию именно в Америке, поскольку здесь её можно приобрести дешевле, чем в России и СНГ. Мы содействуем клиентам в поиске комплектующих по наиболее выгодным ценам. Частным лицам мы предлагаем заказ с аукционов Amazon и Ebay и других магазинов Соединённых Штатов, а также со своего склада и со склада нашего поставщика. Оптовым покупателям мы поставляем на заказ партии товаров в любых объёмах.</p>
                        <h2>Как приобрести автозапчасти в ООО «Альфа Парт»</h2>
                        <ol>
                           <li>Выбирайте товары в онлайн-каталоге (доступен поиск по VIN). </li>
                           <li>Регистрируйтесь, делайте заявку, либо звоните: +7 (499) 994-994-3 и договаривайтесь с менеджером о поставке.</li>
                           <li>Оплачивайте безналичным или наличными способом.</li>
                           <li>Выбирайте способ доставки (авиа- или морские перевозки) и ожидайте поступления посылки на нужный адрес.</li>
                        </ol>
                        <p>Доставка морем длится в среднем 5 недель (с момента отплытия контейнера из порта), по воздуху 7-14 дней, если детали есть в наличии. Обработка заказа происходит моментально за счёт автоматизированного учёта продукции на складе.</p>
                        <p>Это приблизительная схема сотрудничества. Мы готовы рассмотреть ваши предпочтения в индивидуальном порядке. Звоните!</p>
                     </div>
                  </div>
               </div>
            </div>
            <div id="sideLeft">
                <ul class="menu">
                    <?php
                        if (Yii::$app->controller->action->id=="itemslist" or Yii::$app->controller->action->id=="single" or Yii::$app->controller->action->id=="get-items-by" or Yii::$app->controller->action->id=="get-item-by-query"){
                                $this->beginContent('@frontend/views/site/catalog.php');
                                echo $content;
                                $this->endContent();
                            }
                    ?>
                </ul>
                
               <div class="clear"></div>
               <!--<div class="left_menu_title">Категории</div>-->
               <div class="left_menu">
                  <ul>
                    
                      
                      
                     
                     <li  class="lev1">
                        <a  class="alev1" href="/prais.list"><span>Прайс лист</span></a>
                     </li>
                     <li  class="lev1">
                        <a  class="alev1" href="/d_catalog3/1/"><span>Б/У запчасти</span></a>
                     </li>
                     <li  class="lev1">
                        <a  class="alev1" href="/kak_sdelat_zakaz.html"><span>Как сделать заказ</span></a>
                     </li>
                     <li  class="lev1">
                        <a  class="alev1" href="/oplata.html"><span>Оплата</span></a>
                     </li>
                     <li  class="lev1">
                        <a  class="alev1" href="/about/novosti/"><span>Новости</span></a>
                     </li>
                     <li  class="lev1">
                        <a  class="alev1" href="/stati.html"><span>Статьи</span></a>
                     </li>
                     <li  class="lev1">
                        <a  class="alev1" href="/vopros-otvet.html"><span>Вопрос-ответ</span></a>
                     </li>
                     <li  class="lev1">
                        <a  class="alev1" href="/otzyvy.html"><span>Отзывы</span></a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
      <div id="footer">
      <div id="footer_inner">
         <div id="footer_top" class="flc">
            <div class="services_block">
               <div class="service_title">
                  <p>Преимущества компании:</p>
                  <ul id = "ulfoot">
                     <li>лидирующие позиции на рынке запчастей для автомобилей производства США;</li>
                     <li>огромный ассортимент товаров и более 15 000 позиций на складе;</li>
                     <li>развитая система логистики с автоматизированным складским учетом;</li>
                     <li>самые короткие сроки доставки по России, Казахстану и Белоруссии;</li>
                  </ul>
               </div>
               </ul> 
            </div>
            <div class="rightside display_none">
               <div class="payments flc">
                  <div class="leftside">
                     Способы оплаты:
                  </div>
                  <div class="rightside">
                     <div class="pimages flc">
                        <div class="leftside">
                           <img src="/_sysimg/payment-sys/beznal.png" alt="Безналичный расчёт" title="Безналичный расчёт" />
                           <img src="/_sysimg/payment-sys/nal.png" alt="Наличный расчёт" title="Наличный расчёт" />
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div id="footer_bottom">
            <div class="copyright">
               <div class="pageContent">© 2015 «Альфа Парт»  - Автозапчасти из США
               </div>
            </div>
         </div>
      </div>
          <?php $this->endBody() ?>
   </body>
</html>
<?php $this->endPage() ?>