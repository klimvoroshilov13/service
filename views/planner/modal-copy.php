<?php
/**
 * User: Kazakov_N
 * Date: 12.11.2018
 * Time: 21:14
 */

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\components\helper\Datehelper;

/* @var $modelCopy app\models\PlannerCopy */

Modal::begin([

    'header' => '<h2>Копировать планы на дату</h2>',
    'options' => ['id' => 'myModal'],
    'size' => Modal::SIZE_DEFAULT,
]);

$form = ActiveForm::begin([
        'action'=>'copy'
]);

echo $form->field($modelCopy, 'dateStart')
    ->widget(DatePicker::classname(), [
        'language' => 'ru',
        'name' => 'dateStart',
        'value' => date('Y-m-d'),
        'options' => ['placeholder' => Yii::t('yii','Select date ...')],
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'dd.MM.yyyy',
            'autoclose'=> true,
            'todayHighlight' => true,
            'weekStart'=> 1, //неделя начинается с понедельника
            'startDate' => '01.05.2015', //самая ранняя возможная дата
        ]
    ]);
echo $form->field($modelCopy, 'dateEnd')
    ->widget(DatePicker::classname(), [
        'language' => 'ru',
        'name' => 'dateEnd',
        'value' => date('Y-m-d'),
        'options' => ['placeholder' => Yii::t('yii','Select date ...')],
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'dd.MM.yyyy',
            'autoclose'=> true,
            'todayHighlight' => true,
            'weekStart'=> 1, //неделя начинается с понедельника
            'startDate' => Datehelper::setCurrentDate('d.m.Y'), //самая ранняя возможная дата
        ]
    ]);

echo  Html::submitButton( Yii::t('yii', 'Create'),[
    'class' => 'btn btn-success',
]);

ActiveForm::end();
Modal::end();
