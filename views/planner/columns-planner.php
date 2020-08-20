<?php
/**
 * User: Kazakov_N
 * Date: 08.11.2018
 * Time: 19:41
 */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\components\helper\Datehelper;

/* @var $searchModel app\models\PlannerSearch */
/* @var $stateRequest string */
/* @var $perPage string */
/* @var $modelPlannerArray app\services\DataFromModel */

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
             'weekStart' => 1,
             'startDate' => '01.11.2018',
         ]
     ]);
 } catch (Exception $e) {
      return Url::to(['/requests/error','message'=>$e->getMessage()]);
 }


    $infoFilter = Html::activeDropDownList($searchModel, 'name_jobs',
            ArrayHelper::map(\app\models\Jobs::find()->asArray()->all(), 'name', 'name'),
            ['class'=>'form-control','prompt' => 'Выберите работы...']);


 $columnsPlanner = [

     /* Combined column date,day_week */
    [
        'class' => 'app\components\grid\CombinedDataColumn',
        'attributes' => [
            'date:html',
            'day_week:html',
        ],
        'labelTemplate' => '{0}',
        'valueTemplate' => '{0}  |  {1}',
        'labels' => [
            'Дата',
            '[ Day Week ]',
        ],
        'values' => [
            function ($model, $_key, $_index, $_column) {
                /* @var $model object*/
                $date = Yii::$app->formatter->asDatetime($model->date, "php:d.m.Y");
                return Datehelper::getMonthRus($date);
            },
            null
        ],

        'sortLinksOptions' => [
            ['class' => 'text-nowrap'],
            null,
        ],
        'options' => ['width' => '232'],
        'filter' => $datePicker,
    ],

    /* Combined column name_jobs,name_customers,info */
    [
        'class' => 'app\components\grid\CombinedDataColumn',
        'attributes' => [
            'name_jobs:html',
            'customer_id:html',
            'info_contract:html',
            'info_request:html',
            'info_text:html',
            'name_jobs:html'
        ],
        'labelTemplate' => '{2}',
        'valueTemplate' => '{0}  -  {1} {2} {3} {4}',
        'labels' => [
            'Jobs',
            'Customer',
            'Сведения',
        ],
        'values' => [
            null,
            function ($model, $_key, $_index, $_column) use ($modelPlannerArray) {
                /* @var $model object*/
                return $modelPlannerArray['customers'][$model->customer_id];
            },
            function ($model, $_key, $_index, $_column) {
                /* @var $model object*/
                $model->withoutContract();
                return $model->info_request == '' &&  $model->info_text == '' ? '( ' . $model->info_contract
                    :'( ' . $model->info_contract.' -' ;
            },
            function ($model, $_key, $_index, $_column) {
                return $model->info_request == '' ? null:
                        Html::a(' № ' . $model->info_request, array('requests/view?id='.$model->info_request),[
                            'title'=>$model->info_request,
                            'class'=>'my-tooltip',
                    ]);
                },
            function ($model, $_key, $_index, $_column) {
                return $model->info_text == '' ? ')':$model->info_text . ')' ;
            }
        ],

        'sortLinksOptions' => [
            ['class' => 'text-nowrap'],
            null,
        ],
        'options' => ['width' => '45%'],
//        'filter' => $infoFilter
    ],

    /* Combined column name_performers1,name_performers2 */
    [
        'class' => 'app\components\grid\CombinedDataColumn',
        'attributes' => [
            'name_performers1:html',
            'name_performers2:html',
            'name_performers1:html'
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
        'filter' => Html::activeDropDownList($searchModel, 'name_performers1',
            ArrayHelper::map(\app\models\Performers::find()->asArray()->where(['flag'=>'1'])->all(), 'name', 'name'),
            ['class'=>'form-control','prompt' => 'Выберите исполнителя...']),
    ],

    'name_status'=>[
        'attribute'=>'name_status',
        'options'=>['width'=>'130']
    ],

    ['class' => 'yii\grid\ActionColumn',
        'header'=>'Действия',
        'urlCreator'=>function($action, $model, $key, $index) use ($page,$stateRequest,$perPage){
            return Url::to([$action,'id'=>$model->id,'stateRequest'=>$stateRequest,'page'=>$page,'per-page'=>$perPage]);
        },
        'template' => !($userModel->role=='user') ? '{view} {update} {copy} {delete}':'{view} {update} {copy} {delete}',
        'visibleButtons' => [
            'view' => true,
            'update' => true,
            'copy' => true,
            'delete' => true,
        ],
        'buttons' => [
            'copy' => function ($url, $model, $key) {

                $iconName = 'copy';

                $title = \Yii::t('yii','Копировать на дату');
                $id = 'copy?id='.$key;
                $options = [
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                    'id' => $id
                ];
                $url = Url::current(['id' => $key]);

                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);

                return Html::a($icon, $url, $options);
            },
        ],
    ],
];
