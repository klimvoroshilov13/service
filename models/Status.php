<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Status".
 *
 * @property integer $id
 * @property string $name
 * @property string $abbreviated_name
 *
 * @property Requests[] $requests
 */
class Status extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 30],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'abbreviated_name'=>'Abbreviated name'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Requests::className(), ['name_status' => 'name']);
    }
}
