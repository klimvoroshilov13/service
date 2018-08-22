<?php

namespace app\modules\admin\models;

use Yii;
use app\models\User;

/**
 * Модель UserControl
 *
 * Свойства класса
 * @property integer $id // Номер пользователя.
 * @property string $username // Имя пользователя.
 * @property string $fullname // Полное имя.
 * @property string $password_hash // Хеш пароля пользователя.
 * @property string $password_reset_token // Токен для сброса пароля пользователя .
 * @property string $email // Email пользователя.
 * @property string $auth_key // Ключ аутентификации основанный на cookie файлах.
 * @property integer $status // Статус пользователя.
 * @property integer $created_at // Дата создания пользователя.
 * @property integer $updated_at // Дата изменения пользователя.
 * @property string $role // Роль пользователя.
 */
class UserControl extends User
{
    public $new_password; // Новый пароль.
    public $new_confirm; // Подтверждения нового пароля.
    private $old_password; // Старый пароль.

    /**
     *  Константы сценария создания
     * или редактирования пользователя.
     */
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_CREATE = 'create';

    /**
     * Метод возвращает поля модели взависимости от того
     * создаем или обновляем объект модели класса User
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_UPDATE] = ['username', 'new_password','email', 'role','new_confirm'];
        $scenarios[static::SCENARIO_CREATE] = ['username', 'auth_key','new_password','email','role','new_confirm'];
        return $scenarios;
    }

    /**
     * Унаследованный метод  класса Model,
     * возвращает правила проверки вводимых данных пользователем.
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Это имя пользователя уже существует.','on' => self::SCENARIO_CREATE],
            ['username', 'string', 'min' => 6, 'max' => 22],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 22],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Email уже существует.','on' => self::SCENARIO_CREATE],
            ['role', 'required'],
            ['new_password', 'string', 'min' => 6],
            ['new_password', 'required','on' => self::SCENARIO_CREATE],
            [['new_confirm'], 'compare', 'compareAttribute'=>'new_password', 'message'=>'Пароли не совпадают'],
        ];
    }

    /**
     *  Метод позволяющий переименовывать атрибуты таблиц
     *  выводимых на страницах web приложения
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'username' => Yii::t('yii','Name'),
            'email' => 'Email',
            'status' => Yii::t('yii','Status'),
            'new_password' => Yii::t('yii','New_password'),
            'new_confirm' => Yii::t('yii','New_confirm'),
            'created_at' => Yii::t('yii','Created_at'),
            'updated_at' => Yii::t('yii','Updated_at'),
            'role' => Yii::t('yii','Role'),
        ];
    }


    /**
     *  Метод позволяет изменять данные
     * после выборки данных из базы данных,
     * сохраняет старый пароль пользователя
     */
    public function afterFind() // при чтении из базы сохраняем хеш старого пароля
    {
        $this->old_password = $this->password_hash;
        parent::afterFind();
    }

    /**
     *  Метод позволяет изменять данные
     * перед сохранением в таблицу User(Пользователи) базы данных,
     * возвращает true при изменение данных или false если изменение не произошло.
     */
    public  function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            parent::generateAuthKey();

            if ($this->new_password)
            {
                $this->setPassword($this->new_password);
                return parent::beforeSave($insert);
            }
            else{
                $this->password_hash = $this->old_password;
                return parent::beforeSave($insert);
            }
        }
        else
            return false;
    }
}