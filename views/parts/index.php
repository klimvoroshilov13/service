<?php
/**
 * Created by Nikolay N. Kazakov
 * File: index.php
 * Date: 24.09.2019
 * Time: 14:43
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PartsItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('yii', 'PartsItem');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('yii', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'part_request'=>[
                'attribute' => 'part_request',
                'label'=>'Запрос №',
                'options'=>['width' => '10'],
            ],
            'partRequest.date_creation'=>[
                'attribute' => 'partRequest.date_creation',
                'label'=>'Дата',
                'format' => ['date','php:d.m.Y'],
                'options'=>['width' => '100'],
            ],
            'partRequest.customer'=>[
                'attribute' => 'partRequest.customer',
                'label'=>'Контрагент',
                'options'=>['width' => '100'],
            ],
            'partname'=>[
                 'attribute' => 'partname',
                 'label'=>'Наименование ЗИП',
            ],
            'number'=>[
                'attribute' => 'number',
                'options'=>['width' => '10'],
            ],
            'measure'=>[
                'attribute' => 'measure',
                'label'=>'Ед.изм.',
                'options'=>['width' => '10'],
            ],
            'supplier'=>[
                'attribute' => 'supplier',
                'label'=>'Поставщик',
                'options'=>['width' => '100'],
            ],
            'invoice'=>[
                'attribute' => 'invoice',
                'label'=>'Счет',
                'options'=>['width' => '100'],
            ],
            'status'=>[
                'attribute' => 'status',
                'options'=>['width' => '100'],
            ],

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'urlCreator'=>function($action, $model, $key, $index){
                    return Url::to([$action,'id'=>$model->part_request]);
                },
                'template' => '{view} {update} {delete}',
                'visibleButtons' => [
                    'view' => true,
                    'update' => true,
                    'delete' => true,
                 ],
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $iconName = 'pencil';
                        $id = $key;
                        $options = [
                            'title' => $title,
                            'aria-label' => $title,
                            'data-pjax' => '0',
                            'id' => $id
                        ];
                        $title = \Yii::t('yii','Обновить');
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                        $url = Url::to(['update','id' =>$model->id]);
                        return Html::a($icon, $url, $options);
                    },
                    'delete' => function ($url, $model, $key) {
                        $iconName = 'trash';
                        $id = $key;
                        $options = [
                            'title' => $title,
                            'aria-label' => $title,
                            'data-pjax' => '0',
                            'id' => $id
                        ];
                        $title = \Yii::t('yii','Удалить');
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                        $url = Url::to(['delete','id' =>$model->id]);
                        return Html::a($icon, $url, $options);
                    },
                ],

            ],

        ],
    ]); ?>
</div>

