<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $apps array */


$this->title = 'Сервис';

?>

<div class="service-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        <h2></h2>
            <ul>
                <?php foreach ($apps as $key=>$value) {?>
                <li><?= Html::a($key,[$value.'/index']) ?></li>
                <?php }; ?>
            </ul>
    </div>

</div>