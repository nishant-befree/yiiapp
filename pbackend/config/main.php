<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'api-pbackend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'pbackend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'user' => [
            'class' => 'app\modules\user\Module'
        ],
        'bucket' => [
            'class' => 'app\modules\bucket\Module',
        ],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'request' => [
            'csrfParam' => '_csrf-pbackend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            #'identityClass' => 'common\models\User',
            'identityClass' => 'app\modules\user\models\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-pbackend', 'httpOnly' => true],
            'enableSession' => false,
            #'loginUrl' => null,
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-pbackend',
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
        
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ], 
        
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule',               
                    'controller'    => 'user/login',
                    'pluralize'     => false,
                    'tokens' => [
                        '{id}'  => '<id:\d+>',
                    ],
                ],
                 ['class' => 'yii\rest\UrlRule',               
                    'controller'    => 'bucket/indexme',
                    'pluralize'     => false,
                    'tokens' => [
                        '{id}'  => '<id:\d+>',
                    ],
                ],
                ['class' => 'yii\web\UrlRule', 'pattern' => 'site/<action>', 'route' => 'site/<action>'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'login'],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => 'user/login',
                    'pluralize'     => false,
                    'tokens' => [
                            '{id}'             => '<id:\d+>',
                    ],
                    'extraPatterns' => [
                            'GET me'            =>  'direct',
                            'OPTIONS {id}'      =>  'options',
                            'POST login'        =>  'index',
                            //'POST login'        =>  'login',
                            'OPTIONS login'     =>  'options',
                            'POST signup'       =>  'signup',
                            'OPTIONS signup'    =>  'options',
                            'POST confirm'      =>  'confirm',
                            'OPTIONS confirm'   =>  'options',
                            'POST password-reset-request'       =>  'password-reset-request',
                            'OPTIONS password-reset-request'    =>  'options',
                            'POST password-reset-token-verification'       =>  'password-reset-token-verification',
                            'OPTIONS password-reset-token-verification'    =>  'options',
                            'POST password-reset'       =>  'password-reset',
                            'OPTIONS password-reset'    =>  'options',
                            'GET me'            =>  'me',

                            'POST me'           =>  'me-update',
                            'OPTIONS me'        =>  'options',
                    ]
                ],
            ],
        ],
        
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ]
    ],
    'params' => $params,
    #'defaultRoute' => 'site/index',

];
