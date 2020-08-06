<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Requests */
/* @var $role string */
/* @var $modelRequestArray array */


$this->title = Yii::t('yii','Create request');
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requests-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'role'=>$role,
        'modelRequestArray' => $modelRequestArray,
    ]) ?>

</div>
