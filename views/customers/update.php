<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Customers */

$this->title = Yii::t('yii', 'Update Customer').': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Customers'), 'url' => ['index']];
?>
<div class="customers-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
