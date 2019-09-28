<?php
/**
 * Created by Nikolay N. Kazakov
 * File: index.php
 * Date: 24.09.2019
 * Time: 14:43
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PartsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('yii', 'Parts');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('yii', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'id'=>[
                'attribute' => 'id',
                'options'=>['width' => '20'],
            ],
            'date_create'=>[
                'attribute' => 'date_create',
                'options'=>['width' => '150'],
            ],
            'name_customers'=>[
                'attribute' => 'name_customers',
                'options'=>['width' => '150'],
            ],
            'name_parts:ntext',
            'status'=>[
                'attribute' => 'name_status',
                'options'=>['width' => '150'],
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

