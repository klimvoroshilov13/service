<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\components\helper\Adminhelper;

/**
 *  Класс модели Requests(заявки)
 *
 * Свойства класса
 * @property integer $id // Номер заявки
 * @property string $date_start // Дата создания заявки
 * @property string $date_run // Дата начала выполнения заявки
 * @property string $date_changed_status // Даты изменения статуса
 * @property string $date_end // Дата завершения заявки
 * @property string $name_customers //Наименование контрагента
 * @property string $info // Информация по заявки
 * @property string $comment // Комментарий по заявке
 * @property string $phone // Номер телефона
 * @property string $contacts // Контакты контрагента
 * @property string $name_performers // Наименование исполнителя
 * @property string $name_status // Статус заявки
 * @property string $name_contracts // Договор контрагента
 * @property string $name_user // Имя пользователя
 *
 * @property User $nameUser
 * @property Contracts $nameContracts
 * @property Customers $nameCustomers
 * @property Performers $namePerformers
 * @property Status $nameStatus
 */

class Requests extends ActiveRecord
{

    /**
     *  Константы ролей пользователя
     */
    const SCENARIO_ADMIN = 'admin';
    const SCENARIO_OPERATOR = 'operator';
    const SCENARIO_USER = 'user';

    /**
     *   Унаследованный метод  класса Model,
     * возвращает поля формы заявки взависимости
     * от роли пользователя(admin,operator,user).
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_ADMIN] = ['date_start','date_run','date_changed_status', 'date_end','name_customers','info','comment','phone',
            'contacts','name_performers', 'name_status','name_contracts'];
        $scenarios[static::SCENARIO_OPERATOR] = ['date_start','date_changed_status','name_customers','info','phone',
            'contacts','name_contracts'];
        $scenarios[static::SCENARIO_USER] = ['date_end','date_run','date_changed_status','name_customers','info','comment','phone',
            'contacts','name_performers', 'name_status','name_contracts'];
        return $scenarios;
    }


    /**
     *  Унаследованный метод  класса ActiveRecord
     * возвращает имя таблицы базы данных.
     */
    public static function tableName()
    {
        return 'requests';
    }

    /**
     * Унаследованный метод  класса Model,
     * возвращает правила проверки вводимых данных пользователем.
     */
    public function rules()
    {
        return [
            [['date_start','date_run','date_end','info','contacts'],'trim'],
            [['date_start','date_end'],'date','format'=>'php:d.m.Y H:i'],
            [['date_run'],'date','format'=>'php:Y-m-d H:i:s'],
            [['date_start','date_run','date_end'], 'default','value' => null],
            [['name_customers','name_contracts'],'string', 'max' => 100],
            [['date_changed_status','info','comment'], 'string'],
            [['phone'], 'string', 'max' => 16],
            [['contacts'], 'string', 'max' => 250],
            [['name_performers', 'name_status'], 'string', 'max' => 30],
            [['name_user'], 'string', 'max' => 22],
            [['name_customers','phone','contacts','info'], 'required'],// Поля необходимые к заполнению
            [['name_customers'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['name_customers' => 'name']],
            [['name_performers'], 'exist', 'skipOnError' => true, 'targetClass' => Performers::className(), 'targetAttribute' => ['name_performers' => 'name']],
            [['name_status'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['name_status' => 'name']],
            [['name_contracts'], 'exist', 'skipOnError' => true, 'targetClass' => Contracts::className(), 'targetAttribute' => ['name_contracts' => 'name']],
            [['name_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['name_user' => 'username']],
            ];
    }

    /**
     * Унаследованный метод  класса Model
     * возвращает названия атрибутов таблицы Requests базы данных.
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'date_start' => Yii::t('yii','date_start'),
            'date_run' => Yii::t('yii','date_run'),
            'date_changed_status'=> Yii::t('yii','Date_changed_status'),
            'date_end' => Yii::t('yii','date_end'),
            'name_customers' => Yii::t('yii','name_customers'),
            'info' => Yii::t('yii','info'),
            'comment' => Yii::t('yii','comment'),
            'phone'=> Yii::t('yii','phone'),
            'contacts' => Yii::t('yii','contacts'),
            'name_performers' => Yii::t('yii','name_performers'),
            'name_status' => Yii::t('yii','name_status'),
            'name_contracts'=> Yii::t('yii','name_contracts'),
            'name_user' => Yii::t('yii','name_user'),
            ];
    }

    /**
     *  Метод осуществляет связь с таблицей Customers,
     * возвращает поле таблицы Requests связанное с полем таблицы Customers.
     */
    public function getNameCustomers()
    {
        return $this->hasOne(Customers::className(), ['name' => 'name_customers']);
    }

    /**
     *  Метод осуществляет связь с таблицей Performers,
     * возвращает поле таблицы Requests связанное с полем таблицы Performers.
     */
    public function getNamePerformers()
    {
        return $this->hasOne(Performers::className(), ['name' => 'name_performers']);
    }

    /**
     *  Метод осуществляет связь с таблицей Status,
     * возвращает поле таблицы Requests связанное с полем таблицы Status.
     */
    public function getNameStatus()
    {
        return $this->hasOne(Status::className(), ['name' => 'name_status']);
    }

    /**
      *  Метод осуществляет связь с таблицей Contracts,
      * возвращает поле таблицы Requests связанное с полем таблицы Contracts.
      */
    public function getNameContracts()
    {
        return $this->hasOne(Contracts::className(), ['name' => 'name_contracts']);
    }

    /**
     *  Метод осуществляет связь с таблицей User,
     * возвращает поле таблицы Requests связанное с полем таблицы User.
     */
    public function getNameUser()
    {
        return $this->hasOne(User::className(), ['username' => 'name_user']);
    }

    /**
     *  Унаследованный метод класса BaseActiveRecord
     * позволяет изменять данные перед сохранением в таблицу Requests(Заявки)
     * базы данных возвращает true при изменение данных или false если изменение не произошло.
     */
    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {
            $this->name_status=='завершена' && $this->date_end==null ? $this->date_end = setCurrentDate():null;
            $this->getOldAttribute('name_status')=='ожидание' && $this->name_status=='завершена'? $this->name_status='ожидание': null;

            !($this->name_status == $this->getOldAttribute('name_status')) && !($this->name_status=='завершена')? $this->date_run = setCurrentDate():null;

            if (!($this->name_status == $this->getOldAttribute('name_status'))) {
                $status = Status::find()->where(['name'=> $this->name_status])->one();
                if ($this->date_changed_status == null){
                    $this->date_changed_status = Yii::$app->formatter->asDatetime(setCurrentDate(), "php:H:i d.m.y")
                        .'-'.$status->abbreviated_name;
                }
               else
               {
                   $this->date_changed_status = $this->getOldAttribute('date_changed_status').'  '
                       .Yii::$app->formatter->asDatetime(setCurrentDate(), "php:H:i d.m.y").' - '.$status->abbreviated_name;
               }

            }


            $this->name_status==null ? $this->name_status='ожидание': null;

            $this->name_status=='ожидание' && !(Yii::$app->user->identity->username =='Admin') ? $this->name_user=Yii::$app->user->identity->username: null;

            $this->name_status=='ожидание'||$this->name_status=='отложена'||$this->name_status=='отменена'? $this->name_performers=null: null;
            $this->name_status=='ожидание'||$this->name_status=='выполняется' ? $this->date_end=null: null;
            $this->name_status=='отменена'? $this->date_end=$this->date_run:null;

            $this->date_start=Yii::$app->formatter->asDatetime($this->date_start, "php:Y-m-d H:i");
            !($this->date_end==null) ? $this->date_end=Yii::$app->formatter->asDatetime($this->date_end, "php:Y-m-d H:i"):null;
            !($this->date_run==null) ? $this->date_run=Yii::$app->formatter->asDatetime($this->date_run, "php:Y-m-d H:i"):null;

            $this->name_contracts=='' ? $this->name_contracts=null:null;
            $this->name_performers=='' ? $this->name_performers=null:null;
            !(empty($this->comment)) ? $this->info=$this->info.' *': null;
            return true;
        }
        return false;
    }


}

