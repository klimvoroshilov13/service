<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'layout' => '',
    'defaultRoute' => 'requests/index',
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
            'loginUrl' => 'requests/login',
        ],
        'errorHandler' => [
            'errorAction' => 'requests/error',
        ],

        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            //'datetimeFormat' => 'short',
            'defaultTimeZone' => 'Europe/Moscow',
            //'timeZone' => 'GMT+3',
            //'date1Format' => 'd.m.Y',
            'dateFormat' => 'dd MMMM Y',
            'datetimeFormat' => 'dd.MM.Y HH:mm',
            'timeFormat' => 'HH:mm',
            'nullDisplay' => ''
        ],

        //Настройка отправки почты

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'messageConfig' => [
                'from' => ['test@reg13.ru' => 'test'],
            ],
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.reg13.ru', //вставляем имя или адрес почтового сервера
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
                '<controller>/index/<stateRequest:\w+>/<page:\d+>/<per-page:\d+>' => '<controller>/index',
                '<controller>/index/<stateRequest:\w+>' => '<controller>/index',
                '<controller>/update/index' => '<controller>/index',
                '<controller>/update/<id:\d+>/<stateRequest:\w+>/<page:\d+>' => '<controller>/update',
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

    //Модули

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
