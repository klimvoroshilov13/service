<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Contracts */

$this->title = Yii::t('yii', 'Update Contract').': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Contracts'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = Yii::t('yii', 'Update');
?>
<div class="contracts-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
