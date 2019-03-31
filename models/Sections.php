<?php
/**
 * User: Kazakov_N
 * Date: 14.01.2019
 * Time: 20:53
 */

namespace app\models;


use yii\db\ActiveRecord;

class Sections extends ActiveRecord
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['name'], 'string', 'max' => 32]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSections()
    {
        return $this->hasMany(Apps::className(),['name' => 'section']);
    }
}