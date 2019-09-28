<?php
/**
 * Created by Nikolay N. Kazakov
 * File: index.php
 * Date: 24.09.2019
 * Time: 14:43
 */

namespace app\models;

use Yii;

/**
 * This is the model class for table "parts".
 *
 * @property integer $id
 * @property string $date_create
 * @property string $name_customers
 * @property string $name_parts
 * @property double $number
 * @property string $name_measure
 * @property string $name_status
 *
 * @property Measure $nameMeasure
 */
class Parts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_create', 'name_customers', 'name_parts', 'number', 'name_measure'], 'required'],
            [['date_create'], 'safe'],
            [['number'], 'number'],
            [['name_customers', 'name_parts'], 'string', 'max' => 128],
            [['name_measure'], 'string', 'max' => 16],
            [['name_status'], 'string', 'max' => 32],
            [['name_measure'], 'exist', 'skipOnError' => true, 'targetClass' => Measure::className(),
                'targetAttribute' => ['name_measure' => 'name_measure']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yii', 'ID'),
            'date_create' => Yii::t('yii', 'Date create'),
            'name_customers' => Yii::t('yii', 'Name Customers'),
            'name_parts' => Yii::t('yii', 'Name Parts'),
            'number' => Yii::t('yii', 'Number'),
            'name_measure' => Yii::t('yii', 'Name Measure'),
            'name_status' => Yii::t('yii', 'Status'),
            'name_user'=>Yii::t('yii','Name user')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNameMeasure()
    {
        return $this->hasOne(Measure::className(), ['name_measure' => 'name_measure']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->date_create=Yii::$app->formatter->asDatetime($this->date_create, "php:Y-m-d");
            $this->name_user==null ? $this->name_user=Yii::$app->user->identity->fullname: null;
            $this->name_user=='Администратор'? $this->name_user='Казаков Н.': null;
            return true;
        }
        return false;
    }
}