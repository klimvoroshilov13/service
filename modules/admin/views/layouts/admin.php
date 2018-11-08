<?php
/**
 * User: Kazakov_N
 * Date: 04.07.2017
 * Time: 5:59
 */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">

    <!--NavBar Begin-->

    <?php
    NavBar::begin([
        'brandLabel' => 'ЛИГА',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            Yii::$app->user->isGuest ? (
            ['label' => 'Войти', 'url' => ['requests/login']]
            ) : (
                '<li>'
                . Html::beginForm(['admin-panel/logout'], 'post')
                . Html::submitButton('Выйти (' . Yii::$app->user->identity->fullname . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm().
                '</li>'
            )
        ],
    ]);

    if (!Yii::$app->user->isGuest) {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => Yii::t('yii','Users'), 'url' => ['admin-panel/index']],
            ],
        ]);
    }

    NavBar::end();
    ?>

    <!--NavBar End-->

    <div class="container">
        <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
        <?= $content ?>
    </div>

</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; ЛИГА <?= date('Y') ?></p>

<!--  <p class="pull-right">--><?//= Yii::powered() ?><!--</p>-->
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
