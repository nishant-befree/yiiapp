<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\bucket\controllers;

//use yii\web\Controller;
use Yii;
use yii\rest\Controller;

class CountController extends Controller
{    
public function behaviors()
    {
    $behaviors = parent::behaviors();
//
//        $behaviors['authenticator'] = [
//            'class' => CompositeAuth::className(),
//            'authMethods' => [
//                HttpBearerAuth::className(),
//            ],
//        ];
//        
//       $behaviors['verbs'] = [
//              'class' => \yii\filters\VerbFilter::className(),
//              'actions' => [
//                  //'index'  => ['post'],
//                  'indexme'  => ['post'],
//                  'view'   => ['get'],
//                  'create' => ['post'],
//                  'update' => ['put'],
//                  'delete' => ['delete'],
//                  'login'  => ['post'],
//                  'userlogin'  => ['post'],
//                  'me'    =>  ['get', 'post'],
//              ],
//          ];
//        
//        $auth = $behaviors['authenticator'];
//        unset($behaviors['authenticator']);
//
        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

//        // re-add authentication filter
//        $behaviors['authenticator'] = $auth;
//        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
//        $behaviors['authenticator']['except'] = ['Indexme','userlogin','options', 'login', 
//            'signup', 'confirm', 'password-reset-request', 'password-reset-token-verification', 'password-reset'];
//
//
//        // setup access
//        $behaviors['access'] = [
//            'class' => AccessControl::className(),
//            'only' => ['view', 'create', 'update', 'delete'], //only be applied to
//            'rules' => [
//                [
//                    'allow' => true,
//                    'actions' => ['view', 'create', 'update', 'delete'],
//                    'roles' => ['admin', 'manageUsers'],
//                ],
//                [
//                    'allow' => true,
//                    'actions'   => ['me'],
//                    'roles' => ['user'],
//                    //'usertype'
//                ]
//            ],
//        ];
//
        return $behaviors;
    }
    
    public static function actionQuery($SQL) {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($SQL);
        $result2 = $command->queryAll();
        return $result2[0]['COUNT(*)'];
    }

    public function actionUnassigntask() {
        $SQL = "SELECT
                    COUNT(*)
                  FROM (SELECT
                          t.id
                        FROM `turnaround_buckets` `t`
                          JOIN task Task
                            ON Task.id = t.task_id
                          LEFT JOIN task_type typ
                            ON typ.id = Task.task_type_id
                          JOIN jobs j
                            ON j.job_id = Task.job_id
                          JOIN practices p
                            ON Task.practice_id = p.id
                          JOIN clients c
                            ON Task.client_id = c.id
                          LEFT JOIN job_type jt
                            ON Task.job_type_id = jt.id
                          LEFT JOIN `task_assign` AS `ta`
                            ON Task.id = ta.task_id
                          LEFT JOIN `practices_users` AS `pu`
                            ON pu.practice_id = Task.practice_id
                          LEFT JOIN adhoc_template_task adhocTask
                            ON adhocTask.id = Task.adhoc_task_id
                        WHERE ((((j.is_paused = '0')
                                 AND (ta.assignee_id = '1'))
                                AND (Task.task_status_id NOT IN(2,3)))
                               AND (t.task_id >= '0'))
                            AND (t.is_active = '1')
                        GROUP BY Task.id) sq";
        return self::actionQuery($SQL);
        
    }
    
    public static function actionAssigntask() {
        
    }


    public function actionIndex() {
        $CountArray = array();
        $CountArray['unassignedOverdueCount'] = $this->actionUnassigntask('overdue');  // get overdue task count added by Jayesh on 06/07/2016
        $CountArray['assignedOverdueCount'] = $this->actionUnassigntask('overdue'); //actionAssigntask('overdue'); // get overdue task count by Jayesh on 06/07/2016
        $CountArray['mytaskOverdueCount'] = $this->actionUnassigntask('overdue'); //actionMytask('overdue');// get overdue task count by Jayesh on 06/07/2016
        $CountArray['IQOverdueCount'] = $this->actionUnassigntask('overdue'); //actionInternalqueries('overdue'); // get overdue internal query by Jayesh on 06/07/2016
        $CountArray['PQOverdueCount'] = $this->actionUnassigntask('overdue'); //actionPracticequeries('overdue'); // get overdue internal query by Jayesh on 06/07/2016
        $CountArray['workflowOverdueCount'] = $this->actionUnassigntask('overdue'); //actionWorkflowtask('overdue');// get overdue task count by Jayesh on 06/07/2015
        $CountArray['myCount'] = $this->actionUnassigntask('overdue'); //actionMytask('tab_menu');
        $CountArray['unassignedCount'] = $this->actionUnassigntask('overdue'); //actionUnassigntask('tab_menu');
        $CountArray['assignedCount'] = $this->actionUnassigntask('overdue'); //actionAssigntask('tab_menu');
        $CountArray['internalQueriesCount'] = $this->actionUnassigntask('overdue'); //actionInternalqueries('tab_menu');
        $CountArray['practiceQueriesCount'] = $this->actionUnassigntask('overdue'); //actionPracticequeries('tab_menu');
        $CountArray['exceptionReportCount'] = $this->actionUnassigntask('overdue'); //actionExceptionreport('tab_menu');
        $CountArray['workflowTaskCount'] = $this->actionUnassigntask('overdue'); //actionWorkflowtask('tab_menu');
        $CountArray['downloaddocumentCount'] = $this->actionUnassigntask('overdue'); //actionDocumentbuckets('tab_menu');
        $CountArray['jobsinqueueCount'] = $this->actionUnassigntask('overdue'); //actionJobsinqueue('tab_menu');
        $CountArray['practiceSummary'] = $this->actionUnassigntask('overdue'); //actionPracticeSummary('tab_menu');
        //$staffSummary = $this->actionUnassigntask('overdue'); //actionStaffSummary('tab_menu');
        //added by jigar prajapti to set knockback task counter;
        $CountArray['knockbackTaskCounter'] = $this->actionUnassigntask('overdue'); //actionKnockbackTask('tab_menu');
        $CountArray['timesheetApprovalCounter'] = $this->actionUnassigntask('overdue'); //actionTimesheetArppoval('tab_menu');
        $CountArray['timesheetWaitingApprovalCounter'] = $this->actionUnassigntask('overdue'); //actionTimesheetArppoval('waiting');
        return $CountArray;
    }
}