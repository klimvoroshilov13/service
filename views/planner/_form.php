<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\components\helper\Datehelper;
use app\components\helper\Adminhelper;

/* @var $this yii\web\View */
/* @var $model app\models\Planner */
/* @var $form yii\widgets\ActiveForm */
/* @var $role string  */
/* @var $modelPlannerArray  array */

?>

<div class="planner-form">


    <?php $form = ActiveForm::begin(); ?>

        <!-- date -->
        <?php $model->isNewRecord ? $model->date = Datehelper::setCurrentDate(): null;?>
        <?php $model->date=Yii::$app->formatter->asDatetime($model->date, "php:d.m.Y") ?>
        <?= $form->field($model, 'date')
            -> widget(DatePicker::classname(), [
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

        <!-- name_jobs -->
        <? $paramNj = ['options' =>[$modelPlannerArray['job']  => ['Selected' => true]],
            'prompt'=> 'Выберите работы ... '
            ];?>
        <?= $form->field($model,'name_jobs')->dropDownList($modelPlannerArray['jobs'],$paramNj)?>

        <!-- name_customers -->
        <?= $form->field($model, 'customer_id')
            ->dropDownList($modelPlannerArray['customers'],[
                    'options' =>[
                            $modelPlannerArray['customer'] => ['Selected' => true ]
                    ],
            'prompt'=> $model->isNewRecord ? 'Выберите контрагента...':null,
        ])?>

        <!--  info_request  -->
        <?=  $form->field($model, 'info_contract')
            ->dropDownList($model->isNewRecord ?[]:$modelPlannerArray['contracts'],[
            'options' =>[
                $modelPlannerArray['contract'] => ['Selected' => true]
            ],
            'prompt'=> $model->isNewRecord ? 'Выберите договор ...':null,
            ]);?>

        <!--  info_request  -->
        <?= $form->field($model,'info_request')
            ->label(false)
            ->dropDownList($model->isNewRecord ? $modelPlannerArray['requests']:$modelPlannerArray['requestsAll'], [
            'options' =>[$modelPlannerArray['request'] => ['Selected' => true]],
            'prompt'=> $model->isNewRecord ? 'Выберите заявку ...':null,
        ])?>

        <!--  info_text  -->
        <?= $form->field($model, 'info_text')->label(false)->textarea(['rows' => 3, 'cols' => 5]);?>

        <!--  name_performers1  -->
        <? $paramNp = ['options' =>[$modelPlannerArray['performer1'] => ['Selected' => true]],
                    'prompt'=>'Выберите исполнителя ...'
            ];?>
        <?= $form->field($model,'name_performers1')->dropDownList($modelPlannerArray['performers'],$paramNp)?>

        <!--  name_performers2  -->
        <? $paramNp = ['options' =>[$modelPlannerArray['performer2'] => ['Selected' => true]],
                    'prompt'=>'Выберите исполнителя ...'
            ];?>
        <?= $form->field($model,'name_performers2')->checkbox(['label'=>'Второй исполнитель'])?>
        <?= $form->field($model,'name_performers2')->label(false)->dropDownList($modelPlannerArray['performers'],$paramNp)?>

        <!--  name_status  -->
        <?php  $model->isNewRecord ? $paramNs = ['options' =>['ожидание' => ['Selected' => true]]]:
            $paramNs = ['options' =>[$modelPlannerArray['status'] => ['Selected' => true]],
                'prompt'=>'Выберите статус ...'
            ];?>
        <?= $form->field($model,'name_status')->dropDownList($modelPlannerArray['statuses'],$paramNs)?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('yii', 'Create') : Yii::t('yii', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>

<!-- Connect js_scripts -->
<?php
$this->registerJsFile('@web/js/reload-info.js',['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
]]);

$this->registerJsFile('@web/js/planner/form/behavior-fields.js',['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
]]);

$this->registerJsFile('@web/js/planner/form/load-data.js',['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
]]);
?>

<!-- Diagnostic -->
<?php If (Yii::$app->user->identity->username=='Admin') {?>
<?='Это статус: '.$model->name_status;?><br>
<?='Это работы: '.$job;?><br>
<?='Это котрагент: '.$customer;?><br>
<?='Это контракт: '.$contract;?><br>
<?//= Adminhelper::printArr($modelPlannerArray);?>
<?}?>



