<?php

namespace app\controllers;

use Yii;
use app\modules\admin\models\UserControl;
use app\models\Requests;
use app\models\RequestsSearch;
use app\models\MailerForm;
use app\models\Customers;
use app\models\Contracts;
use app\models\Performers;
use app\models\Status;
use app\models\Planner;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\components\helper\Datehelper;
use yii\web\Response;
use app\services\DataFromModel;

    /**
     *  Класс контроллера RequestsController(управление заявками)
     */
class RequestsController extends Controller
 {

    /**
     * Устанавливает шаблон web-приложения
     */
   public $layout='service';

    /**
     * Ограничивает доступ к web-приложению
     * согласно ролям пользователя
     * и не авторизованным пользователям
     *
     * @return array Правила доступа
     */
   public function behaviors()
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
                        'actions' => ['logout','index','view','update','lists'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                        'actions' => ['logout','index','view','update','create','delete','mailer','lists'],
                        'allow' => true,
                        'roles' => ['operator'],
                    ],
                    [
                        'actions' => ['logout','index','view','update','create','delete','mailer','lists','crops'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Генерирует страницу списка заявок находящихся в работе,
     * т.е со статусом "ожидание" и "выполняется".
     *
     * @return \yii\web\Response|string  возвращает страницу списка заявок
     * находящихся в работе
     */
    public function actionIndex()
    {
        $stateRequest = Yii::$app->request->get('stateRequest');
        !($stateRequest) ? $stateRequest='run':null;
        $page = Yii::$app->request->get('page');
        $stateRequest == null ? $stateRequest='run':null;
        $searchModel = new RequestsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$stateRequest);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stateRequest'=>$stateRequest,
            'page'=>$page,
        ]);
    }

    /**
     * Генерирует контрагента с заявками со статусом "ожидание" и "выполняется"
     * или список заявок со статусом "ожидание" и "выполняется"
     * @param $id string контрагент/номер заявки
     * @param $flag string 'one'- вывод контрагента 'all'- список заявок
     * @param $i integer 0
     *
     * @return boolean возвращает страницу списка заявок
     * находящихся в работе
     */
    public function actionLists($id,$flag ='one',$i=0)
    {
        function uploadData($arr, $count, $flag,$i)
        {
            $data=0;
            switch ($flag) {
                case 'one':
                    if ($count > 0) {
                        foreach ($arr as $item) {
                            echo "<option value='" . $item->name_customers . "'>" . $item->name_customers . "</option>";
                        }
                    } else {
                        echo "<option></option>";
                    }
                break;

                case 'all':
                    if ($count > 0) {
                        echo "<option value=''>Выберите заявку ...</option>";
                        foreach ($arr as $item) {
                            echo "<option value='" . $item->id . "'>" . Yii::$app->formatter->asDatetime($item->date_start, "php:d.m.Y") . ' - ' . $item->short_info . "</option>";
                        }
                    } else {
                        echo "<option></option>";
                    }
                break;

                case 'info':
                    if ($count > 0) {
                        foreach ($arr as $item) {
                            $data="Сведения: ".$item->info.
                                "\r\nКонтакт.лицо: ". $item->contacts.
                                "\r\nТелефон: ". $item->phone;
                        }
                    }
                break;
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [$data,$i];
        }

        $flag == '' ? $flag = 'one' : null;
        $requests=null;

        switch ($flag) {
            case 'one':
                $requests = Requests::find()
                    ->where(['id' => $id, 'name_status' => ['ожидание', 'выполняется', 'отложена']])
                    ->all();
            break;
            case 'all':
                $requests = Requests::find()
                    ->where(['name_status' => ['ожидание', 'выполняется', 'отложена']])
                    ->orderBy(['date_start' => SORT_ASC])
                    ->all();
            break;
            case 'info':
                $requests = Requests::find()
                    ->where(['id' => $id])
                    ->all();
            break;
        }

        $countRequests = count($requests);
        return uploadData($requests, $countRequests, $flag,$i);
    }

    /**
     * Генерирует страницу просмотра конкретной заявки
     * @param $id string номер заявки
     *
     * @return \yii\web\Response|string возвращает страницу
     * просмотра конкретной заявки.
     */
    public function actionView($id)
    {
        try {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } catch (NotFoundHttpException $e) {
            return $this->render('error',[
                'message'=>$e->getMessage(),
            ]);
        }
    }

    /**
     * Генерирует страницу создания новой заявки,
     * взависимости от роли пользователя(admin,operator,user),
     *
     * @return \yii\web\Response|string при удачном создании заявки возвращает
     * страницу просмотра заявки
     * иначе возвращает страницу создания заявки.
     */
    public function actionCreate()
    {
        /* @var $userModel UserControl  */

        $model = new Requests();
        $data = new DataFromModel();
        $userModel = Yii::$app->user->identity;
        $role = $userModel->role;
        $modelRequestArray = $data->getDataArray($model);

        switch ($role){
            case 'admin': $model->scenario = Requests::SCENARIO_ADMIN;
                break;
            case 'operator': $model->scenario = Requests::SCENARIO_OPERATOR;
                break;
            case 'user': $model->scenario = Requests::SCENARIO_USER;
                break;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $modelPlanner = new Planner();
            $modelPlanner->date = $model->date_start;
            $modelPlanner->name_jobs = 'заявка';
            $modelPlanner->name_customers = $model->name_customers;
            $modelPlanner->info_contract = $model->name_contracts;
            $modelPlanner->info_request = (string)$model->id;
            $modelPlanner->name_status = $model->name_status;
            $modelPlanner->name_user=$model->name_user;
            $modelPlanner->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'role'=>$role,
                'modelRequestArray' => $modelRequestArray,
            ]);
        }
    }

    /**
     *  Метод генерирующий страницу обновления заявки,
     * взависимости от роли пользователя(admin,operator,user),
     * при удачном обновлении заявки возвращает страницу просмотра заявки
     * иначе возвращает страницу обновления заявки.
     */
    public function actionUpdate($id)
    {
        /* @var $userModel UserControl  */


    try{
        $data = new DataFromModel();
        $model = $this->findModel($id);
        $userModel=Yii::$app->user->identity;
        $role=$userModel->role;
        $page = Yii::$app->request->get('page');
        $statusRequest= Yii::$app->request->get('stateRequest');
        $modelRequestArray = $data->getDataArray($model);

        $planners = Planner::find()
            ->where('info_request='.$id)
            ->andWhere(['<=','date',Datehelper::setCurrentDate('Y-m-d')])
            ->all();

        switch ($role){
            case 'admin': $model->scenario = Requests::SCENARIO_ADMIN;
                break;
            case 'operator': $model->scenario = Requests::SCENARIO_OPERATOR;
                break;
            case 'user': $model->scenario = Requests::SCENARIO_USER;
                break;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (!$page==null){return $this->redirect(['/requests/index/'.$statusRequest.'/'.$page.'/10']);}
            if (!$model->oldAttributes->name_status==$model->name_status){
                foreach ($planners as $planner){
                    $planner->name_status=$model->name_status;
                    $planner->name_performers1=$model->name_performers;
                    $planner->save();
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'role'=>$role,
                'modelRequestArray' => $modelRequestArray,
                ]);
        }
        }catch (NotFoundHttpException $e){
        return $this->render('error',[
            'message'=>$e->getMessage(),
        ]);
        }

    }

    /**
     *  Метод удаления заявки, возвращает страницу списка заявок
     * находящихся в работе т.е со статусом "ожидание" и "выполняется".
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
            }catch (NotFoundHttpException $e){
            return $this->render('error',[
                'message'=>$e->getMessage(),
                ]);
            } catch (StaleObjectException $e) {
            return $this->render('error',[
            'message'=>$e->getMessage(),
            ]);
            } catch (\Exception $e) {
            return $this->render('error',[
                'message'=>$e->getMessage(),
            ]);
            }
    }
    /**
     *  Метод отправки заявки по email,
     * при удачной отправки возвращает сообщение что сообщение отправлено
     * иначе возвращает страницу с формой отправки заявки.
     */
    public function actionMailer($id)
    {
        $model = new MailerForm();
        try {
            $request = $this->findModel($id);
        } catch (NotFoundHttpException $e) {
            return $this->render('error',[
                'message'=>$e->getMessage(),
            ]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->sendEmail()) {
            Yii::$app->session->setFlash('mailerFormSubmitted');
            return $this->refresh();
        }
        return $this->render('mailer', [
            'model' => $model,
            'request'=> $request
        ]);
    }

    /**
     *  Метод поиска нужной заявки,
     * возвращает нужную заявку из базы данных
     * иначе, исключение с ошибкой.
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Requests::findOne($id)) !== null) {
            return $model;
        } else {
           throw new NotFoundHttpException(Yii::t('yii','The requested page does not exist.'));
        }
    }

    public function actionError()
    {
        $e = Yii::$app->errorHandler->exception;
        if ($e !== null) {
            return $this->render('error', ['exception' => $e,'message'=>$e->getMessage()]);
        }
        return 0;
    }


    public function actionCrops()
    {
        $requests = ArrayHelper::map(Requests::find()->all(),'id','info');
        $id=count($requests);
        $error = 0;
        for ($i=1;$i<=$id;$i++){
            try {
                if ($this->findModel($i)) {
                    $model = $this->findModel($i);
                    $model->short_info = $model->info ? mb_strimwidth($model->info, 0, 45, '...') : null;
                    $model->save(false);
                } else {
                    $error++;
                }
            } catch (NotFoundHttpException $e) {
                return $this->render('error',[
                    'message'=>$e->getMessage(),
                ]);
            }
//
//
//
//            print_r($this->findModel($i));
//            echo "<br><br>";
        }
        echo "Завершено c ".$error." ошибками.";
        return 0;
    }

}
