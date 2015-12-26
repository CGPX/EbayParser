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
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'route' => 'site/get-items-by',
                    'pattern' => 'items/category/<category:\d+>/<brand:\d+>/<page:\d+>/<sort:\d+>/<queryText:.+>',
                    'defaults' => [
                        'category' => '6030',
                        'brand' => '',
                        'page' => 1,
                        'sort' => 0,
                        'queryText' =>'',
                    ],
                ],
                [
                    'route' => 'site/get-item-by-query',
                    'pattern' => 'items/<page:\d+>/<sort:\d+>/<queryText:.+>',
                    'defaults' => [
                        'queryText' => '',
                        'page' => 1,
                        'sort' => 0,
                    ],
                ],
                'item/<ebayitemid:\d+>' => 'site/single',
                'items' => 'site/itemslist',
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
