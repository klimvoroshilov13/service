<?php

namespace app\controllers;

use Yii;
use app\models\Contracts;
use app\models\ContractsSearch;
use app\models\Customers;
use app\modules\admin\models\UserControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * ContractsController implements the CRUD actions for Contracts model.
 */
class ContractsController extends Controller
{
    /**
     *  Унаследованное свойство класса Component,
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
                        'actions' => ['logout','index','view','update','lists'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                        'actions' => ['logout','index','view','update','create','delete','lists'],
                        'allow' => true,
                        'roles' => ['operator'],
                    ],
                    [
                        'actions' => ['logout','index','view','update','create','delete','lists','full'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Contracts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContractsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Contracts models.
     * @return mixed
     */
    public function actionLists($id)
    {
        $customer = Customers::findOne($id);
        $contracts = Contracts::find()
            ->where(['name_customers'=>$customer->name,'flag'=>1])
            ->all();

        $countContracts = count($contracts);

        if ($countContracts){
            foreach ($contracts as $contract){
                echo"<option value='".$contract->name. " '>".$contract->full_name. "</option>";
            }
        }
        echo"<option value='0'>Без договора</option>";

    }

    /**
     * Displays a single Contracts model.
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
     * Creates a new Contracts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /* @var $userModel UserControl  */

        $userModel = Yii::$app->user->identity;
        $role=$userModel->role;
        $model = new Contracts();
        $searchModel = new ContractsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
                'role'=>$role
            ]);
        }
    }

    /**
     * Updates an existing Contracts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /* @var $userModel UserControl  */

        $userModel = Yii::$app->user->identity;
        $role = $userModel->role;
        $model = $this->findModel($id);
        //$searchModel = new ContractsSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
                'role'=>$role
            ]);
        }
    }

    /**
     * Deletes an existing Contracts model.
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
     * Finds the Contracts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contracts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contracts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionFull()
    {
        $contracts = ArrayHelper::map(Contracts::find()->all(),'id','name');
        $id = count($contracts);
        for ($i=1;$i<$id;$i++){
            $model=$this->findModel($i);
            $model->full_name=$model->name. ($model->note ? ' ('.$model->note.')':null);
            $this->findModel($i)->save();
        }

    echo "Завершено";

    }

}
