<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**  @var $this yii\web\View */
/**  @var $searchModel app\models\PlannerSearch */
/**  @var $dataProvider yii\data\ActiveDataProvider */
/**  @var $stateRequest string */
/**  @var $Result boolean */
/** @var $month string */
/** @var $modelFilter app\models\PlannerFilter */


$this->title = Yii::t('yii', 'Planners');

?>

<?php Pjax::begin(['timeout'=>'5000']); ?>

    <div class="planner-index">

        <h1><?= Html::encode($this->title) ?></h1>

            <p>
        <!--        --><?php //require  ('modal-create.php');?><!--    -->
            </p>

            <p>
                <?= Html::a(Yii::t('yii', 'Создать'),
                    ['create'], [
                            'class' => 'btn-planner btn-create btn-success'
                    ]) ?>
            </p>

            <p>
                <?= Html::a('refreshButton',$stateRequest == 'curdate'? ['/planner/index/curdate']:null, ['class' => 'hidden','id' => 'refreshButton']) ?>
            </p>

            <p>
                <?php require  ('btn-create.php');?>
                <?php $stateRequest=='all'? require  ('filter-form.php'):null;?>
            </p>

        <?php
            require  ('columns-planner.php');
            try {
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => $columnsPlanner,
                ]);
            } catch (Exception $e) {
                return \yii\helpers\Url::to(['/requests/error','message'=>$e->getMessage()]);
            }
        ?>

        <?php

            if (Yii::$app->request->get('Result')) {
                echo "Скопировано успешно";
            } else {
                echo "Не скопировано";
            }

         ?>

    </div>

    <?php require  ('modal-copy.php');?><!--    -->

<?php Pjax::end(); ?>

<?php

//$this->registerJsFile('@web/js/refresh-page.js',['depends' => [
//    'yii\web\YiiAsset',
//    'yii\bootstrap\BootstrapAsset',
//]]);

$this->registerJsFile('@web/js/planner/index/redye-planners.js',['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
]]);

$this->registerJsFile('@web/js/reload-info.js',['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
]]);

//$this->registerJsFile('@web/js/planner/form/behavior-fields.js',['depends' => [
//    'yii\web\YiiAsset',
//    'yii\bootstrap\BootstrapAsset',
//]]);

//$this->registerJsFile('@web/js/planner/form/load-data.js',['depends' => [
//    'yii\web\YiiAsset',
//    'yii\bootstrap\BootstrapAsset',
//]]);

?>
<!-- Подключение JS скриптов -->

<!-- Диагностика -->
<?php

/* @var $userModel app\models\User */

$userModel = Yii::$app->user->identity;

If ($userModel->username == 'Admin') {?>
    <?="PHP: " . PHP_VERSION . "\n";?>
    <?="ICU: " . INTL_ICU_VERSION . "\n";?>
    <br>
    <?php echo "Месяц:".$modelFilter->month;?>
<?}?>
<!-- Диагностика -->