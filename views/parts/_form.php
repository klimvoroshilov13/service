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
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $partItem app\models\PartsItem */
/* @var $partsRequest app\models\PartsRequest */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$js = 'jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
       jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
       jQuery(this).html("Part: " + (index + 1))
       });
       });

       jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
       jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
       jQuery(this).html("Part: " + (index + 1))
       });
       });';

$this->registerJs($js);
?>

<div class="parts-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <!-- date_creation -->
    <?php $partsRequest->isNewRecord ? $partsRequest->date_creation = Datehelper::setCurrentDate(): null;?>
    <?php $partsRequest->date_creation = Yii::$app->formatter->asDatetime($partsRequest->date_creation, "php:d.m.Y") ?>
    <?= $form->field($partsRequest, 'date_creation')
        -> widget(DatePicker::classname(), [
            'language' => 'ru',
            'name' => 'date_creation',
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


    <!-- customer -->
    <?= $form->field($partsRequest, 'customer')
        ->dropDownList($modelPlannerArray[0][0]['customers'],[
            'options' =>[
                $modelPlannerArray[0][0]['customer'] => ['Selected' => true ]
            ],
            'prompt'=> $partsRequest->isNewRecord ? 'Выберите контрагента...':null,
        ])?>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'limit' => 4, // the maximum times, an element can be cloned (default 999)
        'min' => 1, // 0 or 1 (default 1)
        'insertButton' => '.add-item', // css class
        'deleteButton' => '.remove-item', // css class
        'model' => $partsItem[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'partname',
            'number',
            'measure',
            'supplier',
            'invoice',
            'status',
        ],
    ]); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-envelope"></i><?= Yii::t('yii', 'Parts')?>
            <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i>Add part</button>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body container-items"><!-- widgetContainer -->
            <?php foreach ($partsItem as $index => $partItem): ?>
            <div class="item panel panel-default"><!-- widgetBody -->
                <div class="panel-heading">
                    <span class="panel-title-address"> № <?= ($index + 1) ?></span>
                    <button type="button" class="pull-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                    <div class="clearfix"></div>
                </div>

                <div class="parts-item-firstgroup">
                    <!-- partname-->
                    <?php
                    // necessary for update action.
                    if (!$partItem->isNewRecord) {
                        echo Html::activeHiddenInput($partItem, "[{$index}]id");
                    }
                    ?>
                    <!-- partname-->
                    <?= $form->field($partItem, "[{$index}]partname")->textInput(['maxlength' => true,
                        'placeholder'=> $partItem->isNewRecord ? 'Введите наименование зип...':null])
                    ?>

                    <!-- number-->
                    <?= $form->field($partItem, "[{$index}]number")
                        ->input('number',['step'=>'0.01', 'min'=>'0', 'placeholder'=>'0,00','maxlength' => true])
                    ?>

                    <!-- measure -->
                    <?= $form->field($partItem, "[{$index}]measure")
                        ->dropDownList($modelPlannerArray[1][$index]['measures'],[
                            'options' =>[$partItem->isNewRecord ? 'шт.':$modelPlannerArray[1][$index]['measure'] => ['Selected' => true ]],
                    ])?>
                </div>

                <div class="parts-item-secondgroup">
                    <!-- supplier -->
                    <?= $form->field($partItem, "[{$index}]supplier")
                        ->dropDownList($modelPlannerArray[1][$index]['suppliers'],[
                            'options' =>[
                                $modelPlannerArray[1][$index]['supplier'] => ['Selected' => true ]
                            ],
                            'prompt'=> $partItem->isNewRecord ? 'Выберите поставщика...':null,
                    ])?>

                    <!-- invoice -->
                    <?=
                    $form
                        ->field($partItem, "[{$index}]invoice")
                        ->textInput(['placeholder' => $partItem->getAttributeLabel(Yii::t('yii', 'Invoice'))]);
                    ?>

                    <!-- status -->
                    <?php  $partItem->isNewRecord ? $paramNs = ['options' =>['ожидание' => ['Selected' => true]]]:
                        $paramNs = ['options' =>[$modelPlannerArray[1][$index]['statusItem'] => ['Selected' => true]],
                            'prompt'=>'Выберите статус ...'
                        ];?>
                    <?= $form->field($partItem,"[{$index}]status")->dropDownList($modelPlannerArray[1][$index]['statusesItem'],$paramNs)?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
   </div>
<?php DynamicFormWidget::end(); ?>

    <!--  name_performer  -->
<? $paramNp = ['options' =>[$modelPlannerArray[0][0]['performer1'] => ['Selected' => true]],'prompt'=>'Выберите отправителя ...'];?>
<?= $form->field($partsRequest,'name_performer')->dropDownList($modelPlannerArray[0][0]['performers'],$paramNp)?>

     <div class="form-group">
        <?= Html::submitButton($partsRequest->isNewRecord ? Yii::t('yii', 'Create') : Yii::t('yii', 'Update'),
            ['class' => $partsRequest->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
