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
    public $firstPerformers;
    public $secondPerformers;

    public function rules()
    {
        return [
            [['dateStart'], 'required'],
            [['dateStart','dateEnd','firstPerformers','secondPerformers'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'dateStart'=>'С',
            'dateEnd'=>'По'
        ];
    }

    public function copy($id){
        $this->dateEnd && date($this->dateEnd) > date($this->dateStart)
            ? $sumDays = date($this->dateEnd) - date($this->dateStart)
            : $sumDays = 0;
        for ($i = 0;$i <= ($sumDays);$i++){
            $model = new Planner();
            $model->attributes = Planner::findOne($id)->attributes ;
            $model->date = date('Y-m-d H:i',strtotime($this->dateStart. ' + '.$i.' days'));
            $model->name_status = 'ожидание';
            $model->save();
        }
    }
}