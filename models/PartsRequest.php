<?php
/**
 * Created by Nikolay N. Kazakov
 * File: PartsRequest.php
 * Date: 10.10.2019
 * Time: 20:24
 */

namespace app\models;


use Yii;

/**
 * This is the model class for table "parts_request".
 *
 * @property integer $id
 * @property string $date_creation
 * @property string $date_completation
 * @property string $customer
 * @property string $name_performer
 * @property string $user
 *
 * @property PartsItem[] $parts
 */
class PartsRequest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parts_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['date_creation', 'customer', 'status', 'user'], 'required'],
            [['date_creation', 'date_completation'], 'safe'],
            [['name_performer'], 'string', 'max' => 31],
            [['customer'], 'string', 'max' => 127],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yii', 'ID'),
            'date_creation' => Yii::t('yii', 'Date create'),
            'date_completation' => Yii::t('yii', 'Date Completation'),
            'customer' => Yii::t('yii', 'Customer'),
            'name_performer' => Yii::t('yii', 'Performer'),
            'user' => Yii::t('yii', 'User'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParts()
    {
        return $this->hasMany(PartsItem::className(), ['part_request' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->date_creation=Yii::$app->formatter->asDatetime($this->date_creation, "php:Y-m-d");
            $this->user==null ? $this->user=Yii::$app->user->identity->fullname: null;
            $this->user=='Администратор'? $this->user='Казаков Н.': null;
            return true;
        }
        return false;
    }

}
