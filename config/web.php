<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'lyii',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'name'=>'TRS Flota',
    'language' => 'ro-RO',
    'sourceLanguage' => 'en-US',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'eGgiwicEt8edz_3lupwA1x_SZWeC1DH_',
			'baseUrl' =>'/trsflota',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'session'=>[
            //    'class'=>'yii\web\DbSession',
               'class' => 'yii\redis\Session',
               'timeout' => 1800, // 30 minutes
                      ],
        'cache' => [
            //    'class' => 'yii\caching\FileCache',
               'class' => 'yii\redis\Cache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
               // Automatically logout after 1800 seconds (30 minutes) of inactivity
            'authTimeout' => 1800,
            // (optional) Automatically logout after 1 hour regardless of activity
            //'absoluteAuthTimeout' => 3600,

        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'assetManager'=>[
            'appendTimestamp'=> false,
            'linkAssets'=>true,
        ],             
    ],
    'modules'=>[
            'gridview' => [
                'class' => '\kartik\grid\Module',
                // other module settings can be configured here
            ],
        ],
    'params' => $params,
    'timeZone'=>'Europe/Bucharest',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
    $config['modules']['gridview']=['class'=>'\kartik\grid\Module'];
}

return $config;
