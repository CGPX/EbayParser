<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap\ActiveForm;

AppAsset::register($this);
$SearchForm="hidden"; // форма поиска на всех страницах. по сути она либо скрыта, либо показана.
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
                                            <input type="text" class="form-control filter_query_input" placeholder="Ищем запчасти..." id="ebayform-querytext" name="EbayForm[queryText]" value="<?= $this->params['myMod'][0]['queryTextShow']; ?>">
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
                                                    <option selected="selected" value="null">Выберите тип ТС</option>
                                                    <option value="6001">Автомобили</option>
                                                    <option value="6024">Мотоциклы</option>
                                                    <option value="42595">Снегоходы</option>
                                                    <option value="6723">Квадроциклы</option>
                                                </select>

                                                <select class="form-control input-sm filter_brands">
                                                    <option selected="selected" value="null">Выберите марку</option>
                                                    <?php
                                                    // автомобили
                                                    $modelArray=\common\models\EbayCategory::find()->where(['category_parent_id'=>6001])->asArray()->all();
                                                    $modelCarArray = array();
                                                    foreach($modelArray as $value){
                                                        echo "<option class=\"hidden\" value=\"".$value['category_id']."\" data-id=\"".$value['category_parent_id']."\">".$value['category_name']."</option>";
                                                        $modelCarArray=array_merge($modelCarArray,\common\models\EbayCategory::find()->where(['category_parent_id'=>$value['category_id']])->asArray()->all());
                                                    }
                                                    // мотоциклы
                                                    $modelArray=\common\models\EbayCategory::find()->where(['category_parent_id'=>6024])->asArray()->all();
                                                    foreach($modelArray as $value){
                                                        echo "<option class=\"hidden\" value=\"".$value['category_id']."\" data-id=\"".$value['category_parent_id']."\">".$value['category_name']."</option>";
                                                        $modelCarArray=array_merge($modelCarArray,\common\models\EbayCategory::find()->where(['category_parent_id'=>$value['category_id']])->asArray()->all());
                                                    }
                                                    // снегоходы
                                                    $modelArray=\common\models\EbayCategory::find()->where(['category_parent_id'=>42595])->asArray()->all();
                                                    foreach($modelArray as $value){
                                                        echo "<option class=\"hidden\" value=\"".$value['category_id']."\" data-id=\"".$value['category_parent_id']."\">".$value['category_name']."</option>";
                                                        $modelCarArray=array_merge($modelCarArray,\common\models\EbayCategory::find()->where(['category_parent_id'=>$value['category_id']])->asArray()->all());
                                                    }
                                                    // квадроциклы
                                                    $modelArray=\common\models\EbayCategory::find()->where(['category_parent_id'=>6723])->asArray()->all();
                                                    foreach($modelArray as $value){
                                                        echo "<option class=\"hidden\" value=\"".$value['category_id']."\" data-id=\"".$value['category_parent_id']."\">".$value['category_name']."</option>";
                                                        $modelCarArray=array_merge($modelCarArray,\common\models\EbayCategory::find()->where(['category_parent_id'=>$value['category_id']])->asArray()->all());
                                                    }
                                                    ?>
                                                </select>

                                                <select class="form-control input-sm filter_models">
                                                    <option selected="selected" value="null">Выберите модель</option>
                                                    <?php
                                                    foreach ($modelCarArray as $ololosh){
                                                        echo "<option class=\"hidden\" value=\"".$ololosh['category_id']."\" data-id=\"".$ololosh['category_parent_id']."\">".$ololosh['category_name']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <select class="form-control input-sm filter_sort">
                                                    <option selected="selected" value="null">Сортировать по</option>
                                                    <option value="0">Возрастанию</option>
                                                    <option value="1">Убыванию</option>
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