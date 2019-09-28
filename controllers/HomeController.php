<?php
/**
 * User: Kazakov_N
 * Date: 12.01.2019
 * Time: 10:31
 */

namespace app\controllers;


use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\LoginForm;
use app\models\Apps;

class HomeController extends Controller
{
    public $layout = 'app';

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

                        ],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                        'actions' => [
                            'logout',
                            'index',

                        ],
                        'allow' => true,
                        'roles' => ['operator'],
                    ],
                    [
                        'actions' => [
                            'logout',
                            'index',
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Осуществляет вход в web-приложение
     *
     * @return \yii\web\Response|string  возвращает главную страницу web-приложения
     * в случае авторизации и страницу входа
     * если пользователь не авторизован.
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
     * Осуществляет выход из web-приложения
     *
     * @return \yii\web\Response  возвращает страницу входа
     * в web-приложение при выходе.
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $apps = Apps::findAllApps('home');
        return $this->render('index',
            [
                'apps'=>$apps
            ]
        );
    }
}