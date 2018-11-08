<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Customers;
use app\components\helper\Adminhelper;

/* @var $this yii\web\View */
/* @var $model app\models\Contracts */
/* @var $form yii\widgets\ActiveForm */
/* @var $role string  */

?>

<div class="contracts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

    <?  $valueOneNc = ArrayHelper::getValue($model,'name_customers');
    $valueAllNc = ArrayHelper::map(Customers::find()->where(['flag'=>1])->all(),'name','name')?>

    <?= $form->field($model, 'name_customers')->dropDownList($valueAllNc,[
        'options' =>[$valueOneNc => ['Selected' => true]],
        'prompt'=>'Выберите контрагента ...',
    ]) ?>


    <?  $valueFl = ArrayHelper::getValue($model,'flag');
    $valueAllFl = [
        '0' => 'закрыт',
        '1' => 'выполняется'
    ];?>

    <?= $form->field($model, 'flag')->dropDownList($valueAllFl,[
        'options' =>[$valueFl => ['Selected' => true]],
        'prompt'=>'Выберите статус ...',
    ]) ?>

    <?
    //Диагностика
    if ($role =='admin') {
        Adminhelper::printArr($valueAllFl,$valueFl);
    };
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('yii', 'Create') : Yii::t('yii', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
