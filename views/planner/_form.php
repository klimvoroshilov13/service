<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Planner */
/* @var $form yii\widgets\ActiveForm */
/* @var $role string  */
/* @var $jobs array  */
/* @var $job string  */
/* @var $customers array  */
/* @var $customer string  */
/* @var $contracts array  */
/* @var $contract string  */
/* @var $performers array */
/* @var $performer1 string */
/* @var $performer2 string */
/* @var $statuses array */
/* @var $status string */

?>

<div class="planner-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
        'language' => 'ru',
        'name' => 'date',
        'value' => date('d.m.Y'),
        'options' => ['placeholder' => Yii::t('yii','Select date ...')],
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'dd.MM.yyyy',
            'autoclose'=>true,
            'todayHighlight' => true,
            'weekStart'=> 1, //неделя начинается с понедельника
            'startDate' => '01.05.2015', //самая ранняя возможная дата
        ]
    ]) ?>


<!--    --><?//= $form->field($model, 'day_week')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'name_jobs')->textInput(['maxlength' => true]) ?>


    <? $paramNj = ['options' =>[$job  => ['Selected' => true]],
        'prompt'=> 'Выберите работы ... ',
        'onchange'=>''
        ];?>
    <?= $form->field($model,'name_jobs')->dropDownList($jobs,$paramNj)?>

    <?= $form->field($model, 'name_customers')->dropDownList($customers,[
        'options' =>[$customer => ['Selected' => true]],
        'prompt'=>'Выберите контрагента ...',
    ]) ?>

    <?= $form->field($model, 'info')->textInput() ?>

    <? $paramNp = ['options' =>[$performer1 => ['Selected' => true]],
                'prompt'=>'Выберите исполнителя ...'
        ];?>
    <?= $form->field($model,'name_performers1')->dropDownList($performers,$paramNp)?>


    <? $paramNp = ['options' =>[$performer2 => ['Selected' => true]],
                'prompt'=>'Выберите исполнителя ...'
        ];?>
    <?= $form->field($model,'name_performers2')->dropDownList($performers,$paramNp)?>

    <?  $model->isNewRecord ? $paramNs = ['options' =>['ожидание' => ['Selected' => true]]]:
        $paramNs = ['options' =>[$status => ['Selected' => true]],
            'prompt'=>'Выберите статус ...'
        ];?>
    <?= $form->field($model,'name_status')->dropDownList($statuses,$paramNs)?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?='Это статус:'.$model->name_status;?>
    <?='Это работы:'.$job;?>

    <?php ActiveForm::end(); ?>

</div>
