<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'layout' => '',
    'defaultRoute' => 'home/index',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'HpdwGlX_bA1sh2zn7ER1T2XYzjiT1tzO',
            'baseUrl'=> '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => 'home/login',
        ],
        'errorHandler' => [
            'errorAction' => 'home/error',
        ],

        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'Europe/Moscow',
            'dateFormat' => 'php:d F Y',
            'datetimeFormat' => 'php:d.m.Y H:i',
            'timeFormat' => 'php:H:i',
            'nullDisplay' => ''
        ],

        // Settings send email

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'messageConfig' => [
                'from' => ['test@reg13.ru' => 'test'],
            ],
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.reg13.ru', // insert name or name post server
                'username' => 'test',
                'password' => 'Pa$w0rd',
                'port' => '25',
                'encryption' => '',
            ],

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

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller>/<action>/<stateRequest:\w+>/<page:\d+>/<per-page:\d+>' => '<controller>/<action>',
                '<controller>/index/<stateRequest:\w+>/<page:\d+>/<per-page:\d+>' => '<controller>/index',
                '<controller>/<action>/<stateRequest:\w+>/<page:\d+>/copy' => '<controller>/copy',
                '<controller>/<action>/copy' => '<controller>/copy',
                '<controller>/<action>/index' => '<controller>/index',
                '<controller>/index/<stateRequest:\w+>' => '<controller>/index',
                '<controller>/update/<id:\d+>/<stateRequest:\w+>/<page:\d+>' => '<controller>/update',
                '<controller>/update/<id:\d+>/<stateRequest:\w+>' => '<controller>/update',
                '<controller>/update/<id:\d+>/<page:\d+>' => '<controller>/update',
                '<controller>/update/<id:\d+>' => '<controller>/update',
                '<controller>/<action>' => '<controller>/<action>',
                'admin-panel' =>'admin/admin-panel/index',
                '<module>/<controller>/update/index'=>'<module>/<controller>/index',
                '<module>/<controller>/<action>/<id:\d+>' => '<module>/<controller>/<action>',
                'customers/create/<actions:\w+>' => 'customers/create',
            ],
        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => [
                'user',
                'operator',
                'admin'
            ],

        ],
    ],

    'params' => $params,

    // Modules

    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'

        ],
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ]

    ],


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
}

return $config;
