<?php
/**
 * User: Kazakov_N
 * Date: 15.12.2018
 * Time: 20:28
 */

namespace app\models;
use yii\base\Model;


class PlannerFilter extends Model
{
    public $month;
    public $year;

    public function rules()
    {
        return [
            [['month','year'], 'string']
        ];
    }
}