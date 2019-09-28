<?php
/**
 * Created by Nikolay N. Kazakov
 * File: _form.php
 * Date: 24.09.2019
 * Time: 15:10
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\components\helper\Datehelper;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\Parts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parts-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- date -->
    <?php $model->isNewRecord ? $model->date_create = Datehelper::setCurrentDate(): null;?>
    <?php $model->date_create=Yii::$app->formatter->asDatetime($model->date_create, "php:d.m.Y") ?>
    <?= $form->field($model, 'date_create')
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

    <!-- name_customers -->
    <?= $form->field($model, 'name_customers')
        ->dropDownList($modelPlannerArray['customers'],[
            'options' =>[
                $modelPlannerArray['customer'] => ['Selected' => true ]
            ],
            'prompt'=> $model->isNewRecord ? 'Выберите контрагента...':null,
        ])?>

    <div class="parts-first-group">

    <!-- name_parts-->
        <?= $form->field($model, 'name_parts')->textInput(['maxlength' => true]) ?>

    <!-- number-->
        <?= $form->field($model, 'number')
            ->input('number',['step'=>'0.01', 'min'=>'0', 'placeholder'=>'0,00','maxlength' => true]) ?>

    <!-- name_measure -->
        <?= $form->field($model, 'name_measure')
            ->dropDownList($modelPlannerArray['measures'],[
                'options' =>[
                    $modelPlannerArray['measure'] => ['Selected' => true ]
                ],
                'prompt'=> $model->isNewRecord ? 'Выберите ед.изм...':null,
         ])?>

    </div>

    <!--  name_status  -->
    <?php  $model->isNewRecord ? $paramNs = ['options' =>['ожидание' => ['Selected' => true]]]:
        $paramNs = ['options' =>[$modelPlannerArray['status'] => ['Selected' => true]],
            'prompt'=>'Выберите статус ...'
        ];?>
    <?= $form->field($model,'name_status')->dropDownList($modelPlannerArray['statuses'],$paramNs)?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('yii', 'Create') : Yii::t('yii', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>