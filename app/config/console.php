<?php
return [
    'id' => 'yii2_admin_template_console',
    'name' => 'Yii2 Admin Template Console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require('db.php'),
    ],
    'params' => require('params.php'),
    'vendorPath' => dirname(__DIR__) . "/../vendor",
];
