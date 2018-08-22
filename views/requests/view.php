<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Requests */
/* @var $userModel app\models\User  */
$userModel=Yii::$app->user->identity;

$this->title = 'Заявка № '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requests-view">
    <h2><?= Html::encode($this->title) ?></h2>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'date_start'=>[
                'attribute' => 'date_start',
                'format' =>  ['date','php:d.m.Y H:i'],
                //'options' => ['width' => '100']
            ],
            'date_end'=>[
                'attribute' => 'date_end',
                'format' =>  ['date','php:d.m.Y H:i'],
                //'options' => ['width' => '100']
            ],

            'name_customers',
            'name_contracts',
            'info',
            'comment',
            'phone',
            'contacts',
            'name_performers',
            'name_status',
            'name_user',
        ],
    ]) ?>

    <p>
        <?= Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
        <?= !($userModel->role=='user') ? Html::a(Yii::t('yii','Send by email'), ['mailer', 'id' => $model->id], ['class' => 'btn btn-success']): null ?>
        <?= !($userModel->role=='user') ? Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii','Are you sure you want to delete the request').' №'. $model->id. ' ? ',
                'method' => 'post',
            ],
        ]): null ?>
    </p>
</div>
