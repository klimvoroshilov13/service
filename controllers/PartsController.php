<?php

/**
 * Created by Nikolay N. Kazakov
 * File: index.php
 * Date: 24.09.2019
 * Time: 14:43
 */

namespace app\controllers;

use app\models\Requests;
use Yii;
use app\models\PartsItem;
use app\models\PartsRequest;
use app\models\PartsSearch;
use app\models\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\services\DataFromModel;
use yii\helpers\ArrayHelper;
use app\components\helper\Datehelper;

/**
 * PartsController implements the CRUD actions for PartsItem model.
 */
class PartsController extends Controller
{
    public $layout = 'service';
    /**
     * @inheritdoc
     */
    public function behaviors() // Restricting access to actions
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],

            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'logout',
                            'index',
                            'view',
                            'update',
                            'create',
                            'delete',
                            'copy'
                        ],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                        'actions' => [
                            'logout',
                            'index',
                            'view',
                            'update',
                            'create',
                            'delete'
                        ],
                        'allow' => false,
                        'roles' => ['operator'],
                    ],
                    [
                        'actions' => [
                            'logout',
                            'index',
                            'view',
                            'update',
                            'create',
                            'delete',
                            'copy'
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all PartsItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PartsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PartsItem model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'partsRequest' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PartsItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $partsRequest = new PartsRequest();
//        $partsItem = [new PartsItem()];
        $data = new DataFromModel();
        $modelPlannerArray[0][0] = $data->getDataArray($partsRequest);
        for ($i=0;$i<4;$i++){
            $partsItem[$i]=new PartsItem();
            $modelPlannerArray[1][$i] = $data->getDataArray($partsItem[$i]);
        }

        if ($partsRequest->load(Yii::$app->request->post())) {

            $partsItem = Model::createMultiple(PartsItem::classname());
            Model::loadMultiple($partsItem, Yii::$app->request->post());

            // validate all models
            $valid = $partsRequest->validate();
            $valid = Model::validateMultiple($partsItem) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $partsRequest->save(false)) {
                        foreach ($partsItem as $partItem) {
                            $partItem->part_request = $partsRequest->id;
                            if (! ($flag = $partItem->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
//                        return $this->redirect(['view', 'id' => $partsRequest->id]);
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        } else {
            return $this->render('create', [
                'partsRequest' => $partsRequest,
                'partsItem' => (empty($partsItem)) ? [new PartsItem()] : [new PartsItem()],
                'modelPlannerArray'=>$modelPlannerArray
            ]);
        }
    }

    /**
     * Updates an existing PartsItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $result = $this->findModel($id);
        $partsRequest = $result[2];
        $partsItem = $result[1];
        $data = new DataFromModel();
        $modelPlannerArray = $data->getDataArray($partsRequest);
        $modelPlannerArray = array_merge($modelPlannerArray, $data->getDataArray($partsItem));

        if ($partsRequest->load(Yii::$app->request->post())) {

            $partsItem = Model::createMultiple(PartsItem::classname());
            Model::loadMultiple($partsItem, Yii::$app->request->post());

            // validate all models
            $valid = $partsRequest->validate();
            $valid = Model::validateMultiple($partsItem) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $partsRequest->save(false)) {
                        foreach ($partsItem as $partItem) {
                            $partItem->part_request = $partsRequest->id;
                            if (! ($flag = $partItem->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $partsRequest->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        } else {
            return $this->render('update', [
                'partsRequest' => $partsRequest,
                'partsItem' => (empty($partsItem)) ? [new PartsItem()] : $partsItem,
                'modelPlannerArray'=>$modelPlannerArray
            ]);
        }

    }

    /**
     * Deletes an existing PartsItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $result = $this->findModel($id);
        $result[0]->delete();
        $count = count($result[1]);
        $count == 1 ? $result[2]->delete():null;

        return $this->redirect(['index']);
    }

    /**
     * Finds the PartsItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PartsItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($partsItem = PartsItem::findOne($id)) !== null) {
            $partsItems = PartsItem::findAll(['part_request'=> $partsItem->part_request]);
            $partsRequest = PartsRequest::findOne($partsItem->part_request);
            return [$partsItem,$partsItems,$partsRequest];
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
