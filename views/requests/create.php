<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Requests */
/* @var $role string */
/* @var $customers array */
/* @var $contracts array */
/* @var $contract string */
/* @var $performers array */
/* @var $performer string */
/* @var $statuses array */
/* @var $status string */

$this->title = Yii::t('yii','Create request');
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requests-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'role'=>$role,
        'customers'=>$customers,
        'contracts'=>$contracts,
        'contract'=>$contract,
        'performers'=>$performers,
        'performer'=>$performer,
        'statuses'=>$statuses,
        'status'=>$status,
    ]) ?>

</div>
