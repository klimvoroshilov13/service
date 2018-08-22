<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PlannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('yii', 'Planners');
?>

<?php Pjax::begin(); ?>

<div class="planner-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('yii', 'Запланировать'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
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
                'filter' => DatePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'date',
                    'language' => 'ru',
                    'value' => date('Y-m-d'),
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'format' => 'yyyy.MM.dd',
                        'autoclose'=> true,
                        'todayHighlight' => true,
                        'weekStart'=> 1, //неделя начинается с понедельника
                        'startDate' => '01.05.2015', //самая ранняя возможная дата
                    ]
                ]),
            ],
            /* End Комбинированные ячейки  date и day_week */

            /* Start Комбинированные ячейки name_jobs,name_customers,info */
            [
                'class' => 'app\components\grid\CombinedDataColumn',
                'attributes' => [
                    'name_jobs:html',
                    'name_customers:html',
                    'info:html',
                ],
                'labelTemplate' => '{2}',
                'valueTemplate' => '{0}  -  {1} {2}',
                'labels' => [
                    'Jobs',
                    'Customer',
                    'Сведения',
                ],
                'values' => [
                    null,
                    null,
                    function ($model, $_key, $_index, $_column) {
                        return $model->info == '' ? null:' ( ' . $model->info . ' ) ' ;
                        },
                ],
                'sortLinksOptions' => [
                    ['class' => 'text-nowrap'],
                    null,

                ],
                'options' => ['width' => '350'],

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

            'name_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?
echo "PHP: " . PHP_VERSION . "\n";
echo "ICU: " . INTL_ICU_VERSION . "\n";
?>

<?php Pjax::end(); ?>

</div>
