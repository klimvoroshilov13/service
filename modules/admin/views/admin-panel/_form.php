<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\modules\admin\models\AuthItem;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\UserControl */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $model->isNewRecord ?  $form->field($model, 'username')->textInput(['autofocus' => true]): null ?>

    <?= $form->field($model, 'fullname')->textInput(); ?>

    <?= $form->field($model, 'email')->input('email'); ?>

    <?= $form->field($model, 'new_password')->passwordInput() ?>

    <?= $form->field($model, 'new_confirm')->passwordInput() ?>

    <?= $form->field($model, 'role')->dropDownList(ArrayHelper::map(AuthItem::find()->all(),'name','name')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ?  Yii::t('yii', 'Create') : Yii::t('yii', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>