<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'e_958VS8c2SUAfrWnO9usAZsa1b6_3q7',
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
        
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<alias:\w+>' => 'site/<alias>',
            ],
        ],
        'formatter' => [
            'dateFormat' => 'd-M-Y',
            'datetimeFormat' => 'd-M-Y H:i:s',
            'timeFormat' => 'H:i:s',

            //'locale' => 'de-DE', //your language locale
            //'defaultTimeZone' => 'Europe/Berlin', // time zone
        ],
        
    ],
    'params' => $params,
];

$config['modules']['gridview'] = [
    'class' => '\kartik\grid\Module'
];

use kartik\datecontrol\Module;
$config['modules']['datecontrol'] = [
    'class' => 'kartik\datecontrol\Module',

    // format settings for displaying each date attribute (ICU format example)
    'displaySettings' => [
        Module::FORMAT_DATE => 'dd-MM-yyyy',
        Module::FORMAT_TIME => 'HH:mm:ss a',
        Module::FORMAT_DATETIME => 'dd-MM-yyyy HH:mm:ss a', 
    ],

    // format settings for saving each date attribute (PHP format example)
    'saveSettings' => [
        Module::FORMAT_DATE => 'Y-m-d',
        Module::FORMAT_TIME => 'php:H:i:s',
        Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
    ],

    // set your display timezone
    'displayTimezone' => 'Africa/Tunis',

    // set your timezone for date saved to db
    'saveTimezone' => 'UTC',

    // automatically use kartik\widgets for each of the above formats
    'autoWidget' => true,

    // use ajax conversion for processing dates from display format to save format.
    'ajaxConversion' => true,

    // default settings for each widget from kartik\widgets used when autoWidget is true
    'autoWidgetSettings' => [
        Module::FORMAT_DATE => ['type'=>2, 'pluginOptions'=>['autoclose'=>true]], // example
        Module::FORMAT_DATETIME => [], // setup if needed
        Module::FORMAT_TIME => [], // setup if needed
    ],

    // custom widget settings that will be used to render the date input instead of kartik\widgets,
    // this will be used when autoWidget is set to false at module or widget level.
    'widgetSettings' => [
        Module::FORMAT_DATE => [
            'class' => 'yii\jui\DatePicker', // example
            'options' => [
                'dateFormat' => 'php:d-M-Y',
                'options' => ['class'=>'form-control'],
            ]
        ]
    ]
    // other settings
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
    $config['components']['assetManager']['forceCopy'] = true;
}

return $config;
