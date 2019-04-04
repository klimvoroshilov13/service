<?php
/**
 * User: Kazakov_N
 * Date: 15.12.2018
 * Time: 8:53
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var app\models\PlannerFilter $modelFilter */
/** @var array $arrMonth */
/** @var array $arrYear */
/** @var string $month */

$arrMonth = [
    '01'=>'Январь',
    '02'=>'Февраль',
    '03'=>'Март',
    '04'=>'Апрель',
    '05'=>'Май',
    '06'=>'Июнь',
    '07'=>'Июль',
    '08'=>'Август',
    '09'=>'Сентябрь',
    '10'=>'Октябрь',
    '11'=>'Ноябрь',
    '12'=>'Декабрь'
];

for ($i=0;$i<=3;$i++){
  $year= mktime(0, 0, 0,date('m'),date('d'), date('Y')+($i-2));
  $arrYear[date('y',$year)] = date('Y',$year);
}

?>

<?php $form = ActiveForm::begin([]); ?>

    <!--month-->
    <?= $form
    ->field($modelFilter,'month')
    ->label(false)
    ->dropDownList($arrMonth,[
            'options'=>[
                $modelFilter->month =>['Selected'=>true]
            ]
     ]
        );
    ?>

    <!--year-->
    <?= $form
        ->field($modelFilter,'year')
        ->label(false)
        ->dropDownList($arrYear,[
                'options'=>[
                    $modelFilter->year =>['Selected'=>true]
                ]
            ]
        );
    ?>
    <?= Html::submitButton( Yii::t('yii', 'Применить') , ['class' => 'btn btn-success btn-filter']) ?>
<?php $form = ActiveForm::end(); ?>