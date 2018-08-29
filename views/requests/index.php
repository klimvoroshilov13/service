<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $searchModel app\models\RequestsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $userModel app\models\User */
/* @var $status string */
/* @var $page string */

$userModel=Yii::$app->user->identity;

if ($status=='run'||$status=='') {
    $this->title = 'Заявки(в работе)';}
    else{
        $this->title = 'Заявки(все)';
    }

//$this->params['breadcrumbs'][] = $this->title;
?>


<?php

$this->registerJsFile('@web/js/chcolor.js',['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
    'yii\widgets\PjaxAsset',
    'yii\grid\GridViewAsset'
]]);
?>

<?php Pjax::begin(); ?>

<div class="requests-bar">
    <h3><?= Html::encode($this->title) ?></h3>
    <p>
        <?= !($userModel->role=='user') ? Html::a(Yii::t('yii', 'Create'), ['create'], ['class' => 'btn btn-primary']):null ?>
        <?= Html::a(Yii::t('yii', 'Run'), ['/requests/index/run'], ['class' => 'btn btn-primary control']) ?>
        <?= Html::a(Yii::t('yii', 'All'), ['/requests/index/all'], ['class' => 'btn btn-primary control']) ?>
        <?= Html::a('refreshButton',$status == 'run'? ['/requests/index/run']:null, ['class' => 'control hidden','id' => 'refreshButton']) ?>
    </p>
</div>

<!-- --><?//= 'Статус:'.$status ?>

    <div class="index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        //'emptyCell'=>'-',
        'columns' => [

            'id'=>[
                'attribute' => 'id',
                'options' => ['width' => '10']
            ],

            'date_start'=>[
                'attribute' => 'date_start',
                'options' => ['width' => '150'],
                'format' => ['date','php:d.m.Y H:i'],
            ],

            'name_customers'=>[
                'attribute' => 'name_customers',
                'options' => ['width' => '150'],
                ],
//            'name_contracts'=>[
//                'attribute' => 'name_contracts',
//                'options' => ['width' => '100']
//            ],
            'info'=>[
                'attribute' => 'info',
                'options' => ['width' => '300']
            ],

            /* Start Комбинированные ячейки  phone и contacts */
            [
                'class' => 'app\components\grid\CombinedDataColumn',
                'attributes' => [
                    'phone:html',
                    'contacts:html',
                ],
                'labelTemplate' => '{1}',
                'valueTemplate' => '{0} {1}',
                'labels' => [
                    'Телефон',
                    'Контакты',
                ],
                'values' => [
                    null,
                    null,

                ],
                'sortLinksOptions' => [
                    ['class' => 'text-nowrap'],
                    null,
                ],

                'options' => ['width' => '170'],
            ],
            /* End Комбинированные ячейки  phone и contacts */

            /* Start Комбинированные ячейки  date_run и date_end */
            [
                'class' => 'app\components\grid\CombinedDataColumn',
                'attributes' => [
                    'date_run:datetime',
                    'date_end:html',
                ],
                'labelTemplate' => '{0} / {1}',
                'valueTemplate' => '{0} {1}',
                'labels' => [
                    'Дата нач.',
                    'зав. заявки',
                ],
                'values' => [
                    null,
                        function ($model, $_key, $_index, $_column) {
                        return ' ' . Yii::$app->formatter->asDatetime($model->date_end) . ' ';
                    },
//                  //'date'=>date('php:d.m.Y'),

                ],
                'sortLinksOptions' => [
                    ['class' => 'text-nowrap'],
                    null,
                ],
                //'attribute' => 'date',
                //'value' => 'date',
                //'format' => ['date','php:d.m.Y'],
                'options' => ['width' => '170'],
            ],
            /* End Комбинированные ячейки  date_start и date_end */
            'name_performers',
            'name_status',
            //'name_user',
            ['class' => 'yii\grid\ActionColumn',
                            'header'=>'Изм.',
                            'headerOptions' => ['width' => '70'],
                            'urlCreator'=>function($action, $model, $key, $index)use($page){
                                        return \yii\helpers\Url::to([$action,'id'=>$model->id,'page'=>$page]);
                                        },
                            'template' => !($userModel->role=='user') ? '{view} {update}': '{view} {update}'

               ],
            ],
    ]); ?>
</div>

<?= "* - смотри комментарий."?>

<?php Pjax::end(); ?>