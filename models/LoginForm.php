<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Класс модели LoginForm(модель формы авторизации в web приложение)
 */
class LoginForm extends Model
{
    /**
     *  Свойства класса
     */
    public $username;   // Логин пользователя
    public $password;   // Пароль пользователя
    public $rememberMe = true; //Запомнить пользователя
    private $_user = false; // Временное хранение логина пользователя


    /**
     * Унаследованный метод  класса Model
     * возвращает правила проверки вводимых данных пользователем.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     *  Метод проверки корректности ввода учетной записи,
     * возвращает ошибку при вводе пользователем не корректных данных учетной записи.
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильный логин или пароль.');
            }
        }
    }

    /**
     * Унаследованный метод  класса Model
     * возвращает названия атрибутов формы авторизации web приложения.
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }

    /**
     *  Метод входа пользователя в web приложение,
     * возвращает залогиненного пользователя, иначе возвращает false.
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     *  Метод входа администратора в web приложение,
     * возвращает залогиненного администратора, иначе возвращает false.
     */
    public function loginAdmin()
    {
        if ($this->validate() && User::isUserAdmin($this->username)) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     *  Метод временного хранения логина пользователя,
     * возвращает логин пользователя.
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}
