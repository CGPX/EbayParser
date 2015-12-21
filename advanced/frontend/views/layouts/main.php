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
$SearchForm=""; // форма поиска на всех страницах. по сути она либо скрыта, либо показана.

/**
 * <?= $BlockCatalog ?> - вызываем отображение каталога
 * <?= $BlockCart ?> - вызываем отображение корзины
 * В необходимых для отображения шаблонах нужно вызывать рендер в эти переменные
 *
 * <?= $BlockCataog = \Yii::$app->view->renderFile('@app/views/site/catalog.php'); ?>
 */

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
                <main>
                    <div class="container-fluid">

                        <div class="row">

                            <!-- Левый блок BEGIN -->
                            <aside class="col-sm-12 col-md-4 col-lg-2">
                                <div class="row">
                                    <!--Содержимое-->

                                    <?php
                                        $this->beginContent('@frontend/views/site/catalog.php');
                                            echo $content;
                                        $this->endContent();
                                    ?>


                                </div>
                            </aside>
                            <!-- Левый блок END -->

                            <!-- Контент BEGIN -->
                            <article class="col-sm-12 col-md-8 col-lg-8">
                                <!--Содержимое-->


                                <div class="row">
                                    <!-- хлебные крошки -->
                                    <?= Breadcrumbs::widget([
                                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                    ]) ?>
                                    <!-- виджет alert сообщений -->
                                    <?= Alert::widget() ?>
                                </div>

                                <!-- поисковая форма -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php $form = ActiveForm::begin(['id' => 'ebay-form', 'action' => '?r=site/itemslist']); ?>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Ищем товары..." id="ebayform-querytext" name="EbayForm[queryText]" value="<?= $this->params['myMod'][0]['queryText']; ?>">
                                            <span class="input-group-btn">
                                                <?= Html::submitButton('Найти <span class="glyphicon glyphicon-search" aria-hidden="true"></span>', ['class' => 'btn btn-success', 'name' => 'ebay-button']) ?>
                                            </span>
                                        </div>

                                        <div class="hidden-input-group hidden">
                                            <input type="text" class="form-control" placeholder="Переходим в просмотр лота..." id="ebayform-singleitemid" name="EbayForm[singleItemId]" value="">
                                            <input type="text" class="form-control" placeholder="Определяем категорию..." id="ebayform-querycategory" name="EbayForm[queryCategory]" value="<?= $this->params['myMod'][0]['queryCategory']; ?>">
                                            <input type="text" class="form-control" placeholder="Определяем страницу..." id="ebayform-querypage" name="EbayForm[queryPage]" value="<?= $this->params['myMod'][0]['queryPage']; ?>">
                                            <input type="text" class="form-control" placeholder="Model">
                                            <input type="text" class="form-control" placeholder="Year">
                                        </div>
                                        <?php ActiveForm::end(); ?>
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
                                    $this->beginContent('@frontend/views/site/cart.php');
                                        echo $content;
                                    $this->endContent();
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

            <?php $this->endBody() ?>
        </body>
    </html>
<?php $this->endPage() ?>