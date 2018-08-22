<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jobs".
 *
 * @property integer $id
 * @property string $name
 * @property integer $flag
 *
 * @property Planner[] $planners
 */
class Jobs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jobs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['flag'], 'integer'],
            [['name'], 'string', 'max' => 12],
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
            'flag' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanners()
    {
        return $this->hasMany(Planner::className(), ['name_jobs' => 'name']);
    }
}
