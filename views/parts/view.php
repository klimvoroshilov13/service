<?php
/**
 * Created by Nikolay N. Kazakov
 * File: view.php
 * Date: 24.09.2019
 * Time: 15:15
 */
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $partsRequest app\models\PartsRequest */

$this->title = $partsRequest->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'PartsRequest'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parts-view">

    <h1><?= Html::encode($this->title) ?></h1>

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

    <?= DetailView::widget([
        'model' => $partsRequest,
        'attributes' => [
            'id',
            'date_creation',
            'customer',
        ],
    ]) ?>

</div>