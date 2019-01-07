<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Planner */
/* @var $role string */
/* @var $modelPlannerArray  array  */

$this->title = Yii::t('yii', 'Update Planner of ') . Yii::$app->formatter->asDatetime($model->date, "php:d.m.Y");
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Planners'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = Yii::t('yii', 'Update');
?>
<div class="planner-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'role'=>$role,
        'modelPlannerArray'=>$modelPlannerArray
    ]) ?>

</div>
