<?php
/**
 * User: Kazakov_N
 * Date: 08.11.2018
 * Time: 15:13
 */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\components\helper\Datehelper;
use app\components\helper\Adminhelper;
use kartik\date\DatePicker;

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

<?php Modal::begin([
         'header' => '<h2>Запланировать</h2>',
    'toggleButton' => [
            'label' => '&nbspСоздать&nbsp',
            'class' => 'btn-planner btn-create btn-success',
            ]
    ]);?>

    <?php $form = ActiveForm::begin(); ?>
        <?php $model->isNewRecord ? $model->date = Datehelper::setCurrentDate(): null;?>
        <?php try {
            $model->date = Yii::$app->formatter->asDatetime($model->date, "php:d.m.Y");
        } catch (\yii\base\InvalidConfigException $e) {
        } ?>
        <?= $form->field($model, 'date')->
        widget(DatePicker::classname(), [
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

        <?= $form->field($model, 'name_customers')->dropDownList
        ($customers,['options' =>[$customer => ['Selected' => true]],
            'prompt'=> $model->isNewRecord ? 'Выберите контрагента...':null,
        ])?>


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

        <?php  $model->isNewRecord ? $paramNs = ['options' =>['ожидание' => ['Selected' => true]]]:
            $paramNs = ['options' =>[$status => ['Selected' => true]],
                'prompt'=>'Выберите статус ...'
            ];?>
        <?= $form->field($model,'name_status')->dropDownList($statuses,$paramNs)?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('yii', 'Create') : Yii::t('yii', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

<?php Modal::end();?>
