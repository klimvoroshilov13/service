<?php

namespace app\controllers;

use Yii;
use app\models\Customers;
use app\models\CustomersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Класс контроллера CustomersController(управление контрагентами)
 */

class CustomersController extends Controller
{
    /**
     * Унаследованное свойство класса Component,
     * устанавливающее шаблон для web приложения
     */
    public $layout='service';

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
                        'actions' => ['logout','index','view','update','create','delete'],
                        'allow' => true,
                        'roles' => ['operator'],
                    ],
                    [
                        'actions' => ['logout','index','view','update','create','delete','lists'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     *  Метод генерирующий страницу списка всех контрагентов,
     * возвращает страницу списка всех контрагентов web приложения.
     */
    public function actionIndex()
    {
        $searchModel = new CustomersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionLists($id=1)
    {
//        if ($flag){
//            $firstParam='id';
////            $select='name_customers';
//        }else{
//            $firstParam='name_customers';
////            $select='short_info';
//        }

//        $countRequests = Customers::find()
//            ->where([$firstParam=>$id,'name_status'=>['ожидание','выполняется','отложена']])
//            ->count();


        $customers = Customers::find()
            ->where(['flag'=>$id])
            ->all();

        $countCustomers=count($customers);

        if ($countCustomers > 0)
        {
                echo"<option value=''>Выберите контрагента ...</option>";
            foreach ($customers as $item){
                echo"<option value='".$item->name. " '>".$item->name. "</option>";
            }
        }
        else {
            echo"<option></option>";
        }

    }

    /**
     *  Метод генерирующий страницу просмотра конкретного контрагента,
     * возвращает страницу просмотра конкретного контрагента.
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     *  Метод генерирующий страницу создания нового контрагента,
     * если контрагент создается из страницы формы создания заявки
     * то метод возвращает страницу с формой создания заявки,
     * если он создается из страницы списка всех контрагентов,
     * то метод возвращает страницу с просмотром созданной заявки,
     * иначе возвращает страницу создания заявки.
     */
    public function actionCreate()
    {
        $model = new Customers();
        $actions= Yii::$app->request->get('actions');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($actions=="requests") return $this->redirect(['requests/create']);
            else return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     *  Метод генерирующий страницу обновления контрагента,
     * при удачном обновлении контрагента возвращает страницу просмотра контрагента,
     * иначе возвращает страницу обновления контрагента.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     *  Метод удаления контрагента,
     * возвращает страницу списка всех контрагентов.
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     *  Метод поиска нужного контрагента,
     * возвращает нужного контрагента из базы данных иначе,
     * исключение с ошибкой.
     */
    protected function findModel($id)
    {
        if (($model = Customers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii','The requested page does not exist.'));
        }
    }
}
