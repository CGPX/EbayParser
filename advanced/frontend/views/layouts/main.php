<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <style>
            body {
                padding-top: 50px;
            }

            .navbar {
                margin-bottom: 0;
                border-radius: 0;
            }

            .row.content {height: 450px}

            footer {
                background-color: #555;
                color: white;
                padding: 15px;
            }
        </style>

    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => '<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> BayEbay',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top navbar',
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
            'options' => ['class' => 'navbar-nav navbar-nav'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>

        <div class="container-fluid">

            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>

            <div class="container-fluid">
                <div class="row content">

                    <?= $content ?>

                </div>
            </div>


        </div>



    </div>

    <footer class="container-fluid text-center">

        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8 text-left">

                <address>
                    <strong>&copy; Ololosh, Inc. <?= date('Y') ?></strong><br>
                    123321 г.Кукуево<br>
                    шоссе Программистов, строение 512<br>
                    <abbr title="Phone">т:</abbr> (495) 222 22 22
                </address>

                <address>
                    <strong>Я тебе покушать принёс</strong><br>
                    <a href="mailto:#">pahom@elephant.com</a>
                </address>

            </div>
            <div class="col-sm-2"></div>
        </div>

    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>