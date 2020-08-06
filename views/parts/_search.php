<?php
/**
 * Created by Nikolay N. Kazakov
 * File: _search.php
 * Date: 24.09.2019
 * Time: 15:12
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PartsItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date_create') ?>

    <?= $form->field($model, 'name_customers') ?>

    <?= $form->field($model, 'name_parts') ?>

    <?= $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('yii', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('yii', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
