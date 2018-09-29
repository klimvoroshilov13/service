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
/* @var $status string */
/* @var $statuses array */
/* @var $request string */
/* @var $requests  array  */

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
            'autoclose'=> true,
            'todayHighlight' => true,
            'weekStart'=> 1, //неделя начинается с понедельника
            'startDate' => '01.05.2015', //самая ранняя возможная дата
        ]
    ]) ?>

    <? $paramNj = ['options' =>[$job  => ['Selected' => true]],
        'prompt'=> 'Выберите работы ... '
        ];?>
    <?= $form->field($model,'name_jobs')->dropDownList($jobs,$paramNj)?>

    <?= $form->field($model, 'name_customers')->dropDownList($customers,[
        'options' =>[$customer => ['Selected' => true]],
        'prompt'=>'Выберите контрагента ...',
//        'onchange'=>
//            '$.post( "'.Yii::$app->urlManager->createUrl('requests/lists?id=').'"+$(this).val(),
//                function( data ) {$("select#planner-info_request").html(data);});',
//            '$.post( "'.Yii::$app->urlManager->createUrl('contracts/lists?id=').'"+$(this).val(),
//                function( data ){$("select#planner-info_contract").html(data);});',
    ]) ?>


    <?=  $form->field($model, 'info_contract')->dropDownList($model->isNewRecord ?[]:$contracts,[
        'options' =>[$contract  => ['Selected' => true]],
        'prompt'=> $model->isNewRecord ? 'Выберите договор ...':null,
        ]);?>

    <?= $form->field($model,'info_request')->label(false)->dropDownList($requests, [
        'options' =>[$request  => ['Selected' => true]],
        'prompt'=> $model->isNewRecord ? 'Выберите заявку ...':null,
    ])?>

    <?= $form->field($model, 'info_text')->label(false)->textarea(['rows' => 3, 'cols' => 5]);?>


    <? $paramNp = ['options' =>[$performer1 => ['Selected' => true]],
                'prompt'=>'Выберите исполнителя ...'
        ];?>
    <?= $form->field($model,'name_performers1')->dropDownList($performers,$paramNp)?>


    <? $paramNp = ['options' =>[$performer2 => ['Selected' => true]],
                'prompt'=>'Выберите исполнителя ...'
        ];?>

    <?= $form->field($model,'name_performers2')->checkbox(['label'=>'Второй исполнитель'])?>

    <?= $form->field($model,'name_performers2')->label(false)->dropDownList($performers,$paramNp)?>

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

<!-- Подключение JS скриптов -->
<?php
$this->registerJsFile('@web/js/reload-info.js',['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
]]);

$this->registerJsFile('@web/js/hide-elements.js',['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
]]);

$this->registerJsFile('@web/js/load-data.js',['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
]]);
?>
<!-- Подключение JS скриптов -->
