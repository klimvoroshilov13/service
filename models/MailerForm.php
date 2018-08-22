<?php

namespace app\models;

use Yii;
use yii\base\Model;


/**
 * Класс модели MailerForm(модель формы отправки заявки по email)
 */
class MailerForm extends Model
{
    /**
     *  Свойства класса
     */
    public $fromEmail;  //Email отправителя.
    public $fromName;   //Имя отправителя.
    public $toEmail;    //Email получателя.
    public $subject;    //Тема сообщения.
    public $body;       //Тело сообщения.

    /**
     * Унаследованный метод  класса Model
     * возвращает правила проверки вводимых данных пользователем.
     */
    public function rules()
    {
        return [
            [['toEmail', 'subject', 'body'], 'required'],
            ['toEmail', 'email']
        ];
    }

    /**
     * Унаследованный метод  класса Model
     * возвращает названия атрибутов формы авторизации web приложения.
     */
    public function attributeLabels()
    {
        return [
            'toEmail' => Yii::t('yii','To Email:'),
            'subject' => Yii::t('yii','Subject:'),
            'body' => Yii::t('yii','Info:'),
        ];
    }

    /**
     *  Метод отправки сообщения по email,
     * возвращает true когда сообщение успешно отправлено,
     * иначе false.
     */
    public function sendEmail()
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($this->toEmail)
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        }
        return false;
    }
}