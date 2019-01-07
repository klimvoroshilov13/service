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
/** @var $arrMonth array */
/** @var $month string*/

$arrMonth = array(
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

$arrYear = array(
    '18'=>'2018',
    '19'=>'2019'
);

?>

<!--<div class="filter-month-form">-->
    <?php $form = ActiveForm::begin([]); ?>
        <?= $form
        ->field($modelFilter,'month')
        ->label(false)
        ->dropDownList($arrMonth,[
                'options'=>[
                    $month =>['Selected'=>true]
                ]
         ]
            );
        ?>

    <?= $form
        ->field($modelFilter,'year')
        ->label(false)
        ->dropDownList($arrYear,[
                'options'=>[
                    $month =>['Selected'=>true]
                ]
            ]
        );
    ?>
        <?= Html::submitButton( Yii::t('yii', 'Применить') , ['class' => 'btn btn-success btn-filter']) ?>
    <?php $form = ActiveForm::end(); ?>
<!--</div>-->