<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PlannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $stateRequest string */

$this->title = Yii::t('yii', 'Planners');
?>

<?php Pjax::begin(); ?>

<?php
 try {
     $datePicker = DatePicker::widget([
         'model' => $searchModel,
         'attribute' => 'date',
         'language' => 'ru',
         'value' => date('d-m-Y'),
         'convertFormat' => true,
         'pluginOptions' => [
             'format' => 'dd.MM.yyyy',
             'autoclose' => true,
             'todayHighlight' => true,
             'weekStart' => 1, //неделя начинается с понедельника
             'startDate' => '01.05.2015', //самая ранняя возможная дата
         ]
     ]);
 } catch (Exception $e) {
      return \yii\helpers\Url::to(['/requests/error','message'=>$e->getMessage()]);
 }
 ?>

<?php  $columnsPlanner = [
    /* Start Комбинированные ячейки  date и day_week*/

    [
        'class' => 'app\components\grid\CombinedDataColumn',
        'attributes' => [
            'date:date',
            'day_week:html',
        ],
        'labelTemplate' => '{0}',
        'valueTemplate' => '{0}  |  {1}',
        'labels' => [
            'Дата',
            '[ Day Week ]',
        ],
        'values' => [
            null,
        ],
        'sortLinksOptions' => [
            ['class' => 'text-nowrap'],
            null,

        ],
        'options' => ['width' => '220'],
        'filter' => $datePicker,
    ],
    /* End Комбинированные ячейки  date и day_week */

    /* Start Комбинированные ячейки name_jobs,name_customers,info */
    [
        'class' => 'app\components\grid\CombinedDataColumn',
        'attributes' => [
            'name_jobs:html',
            'name_customers:html',
            'info_contract:html',
            'info_request:html',
            'info_text:html',
        ],
        'labelTemplate' => '{2}',
        'valueTemplate' => '{0}  -  {1} {2} {3} {4}',
        'labels' => [
            'Jobs',
            'Customer',
            'Сведения',
        ],
        'values' => array(
            null,
            null,
            function ($model, $_key, $_index, $_column) {

                /* @var $model app\models\Planner */

                $model->withoutContract();
                return $model->info_request == '' &&  $model->info_text == '' ? '( ' . $model->info_contract:'( ' . $model->info_contract.' -' ;
            },
            function ($model, $_key, $_index, $_column) {
                return $model->info_request == '' ? null:
                    Html::a(' № ' . $model->info_request, array('requests/view?id='.$model->info_request));

            },
            function ($model, $_key, $_index, $_column) {
                return $model->info_text == '' ? ')':$model->info_text . ')' ;
            },
        ),
        'sortLinksOptions' => [
            ['class' => 'text-nowrap'],
            null,

        ],
        'options' => ['width' => '45%'],
    ],
    /* End Комбинированные ячейки name_jobs,name_customers,info */

    /* Start Комбинированные ячейки name_performers1,name_performers2 */
    [
        'class' => 'app\components\grid\CombinedDataColumn',
        'attributes' => [
            'name_performers1:html',
            'name_performers2:html',

        ],
        'labelTemplate' => '{0}',
        'valueTemplate' => '{0}  {1}',
        'labels' => [
            'Исполнители',
            'Performers',
        ],
        'values' => [
            null,
            function ($model, $_key, $_index, $_column) {
                return $model->name_performers2 == '' ? null:' / ' . $model->name_performers2 ;
            },
        ],

        'sortLinksOptions' => [
            ['class' => 'text-nowrap'],
            null,

        ],
        'options' => ['width' => '200'],

    ],
    /* End Комбинированные ячейки name_performers1,name_performers2 */

    'name_status'=>[
        'attribute'=>'name_status',
        'options'=>['width'=>'170']
    ],

    ['class' => 'yii\grid\ActionColumn',
        'header'=>'Изм.',
        'urlCreator'=>function($action, $model, $key, $index) use ($page,$stateRequest){
            return \yii\helpers\Url::to([$action,'id'=>$model->id,'stateRequest'=>$stateRequest,'page'=>$page]);
        },
        'template' => !($userModel->role=='user') ? '{view} {update} {delete}': '{view} {update} {delete}'

    ],

    ];?>

<div class="planner-index">
    <h1><?= Html::encode($this->title) ?></h1>
<!--    --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
    <?php Modal::begin([

    'header' => '<h2>Hello world</h2>',
    'toggleButton' => [
            'label' => 'click me',
            'class' => 'btn-planner btn-create btn-success',
            ],

    'footer' => 'Низ окна',
    ]);

    echo 'Say hello...';

    Modal::end();?>

    </p>

    <p>
        <?= Html::a(Yii::t('yii', '&nbspСоздать&nbsp'), ['create'], ['class' => 'btn-planner btn-create btn-success']) ?>
    </p>
    <p>
        <?= Html::a(Yii::t('yii', 'Сегодня'), ['planner/index/curdate'],$stateRequest=='curdate'? ['class' => 'btn btn-success btn-option btn-current']:['class' => 'btn btn-success btn-option']) ?>
        <?= Html::a(Yii::t('yii', 'Завтра'), ['planner/index/tomorrow'], $stateRequest=='tomorrow'? ['class' => 'btn btn-success btn-option btn-current']:['class' => 'btn btn-success btn-option']) ?>
        <?= Html::a(Yii::t('yii', 'Вчера'), ['planner/index/yesterday'], $stateRequest=='yesterday'? ['class' => 'btn btn-success btn-option btn-current']:['class' => 'btn btn-success btn-option']) ?>
        <?= Html::a(Yii::t('yii', 'Неделя'), ['planner/index/week'], $stateRequest=='week'? ['class' => 'btn btn-success btn-option btn-current']:['class' => 'btn btn-success btn-option']) ?>
        <?= Html::a(Yii::t('yii', 'Месяц'), ['planner/index/month'], $stateRequest=='month'? ['class' => 'btn btn-success btn-option btn-current']:['class' => 'btn btn-success btn-option']) ?>
        <?= Html::a(Yii::t('yii', '&nbsp&nbsp&nbspГод&nbsp&nbsp&nbsp'), ['planner/index/year'], $stateRequest=='year'? ['class' => 'btn btn-success btn-option btn-current']:['class' => 'btn btn-success btn-option']) ?>
        <?= Html::a(Yii::t('yii', '&nbsp&nbsp&nbspВсе&nbsp&nbsp&nbsp'), ['planner/index/all'], $stateRequest=='all'? ['class' => 'btn btn-success btn-option btn-current']:['class' => 'btn btn-success btn-option']) ?>
    </p>


    <?php try {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $columnsPlanner,
        ]);
    } catch (Exception $e) {
        return \yii\helpers\Url::to(['/requests/error','message'=>$e->getMessage()]);
    } ?>


</div>

<?php Pjax::end(); ?>

<?php

//$this->registerJsFile('@web/js/refresh-page.js',['depends' => [
//    'yii\web\YiiAsset',
//    'yii\bootstrap\BootstrapAsset',
//]]);

$this->registerJsFile('@web/js/planner/index/redye-planners.js',['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
]]);
?>
<!-- Подключение JS скриптов -->

<!-- Диагностика -->
<?php

/* @var $userModel app\models\User */

$userModel = Yii::$app->user->identity;

If ($userModel->username == 'Admin') {?>
    <?="PHP: " . PHP_VERSION . "\n";?>
    <?="ICU: " . INTL_ICU_VERSION . "\n";?>
<?}?>
<!-- Диагностика -->