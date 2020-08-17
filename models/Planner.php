<?php

namespace app\models;

use Yii;
use app\components\helper\Datehelper;

/**
 * This is the model class for table "planner".
 *
 * @property integer $id
 * @property string $date
 * @property string $date_create
 * @property string $day_week
 * @property string $name_jobs
 * @property string $name_customers
 * @property string $customer_id
 * @property string $info_text
 * @property string $info_contract
 * @property string $info_request
 * @property string $name_status
 * @property string $name_performers1
 * @property string $name_performers2
 * @property string $all_performers
 * @property string $name_user
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
            [['date_create', 'date'], 'safe'],
            [['customer_id'], 'integer'],
            [['info_text', 'info_request'], 'string'],
            [['day_week', 'name_jobs'], 'string', 'max' => 15],
            [['name_customers', 'info_contract'], 'string', 'max' => 127],
            [['name_status', 'name_performers1', 'name_performers2', 'name_user'], 'string', 'max' => 31],
            [['name_customers'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['name_customers' => 'name']],
            [['name_jobs'], 'exist', 'skipOnError' => true, 'targetClass' => Jobs::className(), 'targetAttribute' => ['name_jobs' => 'name']],
            [['name_performers1'], 'exist', 'skipOnError' => true, 'targetClass' => Performers::className(), 'targetAttribute' => ['name_performers1' => 'name']],
            [['name_performers2'], 'exist', 'skipOnError' => true, 'targetClass' => Performers::className(), 'targetAttribute' => ['name_performers2' => 'name']],
            [['name_status'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['name_status' => 'status_name']],
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
            'date_create' => Yii::t('yii','Date create'),
            'day_week' => Yii::t('yii','Day Week'),
            'name_jobs' => Yii::t('yii','Name Jobs'),
            'name_customers' => Yii::t('yii','Name Customers'),
            'info_text' => Yii::t('yii','Info'),
            'info_contract' => Yii::t('yii','Info'),
            'info_request' => Yii::t('yii','Info'),
            'name_status' => Yii::t('yii','Name Status'),
            'name_performers1' => Yii::t('yii','Name Performers1'),
            'name_performers2' => Yii::t('yii','Name Performers2'),
            'name_user'=>Yii::t('yii','Name user')
        ];
    }

    /**
     * @return string
     */
    public function getAllPerformers()
    {
        /**
         * @var string $name_performers1
         * @var string $name_performers2
         */

        $this->name_performers1 ? $name_performers1 = $this->name_performers1:null;
        $this->name_performers2 ? $name_performers2 = ' / '. $this->name_performers2:null;
        return $name_performers1.$name_performers2;
    }

    /**
     * @return string
     */
    public function withoutContract()
    {
        return $this->info_contract == null ? $this->info_contract = 'Без договора':$this->info_contract;
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
    public function getNamePerformers1()
    {
        return $this->hasOne(Performers::className(), ['name' => 'name_performers1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNamePerformers2()
    {
        return $this->hasOne(Performers::className(), ['name' => 'name_performers2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNameStatus()
    {
        return $this->hasOne(Status::className(), ['status_name' => 'name_status']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->day_week = getDayRus($this->date);
            $this->date=Yii::$app->formatter->asDatetime($this->date, "php:Y-m-d");
            $this->date_create==null ? $this->date_create=Datehelper::setCurrentDate('Y-m-d H:i'):null;
            !($this->name_jobs=='заявка')?$this->info_request=null:null;
            $this->info_contract=='Выберите договор...' || $this->info_contract=='Без договора'? $this->info_contract=null:null;
            $this->name_performers1==''? $this->name_performers1=null:null;
            $this->name_performers2==''? $this->name_performers2=null:null;
            $this->name_user==null ? $this->name_user=Yii::$app->user->identity->fullname: null;
            $this->name_user=='Администратор'? $this->name_user='Казаков Н.': null;
            return true;
        }
        return false;
    }
}
