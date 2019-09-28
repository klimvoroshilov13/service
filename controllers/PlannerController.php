<?php

namespace app\controllers;

use app\models\PlannerFilter;
use Yii;
use app\models\Requests;
use app\modules\admin\models\UserControl;
use app\models\Planner;
use app\models\PlannerSearch;
use app\models\PlannerCopy;
use app\models\PlannerState;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\services\DataFromModel;
use yii\helpers\ArrayHelper;
use app\components\helper\Datehelper;
/**
 * Контроллер PlannerController(управление планировщиком)
 */
class PlannerController extends Controller
{
    public $layout = 'service';

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

    public function actionIndex()
    {
        /** @var UserControl $userModel */
        $userModel = Yii::$app->user->identity;
        $role = $userModel->role;
        $session = Yii::$app->session;
        $get = Yii::$app->request->get();
        $getIndex = 0;
        foreach ($get as $key=>$value){
            !($key=='stateRequest') && !($key=='_pjax') && isset($key) ? $getIndex++:null;
        }

        $stateModel = new PlannerState();
        $modelFilter = new PlannerFilter();
        $searchModel = new PlannerSearch();

        !($get['stateRequest']) ? $get['stateRequest'] ='curdate':null;
        if ($get['stateRequest']=='curdate'){
           $stateModel->autochange();
        }

        if($get['stateRequest']=='all'){
            $session->has('month') && $getIndex == 0 ? $session->remove('month'):null;
            $session->has('year') && $getIndex == 0 ? $session->remove('year'):null;
        }

        if($modelFilter->load(Yii::$app->request->post())){
            $session->set('month', $modelFilter->month);
            $session->set('year', $modelFilter->year);
        }

        // Jobs with $query
        $query = Yii::$app->request->queryParams;

        if ($query['PlannerSearch']['name_jobs']){
           list($jobs,$customer) = explode("-",str_ireplace(" ","",$query['PlannerSearch']['name_jobs']));
           $query['PlannerSearch']['name_jobs']=$jobs;
           $query['PlannerSearch']['name_customers']=$customer;
        }

        if (preg_match('%^\p{Lu}%u', $query['PlannerSearch']['name_jobs'])){
            $query['PlannerSearch']['name_customers']=$query['PlannerSearch']['name_jobs'];
            $query['PlannerSearch']['name_jobs']=null;
        }

        $dataProvider = $searchModel
            ->search(
                $query,
                $get['stateRequest'],
                $session->get('month'),
                $session->get('year')
            );

        !(isset($modelFilter->month)) && $getIndex == 0
            ? $modelFilter->month = Datehelper::setCurrentDate('m')
            : $modelFilter->month = $session->get('month');
        !(isset($modelFilter->year)) && $getIndex == 0
            ? $modelFilter->year = Datehelper::setCurrentDate('y')
            : $modelFilter->year = $session->get('year');

        $modelCopy = new PlannerCopy();
        return $this->render('index', [
               'searchModel' => $searchModel,
               'dataProvider' => $dataProvider,
               'stateRequest'=>$get['stateRequest'],
               'page'=>$get['page'],
               'perPage'=>$get['per-page'],
               'modelCopy'=> $modelCopy,
               'modelFilter'=> $modelFilter,
               'role'=>$role
           ]);
    }


    public function actionView($id)
    {
        Yii::$app->defaultRoute ='planner';
        try {
           $model=$this->findModel($id);
        } catch (NotFoundHttpException $e) {
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }


    public function actionCreate()
    {
        /* @var $userModel UserControl  */
        $userModel = Yii::$app->user->identity;
        $role = $userModel->role;

        $model = new Planner();
        $data = new DataFromModel();
        $stateModel = new PlannerState();
        $modelPlannerArray = $data->getDataArray($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->name_jobs =='заявка' ? $stateModel->requestchange($model):null;
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'role'=>$role,
                'modelPlannerArray'=>$modelPlannerArray
            ]);
        }
    }


    public function actionUpdate($id)
    {

        /** @var UserControl $userModel */
        $userModel = Yii::$app->user->identity;
        $role = $userModel->role;

        try {
            $model = $this->findModel($id);
        } catch (NotFoundHttpException $e) {

        }
        $stateRequest = Yii::$app->request->get('stateRequest');
        Datehelper::setCurrentDate("Y-m-d")<= $model->date ? $status_contracts="active":$status_contracts="all";

        $data = new DataFromModel();
        $stateModel = new PlannerState();
        /** @var Planner $model */
        $modelPlannerArray = $data->getDataArray($model,$status_contracts);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->name_jobs =='заявка' ? $stateModel->requestchange($model):null;
            return $this->redirect(['index', 'id' => $model->id, 'stateRequest'=>$stateRequest]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'role'=>$role,
                'modelPlannerArray'=>$modelPlannerArray
            ]);
        }
    }

    public function actionCopy($id)
    {
        $modelCopy = new PlannerCopy();
        $stateRequest = Yii::$app->request->get('stateRequest');
        if ($modelCopy->load(Yii::$app->request->post()) && $modelCopy->validate()) {
            $modelCopy->copy($id);
            return $this->redirect(['index','Result'=>true]);
        } else {
            return $this->redirect(['index','Result'=>false]);
        }
    }

    public function actionDelete($id)
    {

        $page = Yii::$app->request->get('page');
        $perPage = Yii::$app->request->get('per-page');
        $stateRequest = Yii::$app->request->get('stateRequest');

        try {
            $this->findModel($id)->delete();
        } catch (StaleObjectException $e) {

        } catch (NotFoundHttpException $e) {

        } catch (\Exception $e) {

        }

        if ($page) {
            $url = [
                'index',
                'stateRequest'=>$stateRequest,
                'page'=>$page,
                'per-page'=>$perPage
            ];
        } else {
            $url = [
                'index',
                'stateRequest'=>$stateRequest,
            ];
        }
        return $this->redirect($url);
    }

    /**
     * @throws NotFoundHttpException
     **/
    protected function findModel($id)
    {
        if (($model = Planner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param string $defaultRoute
     * @return PlannerController
     */
}
