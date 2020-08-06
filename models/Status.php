<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "status".
 *
 * @property integer $id
 * @property string $status_name
 * @property string $abbreviated_name
 * @property string $first_owner
 * @property string $second_owner
 * @property string $third_owner
 *
 * @property PartsItem[] $parts
 * @property Planner[] $planners
 * @property Requests[] $requests
 * @property Apps $firstOwner
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
            [['status_name', 'first_owner', 'second_owner', 'third_owner'], 'string', 'max' => 31],
            [['abbreviated_name'], 'string', 'max' => 7],
            [['status_name'], 'unique'],
            [['first_owner'], 'exist', 'skipOnError' => true, 'targetClass' => Apps::className(), 'targetAttribute' => ['first_owner' => 'url']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yii', 'ID'),
            'status_name' => Yii::t('yii', 'Status Name'),
            'abbreviated_name' => Yii::t('yii', 'Abbreviated Name'),
            'first_owner' => Yii::t('yii', 'First Owner'),
            'second_owner' => Yii::t('yii', 'Second Owner'),
            'third_owner' => Yii::t('yii', 'Third Owner'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParts()
    {
        return $this->hasMany(PartsItem::className(), ['status' => 'status_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanners()
    {
        return $this->hasMany(Planner::className(), ['name_status' => 'status_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Requests::className(), ['name_status' => 'status_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFirstOwner()
    {
        return $this->hasOne(Apps::className(), ['url' => 'first_owner']);
    }
}
