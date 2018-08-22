<?php

namespace app\controllers;

use Yii;
use app\models\LoginForm;
use app\modules\admin\models\UserControl;
use app\models\Planner;
use app\models\PlannerSearch;
use app\models\MailerForm;
use app\models\Jobs;
use app\models\Customers;
use app\models\Contracts;
use app\models\Performers;
use app\models\Status;
use app\models\Requests;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
/**
 * PlannerController implements the CRUD actions for Planner model.
 */
class PlannerController extends Controller
{
    public $layout='requests';

    public function behaviors() // Ограничение доступа к actions
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
                        'actions' => ['logout','index','view','update'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                        'actions' => ['logout','index','view','update','create','delete','mailer'],
                        'allow' => true,
                        'roles' => ['operator'],
                    ],
                    [
                        'actions' => ['logout','index','view','update','create','delete','mailer'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Planner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlannerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Planner model.
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
     * Creates a new Planner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /* @var $userModel UserControl  */

        $model = new Planner();
        $job = ArrayHelper::getValue($model,'name_jobs');
        $jobs = ArrayHelper::map(Jobs::find()->all(),'name','name');
        $userModel = Yii::$app->user->identity;
        $role=$userModel->role;
        $customers = ArrayHelper::map(Customers::find()->all(),'name','name');
        $customer = ArrayHelper::getValue($model,'name_customers');
        $contracts = ArrayHelper::map(Contracts::find()->where(['name_customers'=>$customer,'flag'=>1])->all(),'name','name');
//        $contract = ArrayHelper::getValue($model,'name_contracts');
        $performers = ArrayHelper::map(Performers::find()->where(['flag'=>1])->all(),'name','name');
        $performer1 = ArrayHelper::getValue($model,'name_performers1');
        $performer2 = ArrayHelper::getValue($model,'name_performers2');
        $statuses = ArrayHelper::map(Status::find()->all(),'name','name');
        $status = ArrayHelper::getValue($model,'name_status');
        $requests = ArrayHelper::map(Requests::find()->where(['name_status'=>['ожидание','выполняется','отложена']])->all(),'id','info');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'job' => $job,
                'jobs' => $jobs,
                'role'=>$role,
                'customers'=>$customers,
                'contracts'=>$contracts,
//                'contract'=>$contract,
                'performers'=>$performers,
                'performer1'=>$performer1,
                'performer2'=>$performer2,
                'statuses'=>$statuses,
                'status'=>$status,
                'requests'=>$requests,
            ]);
        }
    }

    /**
     * Updates an existing Planner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /* @var $userModel UserControl  */

        $model = $this->findModel($id);
        $job = ArrayHelper::getValue($model,'name_jobs');
        $jobs = ArrayHelper::map(Jobs::find()->all(),'name','name');
        $userModel = Yii::$app->user->identity;
        $role=$userModel->role;
        $customers = ArrayHelper::map(Customers::find()->all(),'name','name');
        $customer = ArrayHelper::getValue($model,'name_customers');
        $contracts = ArrayHelper::map(Contracts::find()->where(['name_customers'=>$customer,'flag'=>1])->all(),'name','name');
//        $contract = ArrayHelper::getValue($model,'name_contracts');
        $performers = ArrayHelper::map(Performers::find()->where(['flag'=>1])->all(),'name','name');
        $performer1 = ArrayHelper::getValue($model,'name_performers1');
        $performer2 = ArrayHelper::getValue($model,'name_performers2');
        $statuses = ArrayHelper::map(Status::find()->all(),'name','name');
        $status = ArrayHelper::getValue($model,'name_status');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'job' => $job,
                'jobs' => $jobs,
                'role'=>$role,
                'customers'=>$customers,
                'contracts'=>$contracts,
//                'contract'=>$contract,
                'performers'=>$performers,
                'performer1'=>$performer1,
                'performer2'=>$performer2,
                'statuses'=>$statuses,
                'status'=>$status,
            ]);
        }
    }

    /**
     * Deletes an existing Planner model.
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
     * Finds the Planner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Planner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Planner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
