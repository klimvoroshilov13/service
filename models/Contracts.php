<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contracts".
 *
 * @property integer $id
 * @property string $name
 * @property string $note
 * @property string $flag
 * @property string $name_customers
 *
 * @property Customers $nameCustomers
 * @property Requests[] $requests
 */
class Contracts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contracts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','note'],'trim'],
            [['name_customers','name','note'], 'string', 'max' => 100],
            [['name'], 'unique'],
            [['name_customers'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['name_customers' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'name' => 'Наименование договора',
            'note' => 'Примечание',
            'name_customers' => 'Наименование контрагента',
            'flag'=>'Статус'
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

//    public function beforeSave($insert)
//    {
//        if (parent::beforeSave($insert))
//        {
//            $this->flag =='выполняется' ? $this->flag=1:$this->flag=0;
//            return true;
//        }
//        return false;
//    }

}
