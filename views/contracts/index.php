<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContractsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('yii', 'Contracts');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contracts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('yii', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id'=>[
                'attribute' => 'id',
                'options' => ['width' => '10']],

            /* Start Комбинированные ячейки  name и note */
            [
                'class' => 'app\components\grid\CombinedDataColumn',
                'attributes' => [
                    'name:html',
                    'note:html',
                ],
                'labelTemplate' => '{0}',
                'valueTemplate' => '{0} {1}',
                'labels' => [
                    'Наименование договора',
                    'Примечание',
                ],
                'values' => [
                    null,
                    function ($model, $_key, $_index, $_column) {
                        return $model->note == '' ? null:'(' . $model->note . ')';
                    },

                ],
                'sortLinksOptions' => [
                    ['class' => 'text-nowrap'],
                    null,
                ],

                'options' => ['width' => '450'],
            ],
            /* End Комбинированные ячейки  name и note */

            'name_customers',

            [
                'attribute' => 'flag',
                'value' => function ($data) { return $data->flag == 1 ? 'выполняется' : 'закрыт'; },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'Изм.',
                'headerOptions' => ['width' => '70'],
                'template' => '{update} {delete}'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
