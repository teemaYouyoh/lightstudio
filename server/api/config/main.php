<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Europe/Kiev',
    'controllerNamespace' => 'api\controllers',
    'modules' => [
        'v1' => [
            // 'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module'
        ],
    ],
    'components' => [
        'request' => [
            'enableCsrfValidation'=> false,
            'csrfParam' => '_csrf-api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.YYYY',
        ],

        'amocrm' => [
            'class' => 'yii\amocrm\Client',
            'subdomain' => 'lightstudiospace', // Персональный поддомен на сайте amoCRM
            'login' => 'lightstudio.office@gmail.com', // Логин на сайте amoCRM
            'hash' => 'afa0de1b5954d38503ecf9ec51d98a38b08ad792', // Хеш на сайте amoCRM
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

        'google' => [
            'class' => 'idk\yii2\google\apiclient\components\GoogleApiClient',
            'credentialsPath' => '@api/components/credentials.json',
            'clientSecretPath' => '@api/components/credentials.json',
        ],


        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],

        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession'=> false,
            // 'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],

        'session' => [
            'name' => 'advanced-api',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info'],
                    'categories' => ['log_action'],
                    'logFile' => './console.txt',
                    'logVars' => []
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'GET v1/products/<id:\d+>' => 'v1/api-product/product',
                'GET v1/categories' => 'v1/api-category/index',
                'GET v1/pages' => 'v1/api-page/index',
                'GET v1/products/photozones' => 'v1/api-product/photozones',
                'GET v1/products/equipments' => 'v1/api-product/equipments',
                'GET v1/products/services' => 'v1/api-product/services',
                'GET v1/products/dresses' => 'v1/api-product/dresses',
                'GET v1/settings' => 'v1/api-setting/index',
                'GET v1/portfolio' => 'v1/api-portfolio/index',
//                'GET v1/payments/methods' => 'v1/api-payment/methods',
//                'GET v1/calendar/product/<productId:\d+>/interval/<date:\d+>' => 'v1/api-interval/intervals-of-date',
                'GET v1/product/<productId:\d+>/interval/<date:\d+>' => 'v1/api-interval/intervals-of-date',
                'POST v1/payments/pay' => 'v1/api-payment/pay',
                'POST v1/lead' => 'v1/api-payment/lead',
                'POST v1/payments/response' => 'v1/api-payment/liqpay-response',
                'GET v1/payments/check-status/<orderId:\d+>' => 'v1/api-payment/check-liqpay-result',
                'GET v1/google' => 'v1/api-google/google',
                'GET v1/crm-auth' => 'v1/api-crm/auth',
                'GET v1/test' => 'v1/api-payment/test',
            ],

        ],

    ],
    'params' => $params,
];
