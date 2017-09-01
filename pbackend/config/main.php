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
        'practice'=>[
            'class' => 'pbackend\modules\practice\Module',
        ],
        'setting'=>[
            'class' => 'app\modules\setting\Module',
        ]
    ],
    'components' => [
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 1,
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 0,
            ],
        ],
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
//                ['class' => 'yii\rest\UrlRule',               
//                    'controller'    => 'user/login',
//                    'pluralize'     => false,
//                    'tokens' => [
//                        '{id}'  => '<id:\d+>',
//                    ],
//                ],
//                 ['class' => 'yii\rest\UrlRule',               
//                    'controller'    => 'bucket/indexme',
//                    'pluralize'     => false,
//                    'tokens' => [
//                        '{id}'  => '<id:\d+>',
//                    ],
//                ],
                ['class' => 'yii\web\UrlRule', 'pattern' => 'site/<action>', 'route' => 'site/<action>'],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => 'user/user',
                    'pluralize'     => false,
                    'tokens' => [
                        '{id}'             => '<id:\d+>',
                    ],
                    'extraPatterns' => [
                        'OPTIONS {id}'              =>  'options',
                        'POST login'                =>  'login',
                        'OPTIONS login'             =>  'options',
                        'GET get-permissions'       =>  'get-permissions',
                        'OPTIONS get-permissions'   =>  'options',
                    ]
                ],
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
                            'POST userlogin'        =>  'index',
                            'POST userlogin'        =>  'userlogin',
                            'OPTIONS userlogin'     =>  'options',
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
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {

                $response = $event->sender;
                if($response->format == 'html') {
                    return $response;
                }

                $responseData = $response->data;

                if(is_string($responseData) && json_decode($responseData)) {
                    $responseData = json_decode($responseData, true);
                }


                if($response->statusCode >= 200 && $response->statusCode <= 299) {
                    $response->data = [
                        'success'   => true,
                        'status'    => $response->statusCode,
                        'data'      => $responseData,
                    ];
                } else {
                    $response->data = [
                        'success'   => false,
                        'status'    => $response->statusCode,
                        'data'      => $responseData,
                    ];

                }

                // Handle and display errors in the API for easy debugging
                $exception = \Yii::$app->errorHandler->exception;
                if ($exception && get_class($exception) !==
                    "yii\web\HttpException" &&
                    !is_subclass_of($exception,
                        'yii\web\HttpException') && YII_DEBUG)
                {
                    $response->data['success'] = false;
                    $response->data['exception'] = [
                        'message' => $exception->getMessage(),
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                        'trace' => $exception->getTraceAsString()
                    ];
                }

                return $response;
            },
        ],
        'sse' => [
	        'class' => \odannyc\Yii2SSE\LibSSE::class
        ]
    ],
    'params' => $params,
    #'defaultRoute' => 'site/index',

];
