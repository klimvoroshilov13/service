<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('yii', 'Users');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h2 align="center"><?=Yii::t('yii', 'Admin panel');?></h2>

    <h3><?= Html::encode($this->title) ?></h3>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'№'],

            //'id',
            'username'=>[
                //'header'=>Yii::t('yii','Name'),
                'attribute' => 'username',
                //'format' =>  ['date','HH:i dd.MM.yyyy'],
                //'options' => ['width' => '70']
            ],
            'fullname',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            'created_at'=>[
                //'header'=>Yii::t('yii','Created_at'),
                'attribute' => 'created_at',
                'format' =>  ['date','php:d.m.Y H:i']
                //'options' => ['width' => '100']
            ],

            'updated_at'=>[
                //'header'=>Yii::t('yii','Updated_at'),
                'attribute' => 'updated_at',
                'format' =>  ['date','php:d.m.Y H:i']
                //'options' => ['width' => '100']
            ],

            'role'=>[
                //'header'=>Yii::t('yii','Role'),
                'attribute' => 'role',
                'options' => ['width' => '100']
            ],

            'status'=>[
                //'header'=>Yii::t('yii','Status'),
                'attribute' => 'status',
                //'format' =>  ['date','HH:i dd.MM.yyyy'],
                'options' => ['width' => '70']
            ],

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'Изм.',
                'headerOptions' => ['width' => '80'],
                'template' => '{update} {delete}',]
        ],
    ]); ?>
    <p>
        <?= Html::a(Yii::t('yii', 'Create user'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
</div>