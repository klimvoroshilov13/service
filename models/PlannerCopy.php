<?php
/**
 * User: Kazakov_N
 * Date: 14.11.2018
 * Time: 14:19
 */

namespace app\models;
use yii\base\Model;

class PlannerCopy extends Model
{
    public $dateStart;
    public $dateEnd;

    public function rules()
    {
        return [
            [['dateStart'], 'required'],
            [['dateStart','dateEnd'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'dateStart'=>'С',
            'dateEnd'=>'По'
        ];
    }
}