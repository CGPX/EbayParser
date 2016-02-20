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
    $this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/items']];
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
            $this->params['breadcrumbs'][] = ['label' => $item['label'], 'url' => '/items/category/'.$item['category_id']];
        }
    }
}else{$FilterForm="hidden";}
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
        <head>
            <meta charset="<?= Yii::$app->charset ?>">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?= Html::csrfMetaTags() /* вот это что за борода? */ ?>

            <title><?= Html::encode($this->title) ?></title>

            <?php $this->head() ?>
        </head>

        <body>
            <?php $this->beginBody() ?>
            <div class="wrapper">
                <!-- BEGIN навигация-->
                <header>
                    <!--Содержимое-->
                    <div class="wrap">
                        <?php
                            NavBar::begin([
                                'brandLabel' => '<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> BayEbay',
                                'brandUrl' => Yii::$app->homeUrl,
                                'options' => [
                                    'class' => 'navbar navbar-inverse navbar-fixed-top',
                                ],
                            ]);
                            $menuItems = [
                                ['label' => 'Информация', 'url' => ['/site/index']],
                                ['label' => 'Каталог', 'url' => ['/site/itemslist']],
                                #['label' => 'Оплата', 'url' => ['/site/404']],
                                #['label' => 'Доставка', 'url' => ['/site/404']],
                                ['label' => 'Оформление', 'url' => ['/site/order']],
                            ];
                            /* это борода для отображение авторизации, нам пока не надо
                            if (Yii::$app->user->isGuest) {
                                $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
                            } else {
                                $menuItems[] = [
                                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                                    'url' => ['/site/logout'],
                                    'linkOptions' => ['data-method' => 'post']
                                ];
                            }
                            */
                            echo Nav::widget([
                                'options' => ['class' => 'nav navbar-nav'],
                                'items' => $menuItems,
                            ]);
                            NavBar::end();
                        ?>
                    </div>
                </header>
                <!-- END навигация-->

                <br />
                <br />
                <br />

                <!-- BEGIN -->
                <main class="content">
                    <div class="container-fluid">

                        <div class="row">

                            <!-- Левый блок BEGIN -->
                            <aside class="col-sm-12 col-md-4 col-lg-2">
                                <div class="row">
                                    <!--Содержимое-->

                                    <?php
                                    if (Yii::$app->controller->action->id=="itemslist" or Yii::$app->controller->action->id=="single" or Yii::$app->controller->action->id=="get-items-by" or Yii::$app->controller->action->id=="get-item-by-query"){
                                        //if (Yii::$app->controller->action->id=="index" or Yii::$app->controller->action->id=="order"){}else{
                                            $SearchForm ="";

                                            $this->beginContent('@frontend/views/site/catalog.php');
                                            echo $content;
                                            $this->endContent();
                                        }
                                    ?>

                                </div>
                            </aside>
                            <!-- Левый блок END -->

                            <!-- Контент BEGIN -->
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
                                <div class="row <?= $SearchForm ?>">
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
                                                </select>
                                            </div>
                                            <a class="btn btn-default btn-success filter_change">Применить</a>
                                        </form>

                                    </div>
                                </div>

                                <br />
                                <?= $content ?>

                            </article>
                            <!-- Контент END -->

                            <!-- Правый блок BEGIN -->
                            <aside class="col-sm-12 col-md-4 col-lg-2">

                                <div class="row">
                                    <!-- содержимое -->

                                    <?php
                                    if (Yii::$app->controller->action->id=="itemslist" or Yii::$app->controller->action->id=="single" or Yii::$app->controller->action->id=="get-items-by" or Yii::$app->controller->action->id=="get-item-by-query"){
                                        //if (Yii::$app->controller->action->id=="index" or Yii::$app->controller->action->id=="order"){}else{
                                            $this->beginContent('@frontend/views/site/cart.php');
                                                echo $content;
                                            $this->endContent();
                                        }
                                    ?>

                                </div>

                            </aside>
                            <!-- Правый блок END -->

                        </div>

                    </div>
                </main>
                <!-- END -->

                <!-- BEGIN -->
                <footer>
                    <div class="container-fluid">
                        <!--Содержимое-->
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8 text-left">

                                <div class="row">
                                    <address>
                                        <strong>Twitter, Inc.</strong><br>
                                        1355 Market Street, Suite 900<br>
                                        San Francisco, CA 94103<br>
                                        <abbr title="Phone">P:</abbr> (123) 456-7890
                                    </address>

                                    <address>
                                        <strong>Full Name</strong><br>
                                        <a href="mailto:#">first.last@example.com</a>
                                    </address>
                                </div>

                            </div>
                            <div class="col-lg-2"></div>
                        </div>

                    </div>
                </footer>
                <!-- END -->
            </div>
            <?php $this->endBody() ?>
        </body>
    </html>
<?php $this->endPage() ?>