<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Contracts */
/* @var $role string  */

$this->title = Yii::t('yii', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Contracts'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contracts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'role'=>$role
    ]) ?>

</div>
