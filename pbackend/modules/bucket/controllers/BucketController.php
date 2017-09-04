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

class BucketController extends Controller {

    public function behaviors() {
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
    
    public function actionAssigntask() {
        
        $SQL = "SELECT ta.assignee_id, us.first_name,us.last_name,
             Task.task_stage_id as task_stage_id,
             Task.task_name as task_name,
             p.company_name as practiceName,
             jt.name as jobTypeName,
             typ.task_type_name as taskTypeName,
             typ.id as taskTypeId,
             jt.id as jobTypeId,
             j.service_division as jobServiceDivision,
            (CASE
                WHEN t.job_milestone_date IS NULL THEN t.task_milestone_date
                WHEN t.job_milestone_date = '0000-00-00 00:00:00' THEN t.task_milestone_date
                WHEN t.task_milestone_date < t.job_milestone_date THEN t.task_milestone_date
                WHEN t.task_milestone_date > t.job_milestone_date THEN t.job_milestone_date
                WHEN t.task_milestone_date = t.job_milestone_date THEN t.job_milestone_date
                ELSE t.task_milestone_date
            END) AS lessDate FROM `turnaround_buckets` `t`  JOIN task Task ON Task.id = t.task_id LEFT JOIN task_type typ ON typ.id = Task.task_type_id JOIN jobs j ON j.job_id = Task.job_id LEFT JOIN adhoc_template_task adhocTask ON adhocTask.id = Task.adhoc_task_id JOIN practices p ON Task.practice_id = p.id  JOIN clients c ON Task.client_id = c.id  LEFT JOIN job_type jt ON Task.job_type_id = jt.id LEFT JOIN `task_assign` AS `ta` ON Task.id = ta.task_id LEFT JOIN `practices_users` AS `pu` ON pu.practice_id = Task.practice_id LEFT JOIN `users` AS `us` ON us.id = ta.assignee_id WHERE (((((ta.id IS NOT NULL) AND (Task.task_status_id NOT IN(2,3))) AND (j.is_paused='0')) AND (typ.id !='25')) AND (t.task_id >='0')) AND (t.is_active='1') GROUP BY Task.id ORDER BY 
            t.is_practice_urgent DESC,
            t.is_urgent DESC,
            t.is_knockback DESC,
            lessDate ASC,
            t.created_on ASC";
        
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($SQL);
        $result = $command->queryAll();
        return $result;
    }
    
    public function actionInternalquery() {
        $SQL = "SELECT
p.company_name,
  tb.is_urgent,
  tb.is_practice_urgent,
  tb.is_knockback,
  tb.job_milestone_date,
  (SELECT
     task_name
   FROM task
   WHERE job_id = t.job_id
       AND task_status_id != 3
       AND adhoc_task_id = 0
   ORDER BY id DESC
   LIMIT 1) AS last_task,
  (SELECT
     query_milestone_date
   FROM turnaround_buckets
   WHERE job_id = t.job_id
       AND query_type = 'I'
       AND is_active = 1
   ORDER BY id ASC
   LIMIT 1) AS first_query_created_on,
  (CASE WHEN j.milestone_date IS NULL THEN (SELECT query_milestone_date FROM turnaround_buckets WHERE job_id=t.job_id AND query_type='I' AND is_active=1 ORDER BY id ASC LIMIT 1) WHEN j.milestone_date = '0000-00-00 00:00:00' THEN (SELECT query_milestone_date FROM turnaround_buckets WHERE job_id=t.job_id AND query_type='I' AND is_active=1 ORDER BY id ASC LIMIT 1) WHEN (SELECT query_milestone_date FROM turnaround_buckets WHERE job_id=t.job_id AND query_type='I' AND is_active=1 ORDER BY id ASC LIMIT 1) < j.milestone_date THEN (SELECT query_milestone_date FROM turnaround_buckets WHERE job_id=t.job_id AND query_type='I' AND is_active=1 ORDER BY id ASC LIMIT 1) WHEN (SELECT query_milestone_date FROM turnaround_buckets WHERE job_id=t.job_id AND query_type='I' AND is_active=1 ORDER BY id ASC LIMIT 1) > j.milestone_date THEN j.milestone_date WHEN (SELECT query_milestone_date FROM turnaround_buckets WHERE job_id=t.job_id AND query_type='I' AND is_active=1 ORDER BY id ASC LIMIT 1) = j.milestone_date THEN j.milestone_date ELSE (SELECT query_milestone_date FROM turnaround_buckets WHERE job_id=t.job_id AND query_type='I' AND is_active=1 ORDER BY id ASC LIMIT 1) END) AS lessDate,
  (SELECT
     COUNT(DISTINCT q.id)
   FROM query_master q
     JOIN jobs j
       ON j.job_id = q.job_id
     LEFT JOIN job_type jt
       ON jt.id = j.job_type_id
   WHERE j.practice_id = q.practice_id
       AND q.query_type = 'I'
       AND q.resolved = 0
       AND q.status = 1
       AND j.is_active = 1
       AND j.is_deleted = 0
       AND q.job_id = t.job_id
       AND q.is_staff = 1
       AND jt.display_in_practice = 'yes'
       AND (SELECT
              COUNT(qr.id)
            FROM query_master_reply qr
            WHERE qr.query_id = q.id
                AND qr.query_reply_type = 'I') = 0) AS querysentcount,
  (SELECT
     COUNT(DISTINCT q.id)
   FROM query_master q
     JOIN jobs j
       ON j.job_id = q.job_id
     LEFT JOIN job_type jt
       ON jt.id = j.job_type_id
   WHERE j.practice_id = q.practice_id
       AND q.query_type = 'I'
       AND q.resolved = 0
       AND q.status = 1
       AND j.is_active = 1
       AND j.is_deleted = 0
       AND q.job_id = t.job_id
       AND jt.display_in_practice = 'yes'
       AND q.pending_for_practice = 0
       AND (((SELECT
                COUNT(qr.id)
              FROM query_master_reply qr
              WHERE qr.query_id = q.id
                  AND qr.query_reply_type = 'I') = 0
             AND q.is_staff = 0)
             OR (SELECT
                   qr.is_staff
                 FROM query_master_reply qr
                 WHERE qr.query_id = q.id
                     AND qr.query_reply_type = 'I'
                 ORDER BY qr.id DESC
                 LIMIT 1) = 1)) AS pendingcount,
  j.is_paused                  AS is_paused,
  j.job_paused_on              AS job_paused_on,
  c.daily_processing_frequency AS clientName
FROM `query_master` `t`
  JOIN jobs j
    ON t.job_id = j.job_id
      AND j.is_active = 1
      AND j.is_deleted = 0
  JOIN job_type jt
    ON j.job_type_id = jt.id
  JOIN clients c
    ON t.client_id = c.id
      AND c.is_active = 1
      AND c.is_deleted = 0
  JOIN turnaround_buckets tb
    ON tb.query_id = t.id
      AND tb.is_active = 1
  JOIN practices p
    ON t.practice_id = p.id
WHERE (((((j.job_submitted = 'Y')
          AND (j.job_status_id <> '7'))
         AND (t.query_type = 'I'))
        AND (t.resolved = '0'))
       AND (CASE WHEN j.service_division = 14
       THEN((SELECT
            COUNT(id)
            FROM task
            WHERE job_id = t.job_id
              AND task_status_id IN(1,4)
              AND task_category IN(2,3)
            ORDER BY id DESC) = 0)WHEN j.service_division != 14 THEN((SELECT
            COUNT(id)
            FROM task
            WHERE job_id = t.job_id
              AND task_status_id IN(1,4)
            ORDER BY id DESC) = 0
            OR t.query_category_id = 68)END))
    AND (j.is_paused = '0')
GROUP BY t.job_id
ORDER BY tb.is_practice_urgent DESC, tb.is_urgent DESC, tb.is_knockback DESC, lessDate ASC, tb.created_on ASC";
     
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($SQL);
        $result = $command->queryAll();
        return $result;
    }
    
    public function actionMytask() {
        $SQL = "SELECT ta.assignee_id, us.first_name,us.last_name,
             Task.task_stage_id as task_stage_id,
             Task.task_name as task_name,
             p.company_name as practiceName,
             jt.name as jobTypeName,
             typ.task_type_name as taskTypeName,
             typ.id as taskTypeId,
             jt.id as jobTypeId,
             j.service_division as jobServiceDivision,
            (CASE
                WHEN t.job_milestone_date IS NULL THEN t.task_milestone_date
                WHEN t.job_milestone_date = '0000-00-00 00:00:00' THEN t.task_milestone_date
                WHEN t.task_milestone_date < t.job_milestone_date THEN t.task_milestone_date
                WHEN t.task_milestone_date > t.job_milestone_date THEN t.job_milestone_date
                WHEN t.task_milestone_date = t.job_milestone_date THEN t.job_milestone_date
                ELSE t.task_milestone_date
            END) AS lessDate FROM `turnaround_buckets` `t`  JOIN task Task ON Task.id = t.task_id LEFT JOIN task_type typ ON typ.id = Task.task_type_id JOIN jobs j ON j.job_id = Task.job_id LEFT JOIN adhoc_template_task adhocTask ON adhocTask.id = Task.adhoc_task_id JOIN practices p ON Task.practice_id = p.id  JOIN clients c ON Task.client_id = c.id  LEFT JOIN job_type jt ON Task.job_type_id = jt.id LEFT JOIN `task_assign` AS `ta` ON Task.id = ta.task_id LEFT JOIN `practices_users` AS `pu` ON pu.practice_id = Task.practice_id LEFT JOIN `users` AS `us` ON us.id = ta.assignee_id WHERE (((((ta.id IS NOT NULL) AND (Task.task_status_id NOT IN(2,3))) AND (j.is_paused='0')) AND (typ.id !='25')) AND (t.task_id >='0')) AND (t.is_active='1') GROUP BY Task.id ORDER BY 
            t.is_practice_urgent DESC,
            t.is_urgent DESC,
            t.is_knockback DESC,
            lessDate ASC,
            t.created_on ASC";
        
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($SQL);
        $result = $command->queryAll();
        return $result;
    }
     public function actionUnassigntask() {
         $SQL = "SELECT
  p.company_name,
  Task.task_name,
  -- Task.task_stage_id,
  -- j.job_type_id,
  
  jt.name            AS jobTypeName,
  (CASE WHEN t.job_milestone_date IS NULL THEN t.task_milestone_date WHEN t.job_milestone_date = '0000-00-00 00:00:00' THEN t.task_milestone_date WHEN t.task_milestone_date < t.job_milestone_date THEN t.task_milestone_date WHEN t.task_milestone_date > t.job_milestone_date THEN t.job_milestone_date WHEN t.task_milestone_date = t.job_milestone_date THEN t.job_milestone_date ELSE t.task_milestone_date END) AS lessDate
FROM `turnaround_buckets` `t`
  JOIN task Task
    ON Task.id = t.task_id
  JOIN task_type typ
    ON typ.id = Task.task_type_id
  JOIN jobs j
    ON j.job_id = Task.job_id
  JOIN practices p
    ON Task.practice_id = p.id
  LEFT JOIN adhoc_template_task adhocTask
    ON adhocTask.id = Task.adhoc_task_id
  JOIN clients c
    ON Task.client_id = c.id
  JOIN job_type jt
    ON Task.job_type_id = jt.id
  LEFT JOIN `task_assign` AS `ta`
    ON Task.id = ta.task_id
  JOIN `practices_users` AS `pu`
    ON pu.practice_id = Task.practice_id
WHERE ((((ta.id IS NULL)
         AND (Task.task_status_id != '3'))
        AND (t.task_id >= '0'))
       AND (t.is_active = '1'))
    AND (j.is_paused = '0')
GROUP BY Task.id
ORDER BY t.is_practice_urgent DESC, t.is_urgent DESC, t.is_knockback DESC, lessDate ASC, t.created_on ASC";
             
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($SQL);
        $result = $command->queryAll();
        return $result;
    }
    
}
