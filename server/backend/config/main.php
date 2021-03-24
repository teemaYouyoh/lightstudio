<?php
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
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'timeZone' => 'Europe/Kiev',
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['78.27.168.227'],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'formatter' => [
            'dateFormat' => 'yyyy-MM-dd',
        ],
        'amocrm' => [
            'class' => 'yii\amocrm\Client',
            'subdomain' => 'lightstudiospace', // Персональный поддомен на сайте amoCRM
            'login' => 'darina10mandarina@gmail.com', // Логин на сайте amoCRM
            'hash' => '50ef3b5e0716dfb7612be12cb20b39bdfae74fe2', // Хеш на сайте amoCRM
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'lightstudio.office@gmail.com',
                'password' => 'lightstudio21',
                'port' => '587',
                'encryption' => 'tls',
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
                [
                    'class' => 'yii\log\FileTarget', 
                    'categories' => ['booking_error'],
                    'logFile' => '@runtime/logs/booking.log',
                    'logVars' => []
                ],
                // [
                //     'class' => 'yii\log\EmailTarget',
                //     'categories' => ['booking_error'],
                //     'mailer' => 'yii\swiftmailer\Mailer',
                //     'logVars' => [],
                //     'message' => [
                //         'from' => ['lightstudio.office@gmail.com' => 'Light Studio'],
                //         'to' => ['rostyslav.butsyk@gmail.com'],
                //         'subject' => 'Ошибка добавлениея промежутка',
                //     ],
                // ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],

    ],
    'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\PathController',
            'access' => ['@'],
            'root' => [
                'baseUrl'=>'@web',
                'basePath'=>'@webroot',
                'path' => '/uploads',
                'name' => 'Uploads'
            ],

        ]
    ],
    'params' => $params,

];
