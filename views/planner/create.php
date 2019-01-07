<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Planner */
/* @var $role string */
/* @var $modelPlannerArray  array  */

$this->title = Yii::t('yii', 'Create Planner');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Planners'), 'url' => ['index']];
?>

<div class="planner-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'role'=>$role,
        'modelPlannerArray'=>$modelPlannerArray
    ]) ?>

</div>
