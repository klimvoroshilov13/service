<?php
/**
 * User: Kazakov_N
 * Date: 18.11.2018
 * Time: 11:15
 */
use yii\helpers\Html;

/**
 * @var $stateRequest string
 */

$btn=[
    'yesterday'=>'&nbsp&nbspВчера&nbsp',
    'curdate'=>'Сегодня',
    'tomorrow'=>'Завтра',
    'curweek'=>'Тек.(неделя)',
    'nextweek'=>'След.(неделя)',
    'month'=>'Месяц',
    'year'=>'&nbsp&nbsp&nbspГод&nbsp&nbsp&nbsp',
    'all'=>'&nbsp&nbsp&nbspВсе&nbsp&nbsp&nbsp'
];
?>

<?php foreach ($btn as $key => $value){
        echo  Html::a(Yii::t('yii', $value ),
            ['index','stateRequest'=> $key],
            $stateRequest == $key ? ['class' => 'btn btn-success btn-option btn-current']
                :['class' => 'btn btn-success btn-option']
            );
    }
?>

