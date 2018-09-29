<?php

namespace app\controllers;

use Yii;
use app\models\LoginForm;
use app\modules\admin\models\UserControl;
use app\models\Requests;
use app\models\RequestsSearch;
use app\models\MailerForm;
use app\models\Customers;
use app\models\Contracts;
use app\models\Performers;
use app\models\Status;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 *  Класс контроллера RequestsController(управление заявками)
 */

class RequestsController extends Controller
{

    /**
     *  Унаследованное свойство класса Component,
     * устанавливающее шаблон для web приложения
     */
    public $layout='requests';
   /**
    *  Унаследованный метод класса Component,
    * данный метод ограничивает доступ к web приложению
    * согласно ролям пользователя и не авторизованным пользователям,
    * возращает массив с правилами доступа к web приложению
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
                        'actions' => ['logout','index','view','update'],
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
   *  Метод входа в web приложение,
   * возвращает главную страницу web приложения в случае авторизации
   * и страницу входа если пользователь не авторизован.
   */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

  /**
   * Метод выхода из Web-приложения,
   * возвращает страницу входа в web приложение при выходе.
   */
    public function actionLogout()
        {
        Yii::$app->user->logout();

        return $this->goHome();
    }

   /**
    *  Метод генерирующий страницу списка заявок находящихся в работе,
    * возвращает страницу списка заявок находящихся в работе
    * т.е со статусом "ожидание" и "выполняется".
    */
    public function actionIndex()
    {
        $stateRequest = Yii::$app->request->get('stateRequest');
        !($stateRequest) ? $stateRequest='run':null;
        $page = Yii::$app->request->get('page');
        $stateRequest==null ? $stateRequest='run':null;
        $searchModel = new RequestsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$stateRequest);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stateRequest'=>$stateRequest,
            'page'=>$page,
        ]);
    }


    public function actionLists($id,$flag='one')
    {
         function uploadData($arr,$count,$flag){
          switch ($flag) {
              case 'one':if ($count > 0){
                         foreach ($arr as $item){
                             echo"<option value='".$item->name_customers."'>".$item->name_customers."</option>";
                         }
                     }else{
                         echo"<option></option>";
                     }
              break;
              case 'all':if ($count > 0){
                  echo"<option value=''>Выберите заявку ...</option>";
                  foreach ($arr as $item){
                      echo"<option value='".$item->id."'>".$item->short_info ."</option>";
                  }
              }else{
                  echo"<option></option>";
              }
              break;
          }
         }

        $flag=='one'? $requests = Requests::find()
            ->where(['id'=>$id,'name_status'=>['ожидание','выполняется','отложена']])
            ->all():null;

        $flag=='all'? $requests = Requests::find()
            ->where(['name_status'=>['ожидание','выполняется','отложена']])
            ->all():null;

        $countRequests=count($requests);
        uploadData($requests,$countRequests,$flag);
    }

    /**
     *  Метод генерирующий страницу просмотра конкретной заявки,
     * возвращает страницу просмотра конкретной заявки.
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     *  Метод генерирующий страницу создания новой заявки,
     * взависимости от роли пользователя(admin,operator,user),
     * при удачном создании заявки возвращает страницу просмотра заявки
     * иначе возвращает страницу создания заявки.
     */
    public function actionCreate()
    {
        /* @var $userModel UserControl  */

        $model = new Requests();
        $userModel = Yii::$app->user->identity;
        $role=$userModel->role;
        $customers = ArrayHelper::map(Customers::find()->all(),'name','name');
        $customer = ArrayHelper::getValue($model,'name_customers');
        $contracts = ArrayHelper::map(Contracts::find()->where(['name_customers'=>$customer,'flag'=>1])->all(),'name','name');
        $contract = ArrayHelper::getValue($model,'name_contracts');
        $performers = ArrayHelper::map(Performers::find()->where(['flag'=>1])->all(),'name','name');
        $performer = ArrayHelper::getValue($model,'name_performers');
        $statuses = ArrayHelper::map(Status::find()->all(),'name','name');
        $status = ArrayHelper::getValue($model,'name_status');

        switch ($role){
            case 'admin': $model->scenario = Requests::SCENARIO_ADMIN;
                break;
            case 'operator': $model->scenario = Requests::SCENARIO_OPERATOR;
                break;
            case 'user': $model->scenario = Requests::SCENARIO_USER;
                break;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'role'=>$role,
                'customers'=>$customers,
                'contracts'=>$contracts,
                'contract'=>$contract,
                'performers'=>$performers,
                'performer'=>$performer,
                'statuses'=>$statuses,
                'status'=>$status,
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

        $model = $this->findModel($id);
        $userModel=Yii::$app->user->identity;
        $role=$userModel->role;
        $page = Yii::$app->request->get('page');
        $statusRequest= Yii::$app->request->get('stateRequest');
//        !($statusPage) ? $statusPage='run':null;
        $customers = ArrayHelper::map(Customers::find()->all(),'name','name');
        $customer = ArrayHelper::getValue($model,'name_customers');
        $contracts = ArrayHelper::map(Contracts::find()->where(['name_customers'=>$customer,'flag'=>1])->all(),'name','name');
        $contract = ArrayHelper::getValue($model,'name_contracts');
        $performers = ArrayHelper::map(Performers::find()->where(['flag'=>1])->all(),'name','name');
        $performer = ArrayHelper::getValue($model,'name_performers');
        $statuses = ArrayHelper::map(Status::find()->all(),'name','name');
        $status = ArrayHelper::getValue($model,'name_status');

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
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'role'=>$role,
                'customer'=>$customer,
                'customers'=>$customers,
                'contracts'=>$contracts,
                'contract'=>$contract,
                'performers'=>$performers,
                'performer'=>$performer,
                'statuses'=>$statuses,
                'status'=>$status,
                ]);
        }
    }

    /**
     *  Метод удаления заявки, возвращает страницу списка заявок
     * находящихся в работе т.е со статусом "ожидание" и "выполняется".
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     *  Метод отправки заявки по email,
     * при удачной отправки возвращает сообщение что сообщение отправлено
     * иначе возвращает страницу с формой отправки заявки.
     */
    public function actionMailer($id)
    {
        $model = new MailerForm();
        $request = $this->findModel($id);

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
     */
    protected function findModel($id)
    {
        if (($model = Requests::findOne($id)) !== null) {
            return $model;
        } else {
//            throw new NotFoundHttpException(Yii::t('yii','The requested page does not exist.'));
            return false;
        }
    }


    public function actionCrops()
    {
        $requests = ArrayHelper::map(Requests::find()->all(),'id','info');
        $id=count($requests);
        $error=0;
        for ($i=1;$i<=$id;$i++){
          if ($this->findModel($i)){
            $model=$this->findModel($i);
            $model->short_info=$model->info ? mb_strimwidth($model->info,0,45,'...'):null;
            $model->save(false);
          }else{
              $error++;
          }
//
//
//
//            print_r($this->findModel($i));
//            echo "<br><br>";
        }
        echo "Завершено c ".$error." ошибками.";

    }

}
