<?php

namespace app\modules\user\controllers;

use Yii;
//use yii\filters\auth\HttpBasicAuth;
use app\modules\user\models\User;
use yii\rest\ActiveController;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UserController extends ActiveController
{ 
    public $modelClass = 'app\modules\user\models\User';
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
//        $behaviors['authenticator'] = [
//            'class' => HttpBasicAuth::className(),
//            'auth' => [ 'app\modules\user\models\User', 'httpBasicAuth'],
//            'only' => [ 'delete', 'update', 'create', 'index']
//        ];
       
//       $behaviors['authenticator'] = [
//                'class' => CompositeAuth::className(),
//                'authMethods' => [
//                    HttpBearerAuth::className(),
//                ],
//
//        ];
         // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS','HEAD'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

        return $behaviors;
    }
    
    public function actionError()
    {        
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return ['exception' => $exception];
        }
    }
    
    public function actionDirectResponse(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Yii::t('app','pong');        
    }
    
    public function actionGetUser(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Yii::t('app','pong');        
    }
}