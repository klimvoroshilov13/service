<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Planner */
/* @var $jobs array  */
/* @var $job string  */
/* @var $role string */
/* @var $customers array */
/* @var $contracts array */
/* @var $contract string */
/* @var $performers array */
/* @var $performer1 string */
/* @var $performer2 string */
/* @var $statuses array */
/* @var $status string */
/* @var $requests  array  */

$this->title = Yii::t('yii', 'Update {modelClass}: ', [
    'modelClass' => 'Planner',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Planners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="planner-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'job' => $job,
        'jobs' => $jobs,
        'role'=>$role,
        'customers'=>$customers,
        'contracts'=>$contracts,
        'contract'=>$contract,
        'performers'=>$performers,
        'performer1'=>$performer1,
        'performer2'=>$performer2,
        'statuses'=>$statuses,
        'stateRequest'=>$status,
        'requests'=>$requests,
    ]) ?>

</div>
