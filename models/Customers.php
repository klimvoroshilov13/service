<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Класс модели  Customers(контрагенты).
 *
 * Свойства класса
 * @property integer $id // Номер контрагента.
 * @property string $name // Наименование контрагента.
 * @property string $full_name // Полное наименование контрагента.
 * @property string $legal_address // Юридический адрес контрагента.
 * @property string $mailing_address // Почтовый адрес контрагента.
 * @property string $kpp // КПП контрагента.
 * @property string $inn // ИНН контрагента.
 * @property Requests[] $requests
 */
class Customers extends ActiveRecord
{
    /**
     *  Унаследованный метод  класса ActiveRecord
     * возвращает имя таблицы базы данных.
     */
    public static function tableName()
    {
        return 'customers';
    }

    /**
     *  Унаследованный метод  класса Model
     * возвращает правила проверки вводимых данных пользователем.
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
            [['full_name'], 'string', 'max' => 150],
            [['legal_address', 'mailing_address'], 'string', 'max' => 255],
            [['inn'], 'string', 'max' => 12],
            [['kpp'], 'string', 'max' => 9],
            ];
    }

    /**
     *  Метод позволяющий переименовывать атрибуты таблиц
     * выводимых на страницах web приложения
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'name' => Yii::t('yii','Name Customer'),
            'full_name' => Yii::t('yii','Full Name Customer'),
            'legal_address' => Yii::t('yii','Legal Address'),
            'mailing_address' => Yii::t('yii','Mailing Address'),
            'kpp' => Yii::t('yii','Kpp'),
            'inn' => Yii::t('yii','Inn'),
        ];
    }

    /**
     *  Метод осуществляет связь с таблицей Contracts,
     * возвращает поле таблицы Customers связанное с полем таблицы Contracts.
     */
    public function getContracts()
    {
        return $this->hasMany(Contracts::className(), ['name_customers' => 'name']);
    }

    /**
     *  Метод осуществляет связь с таблицей Requests,
     * возвращает поле таблицы Customers связанное с полем таблицы Requests.
     */
    public function getRequests()
    {
        return $this->hasMany(Requests::className(), ['name_customers' => 'name']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->name=trim($this->name);
            return true;
        }
        return false;
    }

}
