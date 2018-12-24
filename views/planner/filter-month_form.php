<?php
/**
 * User: Kazakov_N
 * Date: 15.12.2018
 * Time: 8:53
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\components\helper\Datehelper;

/** @var $modelFilter app\models\PlannerFilter */
/** @var array $arr */

//$arr= array(
//    'January'=>'Январь',
//    'February'=>'Февраль',
//    'March'=>'Март',
//    'April'=>'Апрель',
//    'May'=>'Май',
//    'June'=>'Июнь',
//    'July'=>'Июль',
//    'August'=>'Август',
//    'September'=>'Сентябрь',
//    'October'=>'Октябрь',
//    'November'=>'Ноябрь',
//    'December'=>'Декабрь'
//);

$arr= array(
    '1'=>'Январь',
    '2'=>'Февраль',
    '3'=>'Март',
    '4'=>'Апрель',
    '5'=>'Май',
    '6'=>'Июнь',
    '7'=>'Июль',
    '8'=>'Август',
    '9'=>'Сентябрь',
    '10'=>'Октябрь',
    '11'=>'Ноябрь',
    '12'=>'Декабрь'
);
?>

<!--<div class="filter-month-form">-->
    <?php $form = ActiveForm::begin(); ?>
        <?= $form
        ->field($modelFilter,'month')
        ->label(false)
        ->dropDownList($arr); ?>
        <?= Html::submitButton( Yii::t('yii', 'Применить') , ['class' => 'btn btn-success']) ?>
    <?php $form = ActiveForm::end(); ?>
<!--</div>-->