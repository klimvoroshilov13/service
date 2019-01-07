<?php

namespace app\controllers;

use app\models\PlannerFilter;
use Yii;
use app\models\Requests;
use app\modules\admin\models\UserControl;
use app\models\Planner;
use app\models\PlannerSearch;
use app\models\PlannerCopy;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\services\DataFromPlanner;
use yii\helpers\ArrayHelper;
/**
 * Контроллер PlannerController(управление планировщиком)
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
        /* @var $userModel UserControl  */
        $stateRequest = Yii::$app->request->get('stateRequest');
        $page = Yii::$app->request->get('page');
        $perPage = Yii::$app->request->get('per-page');
        !($stateRequest) ? $stateRequest ='curdate':null;

        if ($stateRequest=='curdate'){
            $query = Planner::find()
                ->where('date = CURDATE() AND name_status = "ожидание" AND name_performers1 != "null"')
                ->column();
             if ($query){
                foreach ($query as $value){
                    try {
                        $model = $this->findModel($value);
                    } catch (NotFoundHttpException $e) {
                    }
                    $model->name_status = 'выполняется';
                    $model->save();
                }
              }

            $query = Planner::find()
                ->where('(date < CURDATE()) AND (name_status = "выполняется" OR name_status = "ожидание")')
                ->column();
            if ($query){
                foreach ($query as $value){
                    try {
                        $model = $this->findModel($value);
                    } catch (NotFoundHttpException $e) {
                    }
                    /** @var object $model */
                    switch ($model->name_status){
                        case 'выполняется':
                            $model->name_status = 'завершена';
                            $model->save();
                            break;
                        case 'ожидание':
                            if ($model->name_performers1 == null){
                            try {
                                $model->date = Yii::$app->formatter->asDatetime(setCurrentDate(), "php:Y-m-d");
                            } catch (InvalidConfigException $e) {
                            }
                            $model->day_week = getDayRus($model->date);
                            $model->save();
                        }
                    }
                    $model->name_jobs =='заявка' ? $modelRequest = Requests::findOne($model->info_request):null;
                    /** @var object $modelRequest */
                    if ($modelRequest){
                        $modelRequest->scenario = Requests::SCENARIO_USER;
                        $modelRequest->name_performers = $model->name_performers1;
                        $modelRequest->name_status = $model->name_status;
                        $modelRequest->save();
                    }
                }
            }
        }

        $modelFilter = new PlannerFilter();
        $modelFilter->load(Yii::$app->request->post()) ? $month = $modelFilter->month:$month=null;
        $searchModel = new PlannerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$stateRequest,$month);

        $userModel = Yii::$app->user->identity;
        $role = $userModel->role;

        $model = new Planner();
        $modelCopy = new PlannerCopy();
        $data = new DataFromPlanner();
        $modelPlannerArray = $data->getDataArray($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'stateRequest'=>$stateRequest,
                'page'=>$page,
                'perPage'=>$perPage,
                'model' => $model,
                'modelCopy'=> $modelCopy,
                'modelFilter'=> $modelFilter,
                'role'=>$role,
                'modelPlannerArray'=>$modelPlannerArray,
                'month'=>$modelFilter->month
            ]);
        }

    }


    public function actionView($id)
    {
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
        $data = new DataFromPlanner();
        $modelPlannerArray = $data->getDataArray($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->name_jobs =='заявка' ? $modelRequest = Requests::findOne($model->info_request):null;
            if ($modelRequest){
                $modelRequest->scenario = Requests::SCENARIO_USER;
                $modelRequest->name_performers = $model->name_performers1;
                $modelRequest->name_status = $model->name_status;
                $modelRequest->save();
            }
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
        /* @var $userModel UserControl  */
        $userModel = Yii::$app->user->identity;
        $role = $userModel->role;

        try {
            $model = $this->findModel($id);
        } catch (NotFoundHttpException $e) {

        }
        $stateRequest = Yii::$app->request->get('stateRequest');

        $data = new DataFromPlanner();
        $modelPlannerArray = $data->getDataArray($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->name_jobs =='заявка' ? $modelRequest = Requests::findOne($model->info_request):null;
            if ($modelRequest){
                $modelRequest->scenario = Requests::SCENARIO_USER;
                $modelRequest->name_performers = $model->name_performers1;
                $modelRequest->name_status = $model->name_status;
                $modelRequest->save();
            }
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
        try {
            $copyModel = $this->findModel($id);
        } catch (NotFoundHttpException $e) {
        }

        $modelCopy = new PlannerCopy();
        $stateRequest = Yii::$app->request->get('stateRequest');

        if ($modelCopy->load(Yii::$app->request->post()) && $modelCopy->validate()) {
            $modelCopy->dateEnd && date($modelCopy->dateEnd) > date($modelCopy->dateStart)  ?
                $sumDays = date($modelCopy->dateEnd)-date($modelCopy->dateStart):
                $sumDays = 0;
            for ($i=0;$i<=($sumDays);$i++){
                $model = new Planner();
                $model->attributes = $copyModel->attributes ;
                $model->date = date('Y-m-d H:i',strtotime($modelCopy->dateStart. ' + '.$i.' days'));
                $model->name_status = 'ожидание';
                $model->save();
            }
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
            $url=[
                'index',
                'stateRequest'=>$stateRequest,
                'page'=>$page,
                'per-page'=>$perPage
            ];
        } else {
            $url=[
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
}
