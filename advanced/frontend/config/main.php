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
    'defaultRoute' => 'site/itemslist',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'items' => 'site/itemslist',
                [
                    'route' => 'site/get-items-by',
                    'pattern' => 'items/category=<category:\d+>&page=<page:\d+>&sort=<sort:\d+>&text=<queryText:[&-_a-zA-Zа-яА-Я0-9\s.]+>',
                    'defaults' => [
                        'category' => '6030',
                        'page' => 1,
                        'sort' => 0,
                    ],
                ],
                [
                    'route' => 'site/get-items-by',
                    'pattern' => 'items/category=<category:\d+>&page=<page:\d+>&sort=<sort:\d+>',
                    'defaults' => [
                        'category' => '6030',
                        'page' => 1,
                        'sort' => 0,
                    ],
                ],
                [
                    'route' => 'site/get-items-by',
                    'pattern' => 'items/category=<category:\d+>&page=<page:\d+>&sort=<sort:\d+>&brand=<brand:[-_a-zA-Z0-9\s]+>',
                    'defaults' => [
                        'category' => '6030',
                        'page' => 1,
                        'sort' => 0,
                        'brand' => null,
                    ],
                ],
                [
                    'route' => 'site/get-items-by',
                    'pattern' => 'items/category=<category:\d+>&page=<page:\d+>&sort=<sort:\d+>&brand=<brand:[-_a-zA-Z0-9\s]+>&model=<ser:[-_a-zA-Z0-9\s]+>',
                    'defaults' => [
                        'category' => '6030',
                        'page' => 1,
                        'sort' => 0,
                        'brand' => null,
                        'ser' => null,
                    ],
                ],
                [
                    'route' => 'site/get-items-by',
                    'pattern' => 'items/category=<category:\d+>&page=<page:\d+>&sort=<sort:\d+>&brand=<brand:[-_a-zA-Z0-9\s]+>&model=<ser:[-_a-zA-Z0-9\s]+>&text=<queryText:[&-_a-zA-Zа-яА-Я0-9\s.]+>',
                    'defaults' => [
                        'category' => '6030',
                        'page' => 1,
                        'sort' => 0,
                        'brand' => null,
                        'ser' => null,
                        'queryText' => '',
                    ],
                ],
                [
                    'route' => 'site/get-item-by-query',
                    'pattern' => 'items/<page:\d+>/<sort:\d+>/<queryText:[&-_a-zA-Zа-яА-Я0-9\s.]+>',
                    'defaults' => [
                        'queryText' => '',
                        'page' => 1,
                        'sort' => 0,
                    ],
                ],
                'item/<ebayitemid:\d+>' => 'site/single',
                'order' => 'site/order',
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
