<?php

use yii\rest\UrlRule;
use yii\web\JsonParser;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'name' => 'Stock Meli',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => JsonParser::class
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => UrlRule::class, 'controller' => [
                    'ubicationtype',
                    'ubication',
                    'stock',
                    'user'
                ]],
                [
                    'pattern' => 'stocks/report/<deposit:([A-Z]){2}\d{2}>/<ubication:([A-Z]){2}-\d{2}-\d{2}-([A-Z]){2}>',
                    'route' => 'stock/report'
                ],
                [
                    'pattern' => 'stocks/report/<deposit:([A-Z]){2}\d{2}>/<product:([A-Z]){3}\d{9,}>',
                    'route' => 'stock/search'
                ]
            ],
        ],
    ],
    'params' => $params,
];
