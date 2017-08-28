<?php

namespace app\modules\user\controllers;

/*use yii\web\Controller;
use yii\filters\auth\HttpBearerAuth;
use Yii;
use yii\filters\auth\CompositeAuth;
use app\modules\user\models\LoginForm;
use app\modules\user\models\User;
use yii\filters\AccessControl;*/

    //use app\filters\auth\HttpBearerAuth;
    use yii\filters\auth\HttpBearerAuth;
    use app\models\UserEditForm;
    use Yii;

    use yii\data\ActiveDataProvider;
    use yii\filters\AccessControl;
    use yii\filters\auth\CompositeAuth;
    use yii\helpers\Url;
    use yii\rest\ActiveController;

    use yii\web\HttpException;
    use yii\web\NotFoundHttpException;
    use yii\web\ServerErrorHttpException;

    use app\modules\user\models\LoginForm;
    use app\modules\user\models\User;
/**
 * Default controller for the `admin` module
 */
class LoginController extends ActiveController
{
    
    public $modelClass = 'app\modules\user\models\User';
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],
        ];
        
       /* $behaviors['verbs'] = [
              'class' => \yii\filters\VerbFilter::className(),
              'actions' => [
                  'index'  => ['post'],
                  'view'   => ['get'],
                  'create' => ['post'],
                  'update' => ['put'],
                  'delete' => ['delete'],
                  'login'  => ['post'],
                  'me'    =>  ['get', 'post'],
              ],
          ];*/
        
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['index','options', 'login', 
            'signup', 'confirm', 'password-reset-request', 'password-reset-token-verification', 'password-reset'];


        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['view', 'create', 'update', 'delete'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['view', 'create', 'update', 'delete'],
                    'roles' => ['admin', 'manageUsers'],
                ],
                [
                    'allow' => true,
                    'actions'   => ['me'],
                    'roles' => ['user'],
                    //'usertype'
                ]
            ],
        ];

        return $behaviors;
    }
    
    public function actionIndex() {
        
        $model = new LoginForm();
        $model->roles = [
                User::ROLE_USER,
        ];
        
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = $model->getUser();
            $user->generateAccessTokenAfterUpdatingClientInfo(true);

            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);
            $id = implode(',', array_values($user->getPrimaryKey(true)));

            $responseData = [
                'id'    =>  $id,
                'access_token' => $user->access_token,
            ];

            return $responseData;
        } else {
            // Validation error
            throw new HttpException(422, json_encode($model->errors));
        }
    }              

    public function actionOptions($id = null) {
        return "ok";
    }    
}
