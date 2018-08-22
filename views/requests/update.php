<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Requests */
/* @var $role string */
/* @var $customers array */
/* @var $customer string */
/* @var $contracts array */
/* @var $contract string */
/* @var $performers array */
/* @var $performer string */
/* @var $statuses array */
/* @var $status string */

$this->title = Yii::t('yii','Update request').' № '. $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="requests-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'role'=>$role,
        'customer'=>$customer,
        'customers'=>$customers,
        'contracts'=>$contracts,
        'contract'=>$contract,
        'performers'=>$performers,
        'performer'=>$performer,
        'statuses'=>$statuses,
        'status'=>$status,
    ]) ?>

</div>
