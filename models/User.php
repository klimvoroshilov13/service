<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 *
 * Коласс модели User
 *
 * Свойства класса
 * @property integer $id // Номер пользователя.
 * @property string $username // Имя пользователя.
 * @property string $password_hash // Хеш пароля пользователя.
 * @property string $password_reset_token // Токен для сброса пароля пользователя .
 * @property string $email // Email пользователя.
 * @property string $auth_key // Ключ аутентификации основанный на cookie файлах.
 * @property integer $status// Статус пользователя.
 * @property integer $created_at // Дата создания пользователя.
 * @property integer $updated_at // Дата изменения пользователя.
 * @property string $role // Роль пользователя.
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const ROLE_USER ='user';

    /**
     *  Унаследованный метод  класса ActiveRecord
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * Унаследованный метод класса Component.
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * Унаследованный метод  класса Model,
     * возвращает правила проверки вводимых данных пользователем.
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     *  Метод возвращает действующего пользователя по id.
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     *  Метод используется, когда требуется аутентифицировать пользователя
     * только по секретному токену если нет токена, возвращает ошибку.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Метод возвращает id пользователя.
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Метод возвращает auth_key
     * (ключ аутентификации основанный на cookie файлах) пользователя.
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Метод валидации auth_key
     * (ключ аутентификации основанный на cookie файлах) пользователя.
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Метод возвращает действующего пользователя по username.
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     *  Метод валидации пароля пользователя,
     * возвращает хешированный пароль.
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Метод возвращает hash пароля пользователя.
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Метод возвращает сгенерированный  auth_key
     * (ключ аутентификации основанный на cookie файлах) пользователя.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     *  Унаследованный метод класса BaseActiveRecord  позволяет  
     * изменять данные после сохранения пользователя в базу данных,
     * устанавливает роль пользователя user если роль пользователя не установлена.
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // установка роли пользователя
        $auth = Yii::$app->authManager;
        $name = $this->role ? $this->role : self::ROLE_USER;
        $role = $auth->getRole($name);
        if (!$insert) {
            $auth->revokeAll($this->id);
        }
        $auth->assign($role, $this->id);
    }
}
