<?php
/**
 * User: Kazakov_N
 * Date: 18.07.2018
 * Time: 17:18
 */
namespace app\components\helper;

use yii\helpers\ArrayHelper;

class Dbhelper
{
    public static function maps($model,$where){

        $where==null ? $maps = ArrayHelper::map($model::find()->all(),'name','name'):
            $maps = ArrayHelper::map($model::find()->where($where)->all(),'name','name');

        return $maps;
    }

}