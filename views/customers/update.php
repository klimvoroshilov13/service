<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Customers */

$this->title = Yii::t('yii', 'Update Customer').': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Customers'), 'url' => ['index']];
?>
<div class="customers-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
