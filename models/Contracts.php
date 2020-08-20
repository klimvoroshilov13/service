<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contracts".
 *
 * @property integer $id
 * @property string $name
 * @property string $note
 * @property string $full_name
 * @property string $flag
 * @property string $name_customers
 *
 * @property Customers $nameCustomers
 * @property Requests[] $requests
 */
class Contracts extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'contracts';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name','note'],'trim'],
            [['name_customers','name','note'], 'string', 'max' => 100],
            [['flag'],'required'],
            [['name'], 'unique'],
            [['name_customers'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['name_customers' => 'name']],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'name' => Yii::t('yii','Name Contract'),
            'note' => Yii::t('yii','Note'),
            'customer_id' => Yii::t('yii','Name Customer'),
            'name_customers' => Yii::t('yii','Name Customer'),
            'flag'=> Yii::t('yii','Name Status')
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
    public function getRequests()
    {
        return $this->hasMany(Requests::className(), ['name_contracts' => 'name']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            $this->full_name=$this->name. ($this->note ? ' ('.$this->note.')':null);
//            $this->flag =='выполняется' ? $this->flag=1:$this->flag=0;
            return true;
        }
        return false;
    }

}
