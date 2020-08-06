<?php
/**
 * Created by Nikolay N. Kazakov
 * File: create.php
 * Date: 24.09.2019
 * Time: 15:13
 */


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $partsItem app\models\PartsItem */
/* @var $partsRequest app\models\PartsRequest */

$this->title = Yii::t('yii', 'Create Parts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Parts'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'partsRequest' => $partsRequest,
        'partsItem' => (empty($partsItem)) ? [new PartsItem()] : $partsItem,
        'modelPlannerArray'=>$modelPlannerArray
    ]) ?>

</div>