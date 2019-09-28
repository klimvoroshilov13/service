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

/* @var $userModel app\models\User  */
$userModel=Yii::$app->user->identity;

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
    <link rel="icon" href="/web/favicon.ico">
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

    try {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                Yii::$app->user->isGuest ? (
                ['label' => 'Войти', 'url' => ['home/login']]
                ) : (
                    '<li>'
                    . Html::beginForm(['home/logout'], 'post')
                    . Html::submitButton('Выйти (' . $userModel->fullname . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm() .
                    '</li>'
                )
            ],
        ]);
    } catch (Exception $e) {
    }

    if (!Yii::$app->user->isGuest) {
        try {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => Yii::t('yii', 'Requests'),
                     'url' => ['requests/index'],
                     'linkOptions' => ['target' => '_blank']
                    ]
                ],
            ]);
        } catch (Exception $e) {
        }
    }

    if ($userModel->role=='admin' || $userModel->role=='operator' ) {
        try {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => Yii::t('yii', 'Customers'), 'url' => ['customers/index']]
                ],
            ]);
        } catch (Exception $e) {
        }
    }

    if ($userModel->role=='admin') {
        try {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => Yii::t('yii', 'Admin panel'), 'url' => ['admin/admin-panel/index']],
                    ['label' => Yii::t('yii', 'Contracts'), 'url' => ['contracts/index']],
                ],
            ]);
        } catch (Exception $e) {
        }
    }

    if ($userModel->role=='admin'|| $userModel->role=='user') {
        try {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    [
                        'label' => 'Сервис',
                        'items' => [
                            ['label' => Yii::t('yii', 'Parts'), 'url' => ['parts/index']],
                            ['label' => Yii::t('yii', 'Planner'), 'url' => ['planner/index']],
                        ],
                    ],
                ],
            ]);
        } catch (Exception $e) {
        }
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
