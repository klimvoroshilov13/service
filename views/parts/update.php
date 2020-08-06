<?php
/**
 * Created by Nikolay N. Kazakov
 * File: update.php
 * Date: 24.09.2019
 * Time: 15:14
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PartsItem */

$this->title = Yii::t('yii', 'Update {modelClass}: ', [
        'modelClass' => 'PartsItem',
    ]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'PartsItem'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Update');
?>
<div class="parts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'partsRequest' => $partsRequest,
        'partsItem' => (empty($partsItem)) ? [new PartsItem()] : $partsItem,
        'modelPlannerArray'=>$modelPlannerArray
    ]) ?>
</div>