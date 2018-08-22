<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "planner".
 *
 * @property integer $id
 * @property string $date
 * @property string $day_week
 * @property string $name_jobs
 * @property string $name_customers
 * @property integer $info
 * @property string $name_status
 * @property string $name_performers1
 * @property string $name_performers2
 *
 * @property Customers $nameCustomers
 * @property Jobs $nameJobs
 * @property Performers $namePerformers1
 * @property Performers $namePerformers2
 * @property Status $nameStatus
 */
class Planner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'planner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'required'],
            //[['date'], 'safe'],
            //[['date'],'date','format'=>'php:d.m.Y'],
            [['info'], 'string'],
            [['name_jobs','day_week'], 'string', 'max' => 12],
            [['name_customers'], 'string', 'max' => 100],
            [['name_status', 'name_performers1', 'name_performers2'], 'string', 'max' => 30],
            [['name_customers'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['name_customers' => 'name']],
            [['name_jobs'], 'exist', 'skipOnError' => true, 'targetClass' => Jobs::className(), 'targetAttribute' => ['name_jobs' => 'name']],
            [['name_performers1'], 'exist', 'skipOnError' => true, 'targetClass' => Performers::className(), 'targetAttribute' => ['name_performers1' => 'name']],
            [['name_performers2'], 'exist', 'skipOnError' => true, 'targetClass' => Performers::className(), 'targetAttribute' => ['name_performers2' => 'name']],
            [['name_status'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['name_status' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => Yii::t('yii','Date'),
            'day_week' => Yii::t('yii','Day Week'),
            'name_jobs' => Yii::t('yii','Name Jobs'),
            'name_customers' => Yii::t('yii','Name Customers'),
            'info' => Yii::t('yii','Info'),
            'name_status' => Yii::t('yii','Name Status'),
            'name_performers1' => Yii::t('yii','Name Performers1'),
            'name_performers2' => Yii::t('yii','Name Performers2')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNameCustomers()
    {
        return $this->hasOne(Customers::className(), ['name' => 'name_customers']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNameJobs()
    {
        return $this->hasOne(Jobs::className(), ['name' => 'name_jobs']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNamePerfomers1()
    {
        return $this->hasOne(Performers::className(), ['name' => 'name_performers1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNamePerfomers2()
    {
        return $this->hasOne(Performers::className(), ['name' => 'name_performers2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNameStatus()
    {
        return $this->hasOne(Status::className(), ['name' => 'name_status']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->day_week=getDayRus($this->date);
            $this->date=Yii::$app->formatter->asDatetime($this->date, "php:Y-m-d");
            $this->name_performers1==''? $this->name_performers1=null:null;
            $this->name_performers2==''? $this->name_performers2=null:null;
            return true;
        }
        return false;
    }
}
