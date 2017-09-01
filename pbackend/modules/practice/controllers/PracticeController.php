<?php

namespace pbackend\modules\practice\controllers;

use Yii;
//use pbackend\modules\practice\models\Practices;
//use pbackend\modules\practice\models\PracticesSearch;
use yii\web\Controller;
//use yii\web\NotFoundHttpException;
//use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\data\SqlDataProvider;



/**
 * PracticeController implements the CRUD actions for Practices model.
 */
class PracticeController extends ActiveController
{
    
    public $modelClass= "pbackend\modules\practice\models\Practices";
    
    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        //$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        // prepare and return a data provider for the "index" action
        $FieldsMain = array();
        
        $FieldsMain[] = " t.*, tb.is_urgent, tb.is_knockback, tb.is_practice_urgent, j.milestone_date ";
        
        // First Query Created On
        $FieldsMain[] = "(SELECT
                query_milestone_date
              FROM turnaround_buckets
              WHERE job_id = t.job_id
                  AND query_type = \"P\"
                  AND is_active = \"1\"
              ORDER BY id ASC
              LIMIT 1) AS first_query_created_on";
        
        $FieldsMain[] = "(CASE "
                            . "WHEN j.milestone_date IS NULL "
                            . "THEN (SELECT query_milestone_date "
                                . "FROM turnaround_buckets "
                                . "WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" "
                                    . "ORDER BY id ASC LIMIT 1) "
                            . "WHEN j.milestone_date = \"0000-00-00 00:00:00\" "
                            . "THEN (SELECT query_milestone_date "
                                    . "FROM turnaround_buckets "
                                    . "WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" "
                                    . "ORDER BY id ASC LIMIT 1) "
                            . "WHEN (SELECT query_milestone_date FROM turnaround_buckets "
                                    . "WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" "
                                    . "ORDER BY id ASC LIMIT 1) < j.milestone_date "
                            . "THEN (SELECT query_milestone_date "
                                    . "FROM turnaround_buckets "
                                    . "WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" "
                                    . "ORDER BY id ASC LIMIT 1) "
                            . "WHEN (SELECT query_milestone_date "
                                    . "FROM turnaround_buckets "
                                    . "WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" "
                                    . "ORDER BY id ASC LIMIT 1) > j.milestone_date "
                            . "THEN j.milestone_date "
                            . "WHEN (SELECT query_milestone_date "
                                    . "FROM turnaround_buckets "
                                    . "WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" "
                                    . "ORDER BY id ASC LIMIT 1) = j.milestone_date "
                            . "THEN j.milestone_date "
                            . "ELSE (SELECT query_milestone_date "
                                    . "FROM turnaround_buckets "
                                    . "WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" "
                                    . "ORDER BY id ASC LIMIT 1) "
                            . "END) AS lessDate";
        
        $FieldsMain[] = "(SELECT
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
                                       AND qr.query_reply_type = \"P\") = 0) AS querysentcount";
        
        $FieldsMain[] = "(SELECT
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
                                        LIMIT 1) = 0)) AS pendingcount";
        
        $FieldsMain[] = "(SELECT
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
                                    LIMIT 1) = 1)) AS repliedcount1";
        
        $FieldsMain[] = "(SELECT
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
                                   AND q.pending_for_practice = 1)) AS repliedcount2";
        
        $FieldsMain[] = "(SELECT
                            COUNT(DISTINCT q.id)
                          FROM query_master q
                          WHERE q.resolved = 0
                              AND q.job_id = j.job_id
                              AND q.ans_type = 7) AS transactionQueryCount";
        
        $TableJoins = array();
        $TableJoins[] = "JOIN jobs j
                          ON t.job_id = j.job_id
                            AND j.is_active = 1
                            AND j.is_deleted = 0";
                            
        $TableJoins[] = "JOIN clients c
                          ON t.client_id = c.id
                            AND c.is_active = 1
                            AND c.is_deleted = 0";
        
        $TableJoins[] = "JOIN turnaround_buckets tb
                          ON tb.query_id = t.id
                            AND tb.query_type = \"P\"
                            AND tb.is_active = 1";
        $TableJoins[] = "JOIN practices p
                          ON t.practice_id = p.id";
        
        $Conditions = "(((j.job_submitted = 'Y')
                              AND (j.job_status_id <> '7'))
                             AND (t.query_type = 'P'))
                          AND (t.resolved = '0')";
        
        $GroupBy    =  "  t.job_id";
        $OrderBy    =  "  tb.is_practice_urgent DESC, "
                            . "tb.is_urgent DESC, tb.is_knockback DESC, "
                            . "lessDate ASC, tb.created_on ASC";
        
        $TableName = "`query_master` `t`";
                
        $SQL = "SELECT "
                    . implode(",", $FieldsMain)
                    . " FROM ".$TableName
                    . implode(" ",$TableJoins)
                        ." WHERE ".$Conditions
                        ." GROUP BY ".$GroupBy
                        ." ORDER BY ".$OrderBy
                ;
                
        //$connection = Yii::$app->getDb();
       // $command = $connection->createCommand($SQL);
//        $result = $command->queryAll();
//        return $result;
        
        //$query = new Query();
//        $provider = new ActiveDataProvider([
//            'query' => $command->queryAll(),
//            'pagination' => [
//                'pageSize' => 20,
//            ],
//        ]);
//
//        // get the posts in the current page
//        $posts = $provider->getModels();
        $count = Yii::$app->db->createCommand($SQL)->queryScalar();
        
        $dataProvider = new SqlDataProvider([
      'sql' => $SQL,
      //'params' => [':status' => 1],
      'totalCount' => $count,
      'sort' => [
          'attributes' => [
              'age',
              'name' => [
                  //'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
                  //'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
                  'default' => SORT_DESC,
                  //'label' => 'Name',
              ],
          ],
      ],
      'pagination' => [
          'pageSize' => 20,
      ],
  ]);
        return $dataProvider->getModels();
    }
}
/***
    /**
     * @inheritdoc
     */
//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }
//
//    /**
//     * Lists all Practices models.
//     * @return mixed
//     */
//    public function actionIndex()
//    {
//        $searchModel = new PracticesSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//        return $dataProvider;
//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
//    }
//
//    /**
//     * Displays a single Practices model.
//     * @param integer $id
//     * @return mixed
//     */
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }
//
//    /**
//     * Creates a new Practices model.
//     * If creation is successful, the browser will be redirected to the 'view' page.
//     * @return mixed
//     */
//    public function actionCreate()
//    {
//        $model = new Practices();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
//    }
//
//    /**
//     * Updates an existing Practices model.
//     * If update is successful, the browser will be redirected to the 'view' page.
//     * @param integer $id
//     * @return mixed
//     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
//    }
//
//    /**
//     * Deletes an existing Practices model.
//     * If deletion is successful, the browser will be redirected to the 'index' page.
//     * @param integer $id
//     * @return mixed
//     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }
//
//    /**
//     * Finds the Practices model based on its primary key value.
//     * If the model is not found, a 404 HTTP exception will be thrown.
//     * @param integer $id
//     * @return Practices the loaded model
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    protected function findModel($id)
//    {
//        if (($model = Practices::findOne($id)) !== null) {
//            return $model;
//        } else {
//            throw new NotFoundHttpException('The requested page does not exist.');
//        }
//    }
//}
