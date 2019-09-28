<?php

/**
 * Created by Nikolay N. Kazakov
 * File: index.php
 * Date: 24.09.2019
 * Time: 14:43
 */

namespace app\controllers;

use Yii;
use app\models\Parts;
use app\models\PartsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\services\DataFromModel;
use yii\helpers\ArrayHelper;
use app\components\helper\Datehelper;

/**
 * PartsController implements the CRUD actions for Parts model.
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
     * Lists all Parts models.
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
     * Displays a single Parts model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Parts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Parts();
        $data = new DataFromModel();
        $modelPlannerArray = $data->getDataArray($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'modelPlannerArray'=>$modelPlannerArray
            ]);
        }
    }

    /**
     * Updates an existing Parts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Parts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Parts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Parts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Parts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
