<?php

namespace app\modules\admin\controllers;


use Yii;
use app\modules\admin\models\UserSearch;
use app\modules\admin\models\UserControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * Класс контроллера AdminPanelController(Управление пользователями)
 */
class AdminPanelController extends Controller
{
    /**
     *  Унаследованное свойство класса Component,
     * устанавливающее шаблон для web приложения
     */
    public $layout='admin';

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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout','index','view','update','create','delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
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
     *  Метод генерирующий страницу списка всех пользователей,
     * возвращает страницу списка всех пользователей web приложения.
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     *  Метод генерирующий страницу создания нового пользователя,
     * при удачном создании пользователя,
     * возвращает страницу просмотра всех пользователей web приложения,
     * иначе возвращает страницу создания пользователя.
     */
    public function actionCreate()
    {
        $model = new UserControl();
        $model->scenario = UserControl::SCENARIO_CREATE;//Задаем сценарий создания пользователя
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        if ($model->load(Yii::$app->request->post())&& $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     *  Метод генерирующий страницу обновления пользователя,
     * при удачном обновлении пользователя,
     * возвращает страницу просмотра всех пользователей web приложения,
     * иначе возвращает страницу обновления пользователя.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = UserControl::SCENARIO_UPDATE;//Задаем сценарий редактирования пользователя
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     *  Метод удаления контрагента,
     * возвращает страницу списка всех пользователей web приложения.
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $auth = Yii::$app->authManager;
        $auth->revokeAll($id);
        return $this->redirect(['index']);
    }

    /**
     *  Метод поиска нужного пользователя,
     * возвращает нужного пользователя из базы данных иначе,
     * исключение с ошибкой.
     */
    protected function findModel($id)
    {
        if (($model = UserControl::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii','The requested page does not exist.'));
        }
    }


}
