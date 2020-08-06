<?php
use yii\helpers\Html;
use yii\widgets\MaskedInput;
use yii\bootstrap\ActiveForm;
use kartik\datetime\DateTimePicker;
use app\components\helper\Datehelper;
use app\components\helper\Adminhelper;

/* @var $this yii\web\View */
/* @var $model app\models\Requests */
/* @var $modelRequestArray array*/
/* @var $role string  */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="requests-form">

    <?php $form = ActiveForm::begin(); ?>

        <?php
            $model->isNewRecord ? $model->date_start = Datehelper::setCurrentDate():null;
            $model->date_start = Yii::$app->formatter->asDatetime($model->date_start, "php:d.m.Y H:i")
        ?>

        <!-- Field date_start -->
        <?=
            !($role=='user')?
                $form
                    ->field($model,'date_start')
                    ->widget(DateTimePicker::classname(), [
                       'language' => 'ru',
                       'name' => 'date_start',
                       'value' => date('d.m.Y h:i'),
                       'options' => ['placeholder' => Yii::t('yii','Select date and time ...')],
                       'convertFormat' => true,
                       'pluginOptions' => [
                           'format' => 'dd.MM.yyyy hh:i',
                           'autoclose'=>true,
                           'todayHighlight' => true,
                           'weekStart'=> 1,
                           'startDate' => '01.05.2015 00:00',
                       ]
                    ])
            : null;
        ?>

        <?php
            $model->isNewRecord || $model->date_end==null ?
                null:$model->date_end = Yii::$app->formatter->asDatetime($model->date_end, "php:d.m.Y H:i")
        ?>

        <!-- Field date_end -->
        <?=
            !($role=='operator') ?
                $form
                    ->field($model,'date_end')
                    ->widget(DateTimePicker::classname(), [
                        'language' => 'ru',
                        'name' => 'date_end',
                        'value' => date('d.m.Y h:i'),
                        'options' => ['placeholder' => Yii::t('yii','Select date and time ...')],
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'format' => 'dd.MM.yyyy hh:i',
                            'autoclose'=>true,
                            'todayHighlight' => true,
                            'weekStart'=> 1,
                            'startDate' => $model->date_start,
                            ]
                        ])
            : null;
        ?>

        <!-- Field  name_customers -->
        <?=
            $form
                ->field($model, 'name_customers')
                ->dropDownList($modelRequestArray['customers'],[
                    'options' =>[$modelRequestArray['customer'] => ['Selected' => true]],
                    'prompt'=>'Выберите контрагента ...',
                    'onchange'=>'$.post( "'.Yii::$app->urlManager->createUrl('contracts/lists?id=').'"+$(this).val(), 
                        function(data){$("select#requests-name_contracts").html(data);});'
                ])
        ?>

        <?=
            !($role=='user')&& $model->isNewRecord ?
                Html::a(Yii::t('yii', 'New'), ['/customers/create/requests'], ['class' => 'btn btn-primary']):null;
        ?>

        <?php
        $role=='admin' ? Adminhelper::printArr($modelRequestArray['contracts'],$modelRequestArray['contract']): null;
        ?>

        <!-- Field name_contracts -->
        <?=
            !($role=='user')?
                $form
                    ->field($model, 'name_contracts')
                    ->dropDownList($modelRequestArray['contracts'],[
                        'options' =>[$modelRequestArray['contract']  => ['Selected' => true]],
                        'prompt'=>'Выберите договор ...',
                    ])
            :null;
        ?>

        <!-- Field info -->
        <?=
            $form
                ->field($model, 'info')
                ->textarea(['rows' => 3, 'cols' => 5]);
         ?>

        <?=
            !($role=='operator') && !($model->isNewRecord) ?
                $form
                    ->field($model, 'comment')
                    ->textarea(['rows' => 3, 'cols' => 5]):null;
        ?>

        <label class="control-label"><?= 'Контакты' ?></label>

        <!-- phone -->
        <?=
            $form
                ->field($model, 'phone')
                ->label(false)->widget(MaskedInput::className(), ['mask' => '+7(999)999-99-99',])
                ->textInput(['placeholder' => $model->getAttributeLabel(Yii::t('yii', 'Phone'))]);
        ?>

        <!-- contacts -->
        <?=
            $form
                ->field($model, 'contacts')
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel(Yii::t('yii', 'Contact person'))]);
         ?>

        <!-- name_performers -->
        <?php
            $modelRequestArray['performer1'] == null ? $modelRequestArray['performer1'] = 0:null;
            $model->isNewRecord ?
                $paramNp = ['options' =>[],'prompt'=>'Выберите исполнителя ...']:
                $paramNp = ['options' =>[$modelRequestArray['performer1'] => ['Selected' => true]],'prompt'=>'Выберите исполнителя ...'];
        ?>

        <?= !($role=='operator') ? $form->field($model,'name_performers')->dropDownList($modelRequestArray['performers'],$paramNp): null ?>

        <!-- name_status -->
        <?php
            $model->isNewRecord ?
                $paramNs = ['options' =>['ожидание' => ['Selected' => true]]]:
                $paramNs = ['options' =>[$modelRequestArray['status'] => ['Selected' => true]]];
         ?>

        <?=
            !($role=='operator') ?
                $form
                    ->field($model,'name_status')
                    ->dropDownList($modelRequestArray['statuses'],$paramNs):null;
        ?>

        <div class="form-group">
            <?=
                Html::submitButton($model->isNewRecord ?
                    Yii::t('yii', 'Create')
                   :Yii::t('yii', 'Update'),[
                            'class' => $model->isNewRecord ?
                                'btn btn-success'
                               :'btn btn-primary'
                    ]
                )
            ?>
        </div>

    <?php ActiveForm::end();?>

    <?= $role=='admin' ? $model->getOldAttribute('name_status'):null ?> <br>

    <?= $role=='admin' ? var_dump($model):null ?>

</div>
