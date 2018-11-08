<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Planner */

$this->title = Yii::t('yii','Planner of ').Yii::$app->formatter->asDatetime($model->date, "php:d.m.Y");
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Planners'), 'url' => ['index']];
?>
<div class="planner-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'date',
                'format' =>  ['date','php:d.m.Y'],
                //'options' => ['width' => '100']
            ],
            'name_jobs',
            'name_customers',
            [
                'attribute' => 'info_contract',
                'value'=> function($model){
                    return $model->withoutContract().' '
                            .$model->info_request.' '.
                            $model->info_text;
            }
            ],
            [
                'attribute' =>'all_performers',
                'label' => 'Исполнители',
                'value' => $model->getAllPerformers()
            ],
            'name_status',

            'name_user'
        ],
    ]) ?>

    <p>
        <?= Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

</div>
