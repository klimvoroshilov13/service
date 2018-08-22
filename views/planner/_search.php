<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PlannerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="planner-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'name_jobs') ?>

    <?= $form->field($model, 'name_customers') ?>

    <?= $form->field($model, 'info') ?>

    <?php // echo $form->field($model, 'name_status') ?>

    <?php // echo $form->field($model, 'name_performers') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
