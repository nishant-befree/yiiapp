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

class CountController extends Controller {

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

    public static function actionQuery($SQL) {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($SQL);
        $result2 = $command->queryAll();
        return $result2[0]['COUNT(*)'];
    }

    public function actionUnassigntask($param) {
        if ($param == "overdue") {
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
        } else {
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
        }
        return self::actionQuery($SQL);
    }

    public static function actionAssigntask($param) {
        if ($param =="overdue") {
            
        $SQL = "SELECT
  COUNT(*)
FROM (SELECT
        t.id
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
      WHERE (((((ta.id IS NULL)
                AND (Task.task_status_id != '3'))
               AND (t.task_milestone_date < '2017-09-04 11:06:26'))
              AND (t.task_id >= '0'))
             AND (t.is_active = '1'))
          AND (j.is_paused = '0')
      GROUP BY Task.id) sq";        
        } else {
            $SQL="SELECT
                    COUNT(*)
                  FROM (SELECT
                          t.id
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
                        GROUP BY Task.id) sq";
        }
        return self::actionQuery($SQL);
    }

    public function actionMytask($param) {        
            if ($param == "overdue") {
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
        LEFT JOIN adhoc_template_task adhocTask
          ON adhocTask.id = Task.adhoc_task_id
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
        LEFT JOIN `users` AS `us`
          ON us.id = ta.assignee_id
      WHERE ((((((ta.id IS NOT NULL)
                 AND (Task.task_status_id NOT IN(2,3)))
                AND (j.is_paused = '0'))
               AND (typ.id != '25'))
              AND (t.task_id >= '0'))
             AND (t.is_active = '1'))
          AND (t.task_milestone_date < '2017-09-04 11:06:28')
      GROUP BY Task.id) sq";
            } else {
                $SQL = "SELECT
                    COUNT(*)
                  FROM `turnaround_buckets` `t`
                    JOIN task Task
                      ON Task.id = t.task_id
                    LEFT JOIN task_type typ
                      ON typ.id = Task.task_type_id
                    JOIN practices p
                      ON Task.practice_id = p.id
                    JOIN adhoc_template_task adt
                      ON adt.id = Task.adhoc_task_id
                        AND adt.task_type = 3
                    LEFT JOIN `task_assign` AS `ta`
                      ON Task.id = ta.task_id
                  WHERE ((((Task.task_status_id NOT IN(3,2))
                           AND (Task.task_type_id = '68'))
                          AND (t.task_id >= '0'))
                         AND (t.is_active = '1'))
                      AND (t.task_milestone_date < '2017-09-04 11:06:46')";
            }
       
        return self::actionQuery($SQL);
    }

    public function actionInternalqueries($param) {
        if ($param == "overdue") {
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
      WHERE (((((j.is_paused = '0')
                AND (ta.assignee_id = '1'))
               AND (Task.task_status_id NOT IN(2,3)))
              AND (t.task_milestone_date < '2017-09-04 11:06:28'))
             AND (t.task_id >= '0'))
          AND (t.is_active = '1')
      GROUP BY Task.id) sq";
        } else {
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
        LEFT JOIN adhoc_template_task adhocTask
          ON adhocTask.id = Task.adhoc_task_id
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
        LEFT JOIN `users` AS `us`
          ON us.id = ta.assignee_id
      WHERE (((((ta.id IS NOT NULL)
                AND (Task.task_status_id NOT IN(2,3)))
               AND (j.is_paused = '0'))
              AND (typ.id != '25'))
             AND (t.task_id >= '0'))
          AND (t.is_active = '1')
      GROUP BY Task.id) sq";
        }
        return self::actionQuery($SQL);
    }

    public function actionPracticequeries($param) {
        if ($param == "overdue") {
            $SQL = "SELECT
  COUNT(*)
FROM (SELECT
        t.id
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
      WHERE ((((((j.job_submitted = 'Y')
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
                  ORDER BY id DESC) = 0)WHEN j.service_division != 14 
                  THEN((SELECT
                        COUNT(id)
                      FROM task
                      WHERE job_id = t.job_id
                          AND task_status_id IN(1,4)
                      ORDER BY id DESC) = 0
                      OR t.query_category_id = 68)END))
             AND ((SELECT
                     query_milestone_date
                   FROM turnaround_buckets
                   WHERE job_id = t.job_id
                       AND query_type = 'I'
                       AND is_active = 1
                   ORDER BY id ASC
                   LIMIT 1) < '2017-09-04 11:06:28'))
          AND (j.is_paused = '0')
      GROUP BY t.job_id) sq";
        } else {
            $SQL = "SELECT
  COUNT(*)
FROM (SELECT
        t.id
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
                ORDER BY id DESC) = 0)WHEN j.service_division != 14 
                THEN((SELECT
                        COUNT(id)
                      FROM task
                      WHERE job_id = t.job_id
                          AND task_status_id IN(1,4)
                      ORDER BY id DESC) = 0
                      OR t.query_category_id = 68)END))
                AND (j.is_paused = '0')
      GROUP BY t.job_id) sq";
        }
        return self::actionQuery($SQL);
    }

    public function actionWorkflowtask($param) {
        if ($param == "overdue") {
            $SQL = "SELECT
  COUNT(*)
FROM (SELECT
        t.id
      FROM `query_master` `t`
        JOIN jobs j
          ON t.job_id = j.job_id
            AND j.is_active = 1
            AND j.is_deleted = 0
        JOIN clients c
          ON t.client_id = c.id
            AND c.is_active = 1
            AND c.is_deleted = 0
        JOIN turnaround_buckets tb
          ON tb.query_id = t.id
            AND tb.query_type = 'P'
            AND tb.is_active = 1
        JOIN practices p
          ON t.practice_id = p.id
      WHERE ((((j.job_submitted = 'Y')
               AND (j.job_status_id <> '7'))
              AND (t.query_type = 'P'))
             AND (t.resolved = '0'))
          AND (query_milestone_date < '2017-09-04 11:06:45')
      GROUP BY t.job_id) sq";
        } else {
            $SQL = "SELECT COUNT(*) FROM "
                    . "(SELECT t.id FROM `query_master` `t` "
                    . "JOIN jobs j ON t.job_id = j.job_id and j.is_active = 1 and j.is_deleted = 0 "
                    . "JOIN clients c ON t.client_id = c.id and c.is_active = 1 and c.is_deleted = 0 "
                    . "JOIN turnaround_buckets tb ON tb.query_id = t.id AND tb.query_type='P' "
                    . "AND tb.is_active = 1 JOIN practices p ON t.practice_id = p.id"
                    . " WHERE ((((j.job_submitted='Y') AND (j.job_status_id<>'7')) AND (t.query_type='P'))"
                    . " AND (t.resolved='0')) AND (query_milestone_date < '2017-09-04 14:08:43') "
                    . "GROUP BY t.job_id) sq";
        }
        return self::actionQuery($SQL);
    }

    public function actionExceptionreport($param) {
        if ($param == "overdue") {
            $SQL = "";
        } else {
            $SQL = "SELECT COUNT(*) FROM (SELECT t.id FROM `exception_report` `t` "
                    . "JOIN jobs j ON t.job_id = j.job_id and j.is_active = 1 and j.is_deleted = 0 "
                    . "JOIN job_type jt ON j.job_type_id = jt.id "
                    . "JOIN clients c ON t.client_id = c.id and c.is_active = 1 "
                    . "LEFT JOIN practices p ON t.practice_id = p.id "
                    . "LEFT JOIN `practices_users` AS `pu` ON pu.practice_id = t.practice_id "
                    . "AND pu.service_division_id=j.service_division "
                    . "LEFT JOIN `task` AS task ON t.job_id = task.job_id AND task_type_id NOT IN(68) "
                    . "LEFT JOIN `task_assign` AS `ta` ON ta.task_id = task.id "
                    . "WHERE (j.job_submitted='Y') AND (j.job_status_id<>'7') GROUP BY t.job_id) sq";
        }
        return self::actionQuery($SQL);
    }

    public function actionDocumentbuckets($param) {
        if ($param == "overdue") {
            $SQL = "";
        } else {
            $SQL = "SELECT COUNT(*) FROM "
                    . "(SELECT t.id FROM `documents` `t` "
                    . "LEFT JOIN `task` AS task ON t.job_id = task.job_id "
                    . "LEFT JOIN `task_assign` AS `ta` ON ta.task_id = task.id "
                    . "JOIN jobs j ON j.job_id = t.job_id "
                    . "JOIN practices p ON p.id = j.practice_id "
                    . "LEFT JOIN clients c ON j.client_id = c.id "
                    . "LEFT JOIN users u ON u.id = t.viewed_by "
                    . "Left JOIN `practices_users` AS `pu` ON pu.practice_id = j.practice_id "
                    . "WHERE (((((j.job_status_id<>' 7') AND (j.is_active='1')) "
                    . "AND (p.is_active='1')) AND ((t.query_id IS NULL OR t.query_id = ''))) "
                    . "AND (DATEDIFF(NOW(),t.downloaded_date)<=7 OR t.downloaded_date IS NULL "
                    . "OR t.downloaded_date = '0000-00-00 00:00:00')) AND (viewed='0') GROUP BY t.id) sq";
        }
        return self::actionQuery($SQL);
    }

    public function actionJobsinqueue($param) {
        if ($param == "overdue") {
            $SQL = "";
        } else {
            $SQL = "SELECT COUNT(*) FROM (SELECT t.id FROM `timesheet_approval` `t` "
                    . "LEFT JOIN users u ON u.id = t.user_id WHERE ((t.is_active='1') "
                    . "AND (t.timesheet_date < '2017-09-04')) AND (u.role='11') GROUP BY t.id) sq";
        }
        return self::actionQuery($SQL);
    }

    public function actionPracticeSummary($param) {
        if ($param == "overdue") {
            $SQL = "";
        } else {
            $SQL = "SELECT COUNT(*) FROM "
                    . "(SELECT t.id,t.user_id,t.timesheet_date, t.activity_names, "
                    . "t.timesheet_comments, t.actual_units,t.is_approved,"
                    . "CONCAT_WS(' ',first_name,last_name) AS user_fullname "
                    . "FROM `timesheet_approval` `t` "
                    . "LEFT JOIN users u ON u.id = t.user_id WHERE (((t.is_active='1') "
                    . "AND (t.timesheet_date < '2017-09-04')) AND (u.role='11')) AND (is_approved='0')"
                    . " GROUP BY t.id) sq";
        }
        return self::actionQuery($SQL);
    }

    public function actionKnockbackTask($param) {
        if ($param == "overdue") {
            $SQL = "";
        } else {
            $SQL = "SELECT COUNT(*) FROM "
                    . "(SELECT t.id FROM `turnaround_buckets` `t` "
                    . "JOIN task Task ON Task.id = t.task_id "
                    . "LEFT JOIN task_type typ ON typ.id = Task.task_type_id "
                    . "JOIN jobs j ON j.job_id = Task.job_id "
                    . "JOIN practices p ON Task.practice_id = p.id "
                    . "JOIN clients c ON Task.client_id = c.id "
                    . "LEFT JOIN job_type jt ON Task.job_type_id = jt.id "
                    . "LEFT JOIN `task_assign` AS `ta` ON Task.id = ta.task_id "
                    . "LEFT JOIN `practices_users` AS `pu` ON pu.practice_id = Task.practice_id "
                    . "LEFT JOIN adhoc_template_task adhocTask ON adhocTask.id = Task.adhoc_task_id "
                    . "WHERE (((((j.is_paused='0') AND (ta.assignee_id='1')) "
                    . "AND (Task.task_status_id NOT IN(2,3))) AND (t.task_milestone_date < '2017-09-04 11:06:56')) "
                    . "AND (t.task_id >='0')) AND (t.is_active='1') GROUP BY Task.id) sq";
        }
        return self::actionQuery($SQL);
    }

    public function actionTimesheetArppoval($param) {
        if ($param == "waiting") {
            
            $SQL = "SELECT COUNT(*) FROM "
                    . "(SELECT t.id FROM `turnaround_buckets` `t` JOIN task Task ON Task.id = t.task_id "
                    . "LEFT JOIN task_type typ ON typ.id = Task.task_type_id JOIN jobs j ON j.job_id = Task.job_id "
                    . "LEFT JOIN adhoc_template_task adhocTask ON adhocTask.id = Task.adhoc_task_id "
                    . "JOIN practices p ON Task.practice_id = p.id JOIN clients c ON Task.client_id = c.id "
                    . "LEFT JOIN job_type jt ON Task.job_type_id = jt.id LEFT JOIN `task_assign` AS `ta` ON Task.id = ta.task_id "
                    . "LEFT JOIN `practices_users` AS `pu` ON pu.practice_id = Task.practice_id "
                    . "LEFT JOIN `users` AS `us` ON us.id = ta.assignee_id "
                    . "WHERE ((((((ta.id IS NOT NULL) AND (Task.task_status_id NOT IN(2,3))) AND (j.is_paused='0')) "
                    . "AND (typ.id !='25')) AND (t.task_id >='0')) AND (t.is_active='1')) "
                    . "AND (t.task_milestone_date < '2017-09-04 13:08:09') GROUP BY Task.id) sq";

        } else {
            $SQL = "SELECT COUNT(*) FROM "
                    . "(SELECT t.id FROM `turnaround_buckets` `t` JOIN task Task ON Task.id = t.task_id "
                    . "LEFT JOIN task_type typ ON typ.id = Task.task_type_id "
                    . "JOIN jobs j ON j.job_id = Task.job_id "
                    . "JOIN practices p ON Task.practice_id = p.id "
                    . "JOIN clients c ON Task.client_id = c.id "
                    . "LEFT JOIN job_type jt ON Task.job_type_id = jt.id "
                    . "LEFT JOIN `task_assign` AS `ta` ON Task.id = ta.task_id "
                    . "LEFT JOIN `practices_users` AS `pu` ON pu.practice_id = Task.practice_id "
                    . "LEFT JOIN adhoc_template_task adhocTask ON adhocTask.id = Task.adhoc_task_id "
                    . "WHERE ((((j.is_paused='0') AND (ta.assignee_id='1')) "
                    . "AND (Task.task_status_id NOT IN(2,3))) AND (t.task_id >='0')) "
                    . "AND (t.is_active='1') GROUP BY Task.id) sq";
        }
        return self::actionQuery($SQL);
    }

    public function actionIndex2() {
        $CountArray = array();
        $CountArray['unassignedOverdueCount'] = $this->actionUnassigntask('overdue');  // get overdue task count added by Jayesh on 06/07/2016
        $CountArray['assignedOverdueCount'] = $this->actionAssigntask('overdue'); // get overdue task count by Jayesh on 06/07/2016
        $CountArray['mytaskOverdueCount'] = $this->actionMytask('overdue'); // get overdue task count by Jayesh on 06/07/2016
        $CountArray['IQOverdueCount'] = $this->actionInternalqueries('overdue'); // get overdue internal query by Jayesh on 06/07/2016
        $CountArray['PQOverdueCount'] = $this->actionPracticequeries('overdue'); // get overdue internal query by Jayesh on 06/07/2016
        $CountArray['workflowOverdueCount'] = $this->actionWorkflowtask('overdue'); // get overdue task count by Jayesh on 06/07/2015
        $CountArray['myCount'] = $this->actionMytask('tab_menu');
        $CountArray['unassignedCount'] = $this->actionUnassigntask('tab_menu');
        $CountArray['assignedCount'] = $this->actionAssigntask('tab_menu');
        $CountArray['internalQueriesCount'] = $this->actionInternalqueries('tab_menu');
        $CountArray['practiceQueriesCount'] = $this->actionPracticequeries('tab_menu');
        $CountArray['exceptionReportCount'] = $this->actionExceptionreport('tab_menu');
        $CountArray['workflowTaskCount'] = $this->actionWorkflowtask('tab_menu');
        $CountArray['downloaddocumentCount'] = $this->actionDocumentbuckets('tab_menu');
        $CountArray['jobsinqueueCount'] = $this->actionJobsinqueue('tab_menu');
        $CountArray['practiceSummary'] = $this->actionPracticeSummary('tab_menu');
        //$staffSummary = $this->actionStaffSummary('tab_menu');
        //added by jigar prajapti to set knockback task counter;
        $CountArray['knockbackTaskCounter'] = $this->actionKnockbackTask('tab_menu');
        $CountArray['timesheetApprovalCounter'] = $this->actionTimesheetArppoval('tab_menu');
        $CountArray['timesheetWaitingApprovalCounter'] = $this->actionTimesheetArppoval('waiting');
        return $CountArray;
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
