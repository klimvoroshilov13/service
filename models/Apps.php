<?php
/**
 * User: Kazakov_N
 * Date: 14.01.2019
 * Time: 20:35
 */

namespace app\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Apps extends ActiveRecord
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name','url','section'], 'string'],
            [['name','url','section'], 'string', 'max' => 32],
            [['section'], 'exist', 'skipOnError' => true, 'targetClass' => Sections::className(), 'targetAttribute' => ['section' => 'name']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSections()
    {
        return $this->hasOne(Sections::className(),['section' => 'name']);
    }

    public static function findAllApps($section){
        return $apps = ArrayHelper::map(Apps::find()
                         ->where(['section'=>$section])
                         ->all(),
                         'name','url'
        );
    }
}