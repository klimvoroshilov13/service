<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "performers".
 *
 * @property integer $id
 * @property string $name
 * @property integer $stateRequest
 *
 * @property Requests[] $requests
 */
class Performers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'performers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stateRequest'], 'integer'],
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
            'stateRequest' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Requests::className(), ['name_performers' => 'name']);
    }
}
