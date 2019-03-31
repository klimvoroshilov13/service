<?php
/**
 * User: Kazakov_N
 * Date: 07.01.2019
 * Time: 6:50
 */

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * PlannerState - изменение состояниий для `app\models\Planner`.
 */

class PlannerState extends Planner
{
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function  requestchange($queryOne){
        $modelRequest = Requests::findOne($queryOne->info_request);
        if ($modelRequest) {
            $modelRequest->scenario = Requests::SCENARIO_USER;
            $modelRequest->name_performers = $queryOne->name_performers1;
            $modelRequest->name_status = $queryOne->name_status;
            $modelRequest->save();
        }
    }

    /**
     * Autochange planners
     *
     * @return array
     */
    public function autochange()
    {
        $queryArr = (['run'=>'','closed'=>'','wait'=>'']);
        $query = Planner::find()
            ->where('date = CURDATE() AND name_status = "ожидание" AND name_performers1 != "null"')
            ->column();
        if ($query) {
            foreach ($query as $value) {
                $queryOne = Planner::findOne($value);
                $queryOne->name_status = 'выполняется';
                $queryOne->save();
            }
            $queryArr['run'] = count($query);
        }

        $query = Planner::find()
                ->where('(date < CURDATE()) AND (name_status = "выполняется" OR name_status = "ожидание")')
                ->column();
        if ($query) {
            foreach ($query as $value) {
                $queryOne = Planner::findOne($value);
                switch ($queryOne->name_status) {
                    case 'выполняется':
                        $queryOne->name_status = 'завершена';
                        $queryOne->save();
                        $queryArr['closed'] = $queryArr['closed'] + 1;
//                        break;
                    case 'ожидание':
                        if ($queryOne->name_performers1 == null) {
                            try {
                                $queryOne->date = Yii::$app->formatter->asDatetime(setCurrentDate(), "php:Y-m-d");
                            } catch (InvalidConfigException $e) {
                            }
                            $queryOne->day_week = getDayRus($queryOne->date);
                            $queryOne->save();
                            $queryArr['wait'] = $queryArr['wait'] + 1;
                        }
                }
                $queryOne->name_jobs == 'заявка' ? $this->requestchange($queryOne) : null;
            }
        }
        return $queryArr;
    }
}