<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'site/index',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'items' => 'site/itemslist',
                [
                    'route' => 'site/get-items-by',
                    'pattern' => 'category/<category:\d+>/<brand:\w+|[\w\W]+?>/<ser:\w+|[\w\W]+?>',
                    'defaults' => [
                        'category' => '6030',
                        'brand' => null,
                        'ser' => null,
                    ],
                ],
                [
                    'route' => 'site/get-items-by',
                    'pattern' => 'category/<category:\d+>/<brand:\w+|[\w\W]+?>',
                    'defaults' => [
                        'category' => '6030',
                        'brand' => null,
                        'ser' => null,
                    ],
                ],
                [
                    'route' => 'site/get-items-by',
                    'pattern' => 'category/<category:\d+>',
                    'defaults' => [
                        'category' => '6030',
                        'brand' => null,
                        'ser' => null,
                    ],
                ],
                'item/<ebayitemid:\d+>/<category:\d+>' => 'site/single',
                'item/<ebayitemid:\d+>' => 'site/single',
                'order' => 'site/order',
                'root' => 'site/root',
                'getCats/<id:\d+>' => 'site/cats',
                '<category:\d+>' => 'site/filter',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
