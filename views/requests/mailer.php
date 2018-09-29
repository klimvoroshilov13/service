<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\User;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\MailerForm */
/* @var $request app\models\Requests */

$this->title = Yii::t('yii','Send Requests').' № '. $request->id;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php if (Yii::$app->session->hasFlash('mailerFormSubmitted')) : ?>
    <?php Pjax::end(); ?>

        <div class="alert alert-success">
            <?=Yii::t('yii','Your email has been sent...')?>
            <? Html::tag('meta',['http-equiv'=>'refresh','content'=>'1;URL=index'])?>
        </div>

    <?php else : ?>

        <div class="requests-form">

                <?php $form = ActiveForm::begin(['id' => 'mailer-form']); ?>

                <? $valueAllNe = ArrayHelper::map(User::find()->all(),'email','email');?>
                <?= $form->field($model, 'toEmail')->dropDownList($valueAllNe,['options'=>[1 =>['Selected' => true]]]) ?>

                <? $request->name_customers ? $model->subject = Yii::t('yii','Request').' № '. $request->id .' '.Yii::t('yii','Customer:').' '. $request->name_customers :
                    $model->subject = Yii::t('yii','Request').' № '. $request->id ?>
                <?= $form->field($model, 'subject') ?>

                <? $model->body = Yii::t('yii','Customer:').' '. $request->name_customers."\n".$request->info ."\n"
                    . Yii::t('yii','Contacts:').' ' .$request->phone.', '.$request->contacts."\n".
                    Yii::t('yii','Create:').' ' .Yii::$app->formatter->asDatetime($request->date_start, "php:d.m.Y H:i") ."\n".
                    Yii::t('yii','Operator:').' '.$request->name_user ?>
                <?= $form->field($model, 'body')-> textArea(['rows' => 5, 'cols' => 5])  ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('yii','Send'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
    <?php endif; ?>
</div>