<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Customers */

$this->title = Yii::t('yii', 'Create Customers');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Customers'), 'url' => ['index']];
?>
<div class="customers-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
