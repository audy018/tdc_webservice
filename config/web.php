<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'syz_yqSbGcVw1-9qpFM7g7NUzJvCyIqy1121',
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
	'urlManager' => [
        	'class' => 'yii\web\UrlManager',
	        // Disable index.php
	        'showScriptName' => false,
	        // Disable r= routes
	        'enablePrettyUrl' => true,
	        'rules' => array(
        	        '<controller:\w+>/<id:\d+>' => '<controller>/view',
                	'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
	                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                        ['class' => 'yii\rest\UrlRule', 'controller' => 'user'],
                        ['class' => 'yii\rest\UrlRule', 'controller' => 'buffe-config', 'pluralize'=>false],
                        ['class' => 'yii\rest\UrlRule', 'controller' => 'buffe-constants'],
        	),
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
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
        'db' => require(__DIR__ . '/db.php'),
        'dbtcc' => require(__DIR__ . '/dbtcc.php'),
        'dbtdc' => require(__DIR__ . '/dbtdc.php'),
        'dbtdcdata' => require(__DIR__ . '/dbtdcdata.php'),
        'dbcascap' => require(__DIR__ . '/db_cascap.php'),
    ],
    'params' => $params,
    'timeZone' => 'Asia/Bangkok',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
