<?php

namespace app\modules\bucket\controllers;

//use yii\web\Controller;
use Yii;
use yii\rest\Controller;

/**
 * Default controller for the `bucket` module
 */
class DefaultController extends Controller
{    
//    public function behaviors()
//    {
//    $behaviors = parent::behaviors();
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
//        // add CORS filter
//        $behaviors['corsFilter'] = [
//            'class' => \yii\filters\Cors::className(),
//            'cors' => [
//                'Origin' => ['*'],
//                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
//                'Access-Control-Request-Headers' => ['*'],
//            ],
//        ];
//
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
//        return $behaviors;
//    } 
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
               
            
        $connection = Yii::$app->getDb();
$command = $connection->createCommand("
    SELECT
  t.*,
  tb.is_urgent,
  tb.is_knockback,
  tb.is_practice_urgent,
  j.milestone_date,
  (SELECT
     query_milestone_date
   FROM turnaround_buckets
   WHERE job_id = t.job_id
       AND query_type = \"P\"
       AND is_active = \"1\"
   ORDER BY id ASC
   LIMIT 1) AS first_query_created_on,
  (CASE WHEN j.milestone_date IS NULL THEN (SELECT query_milestone_date FROM turnaround_buckets WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" ORDER BY id ASC LIMIT 1) WHEN j.milestone_date = \"0000-00-00 00:00:00\" THEN (SELECT query_milestone_date FROM turnaround_buckets WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" ORDER BY id ASC LIMIT 1) WHEN (SELECT query_milestone_date FROM turnaround_buckets WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" ORDER BY id ASC LIMIT 1) < j.milestone_date THEN (SELECT query_milestone_date FROM turnaround_buckets WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" ORDER BY id ASC LIMIT 1) WHEN (SELECT query_milestone_date FROM turnaround_buckets WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" ORDER BY id ASC LIMIT 1) > j.milestone_date THEN j.milestone_date WHEN (SELECT query_milestone_date FROM turnaround_buckets WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" ORDER BY id ASC LIMIT 1) = j.milestone_date THEN j.milestone_date ELSE (SELECT query_milestone_date FROM turnaround_buckets WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" ORDER BY id ASC LIMIT 1) END) AS lessDate,
  (SELECT
     COUNT(DISTINCT q.id)
   FROM query_master q
     JOIN jobs j
       ON j.job_id = q.job_id
     LEFT JOIN job_type jt
       ON jt.id = j.job_type_id
   WHERE j.practice_id = q.practice_id
       AND q.query_type = \"P\"
       AND q.resolved = 0
       AND q.status = 1
       AND j.is_active = 1
       AND j.is_deleted = 0
       AND q.job_id = t.job_id
       AND q.is_staff = 1
       AND jt.display_in_practice = \"yes\"
       AND (SELECT
              COUNT(qr.id)
            FROM query_master_reply qr
            WHERE qr.query_id = q.id
                AND qr.query_reply_type = \"P\") = 0) AS querysentcount,
  (SELECT
     COUNT(DISTINCT q.id)
   FROM query_master q
     JOIN jobs j
       ON j.job_id = q.job_id
     LEFT JOIN job_type jt
       ON jt.id = j.job_type_id
   WHERE j.practice_id = q.practice_id
       AND q.query_type = \"P\"
       AND q.resolved = 0
       AND q.status = 1
       AND j.is_active = 1
       AND j.is_deleted = 0
       AND q.job_id = t.job_id
       AND jt.display_in_practice = \"yes\"
       AND q.pending_for_practice = 0
       AND (((SELECT
                COUNT(qr.id)
              FROM query_master_reply qr
              WHERE qr.query_id = q.id
                  AND qr.query_reply_type = \"P\") = 1
             AND q.is_staff = 0)
             OR (SELECT
                   qr.is_staff
                 FROM query_master_reply qr
                 WHERE qr.query_id = q.id
                     AND qr.query_reply_type = \"P\"
                 ORDER BY qr.id DESC
                 LIMIT 1) = 0)) AS pendingcount,
  (SELECT
     COUNT(DISTINCT q.id)
   FROM query_master q
     JOIN jobs j
       ON j.job_id = q.job_id
     LEFT JOIN job_type jt
       ON jt.id = j.job_type_id
   WHERE j.practice_id = q.practice_id
       AND q.query_type = \"P\"
       AND q.resolved = 0
       AND q.status = 1
       AND j.is_active = 1
       AND j.is_deleted = 0
       AND q.job_id = t.job_id
       AND jt.display_in_practice = \"yes\"
       AND ((SELECT
               qr.is_staff
             FROM query_master_reply qr
             WHERE qr.query_id = q.id
                 AND q.job_id = t.job_id
                 AND qr.query_reply_type = \"P\"
             ORDER BY qr.id DESC
             LIMIT 1) = 1)) AS repliedcount1,
  (SELECT
     COUNT(DISTINCT q.id)
   FROM query_master q
     JOIN jobs j
       ON j.job_id = q.job_id
     LEFT JOIN job_type jt
       ON jt.id = j.job_type_id
   WHERE j.practice_id = q.practice_id
       AND q.query_type = \"P\"
       AND q.resolved = 0
       AND q.status = 1
       AND j.is_active = 1
       AND j.is_deleted = 0
       AND q.job_id = t.job_id
       AND jt.display_in_practice = \"yes\"
       AND ((SELECT
               qr.is_staff
             FROM query_master_reply qr
             WHERE qr.query_id = q.id
                 AND qr.query_reply_type = \"P\"
                 AND q.job_id = t.job_id
             ORDER BY qr.id DESC
             LIMIT 1) = 0
            AND q.pending_for_practice = 1)) AS repliedcount2,
  (SELECT
     COUNT(DISTINCT q.id)
   FROM query_master q
   WHERE q.resolved = 0
       AND q.job_id = j.job_id
       AND q.ans_type = 7) AS transactionQueryCount
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
      AND tb.query_type = \"P\"
      AND tb.is_active = 1
  JOIN practices p
    ON t.practice_id = p.id
WHERE (((j.job_submitted = 'Y')
        AND (j.job_status_id <> '7'))
       AND (t.query_type = 'P'))
    AND (t.resolved = '0')
GROUP BY t.job_id
ORDER BY tb.is_practice_urgent DESC, tb.is_urgent DESC, tb.is_knockback DESC, lessDate ASC, tb.created_on ASC");

$result = $command->queryAll();
return json_encode($result);
//echo "<pre>";print_r($result[0]);exit;
 //return $result;
        //return $this->render('index');
    }
}
