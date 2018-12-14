<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Planner */
/* @var $jobs array  */
/* @var $job string  */
/* @var $role string */
/* @var $customer array */
/* @var $customers array */
/* @var $contract string */
/* @var $contracts array */
/* @var $performers array */
/* @var $performer1 string */
/* @var $performer2 string */
/* @var $status string */
/* @var $statuses array */
/* @var $request string  */
/* @var $requests  array  */

$this->title = Yii::t('yii', 'Update Planner of ') . Yii::$app->formatter->asDatetime($model->date, "php:d.m.Y");
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Planners'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = Yii::t('yii', 'Update');
?>
<div class="planner-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'job' => $job,
        'jobs' => $jobs,
        'role'=>$role,
        'customer'=>$customer,
        'customers'=>$customers,
        'contract'=>$contract,
        'contracts'=>$contracts,
        'performers'=>$performers,
        'performer1'=>$performer1,
        'performer2'=>$performer2,
        'status'=>$status,
        'statuses'=>$statuses,
        'request'=>$request,
        'requests'=>$requests,
    ]) ?>

</div>
