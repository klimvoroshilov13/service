<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CustomersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('yii', 'Customers');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('yii','Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id'=>[
                'attribute' => 'id',
                'options' => ['width' => '10']
            ],
            'name'=>[
                'attribute' => 'name',
                'options' => ['width' => '150']
            ],
            'full_name'=>[
                'attribute' => 'full_name',
                'options' => ['width' => '200']
            ],
            'legal_address'=>[
                'attribute' => 'legal_address',
                'options' => ['width' => '300']
            ],
            'mailing_address'=>[
                'attribute' => 'mailing_address',
                'options' => ['width' => '300']
            ],
            'inn'=>[
                'attribute' => 'inn',
                'options' => ['width' => '100']
            ],
            'kpp'=>[
                'attribute' => 'kpp',
                'options' => ['width' => '100']
            ],
            ['class' => 'yii\grid\ActionColumn',
                'header'=>'Изм.',
                'headerOptions' => ['width' => '80'],
                'template' => '{update} {delete}',],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
