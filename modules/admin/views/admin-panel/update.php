<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\UserControl */

$this->title = Yii::t('yii', 'Update user: ') . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Users'), 'url' => ['index']];

?>
<div class="user-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>