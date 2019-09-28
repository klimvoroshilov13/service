<?php
/**
 * User: Kazakov_N
 * Date: 11.11.2018
 * Time: 12:45
 */

namespace app\services;

use app\models\Parts;
use Yii;
use app\models\Jobs;
use app\models\Customers;
use app\models\Contracts;
use app\models\Performers;
use app\models\Status;
use app\models\Requests;
use yii\base\Object;
use yii\helpers\ArrayHelper;
use app\models\Planner;
use app\models\Measure;


class DataFromModel
{

    /**
     * @param string $id
     * @return object
     */

    public function getDataRequest($id){
       $modelRequest = Requests::findOne($id);
       return $modelRequest;
    }

    /**
     * @param Object $model
     * @return array
     */

    public function getDataArray($model,$status_contracts="all"){

        if ($model instanceof Planner){
            $job = trim(ArrayHelper::getValue($model,'name_jobs'));
            $jobs = ArrayHelper::map(Jobs::find()
                ->all(),'name','name');
        }

        if ($model instanceof Planner) {
            $contract = trim(ArrayHelper::getValue($model, 'info_contract'));
            $contract == null ? $contract = "Без договора" : null;
            switch ($status_contracts) {
                case "all" :
                    $contracts = ArrayHelper::map(Contracts::find()
                        ->where(['name_customers' => $model->name_customers])
                        ->all(), 'name', 'full_name');
                    break;
                case "active" :
                    $contracts = ArrayHelper::map(Contracts::find()
                        ->where(['name_customers' => $model->name_customers, 'flag' => 1])
                        ->all(), 'name', 'full_name');
                    break;
            }
            $contracts["Без договора"] = "Без договора";
        }

        if ($model instanceof Planner) {
            $performer1 = trim(ArrayHelper::getValue($model, 'name_performers1'));
            $performer2 = trim(ArrayHelper::getValue($model, 'name_performers2'));
            $performers = ArrayHelper::map(Performers::find()
                ->where(['flag' => 1])
                ->all(), 'name', 'name');
        }

        if ($model instanceof Planner) {
            $request = trim(ArrayHelper::getValue($model, 'info_request'));
            $requests = ArrayHelper::map(Requests::find()
                ->where(['name_status' => ['ожидание', 'выполняется', 'отложена']])
                ->all(), 'id', 'short_info');
            $requestsAll = ArrayHelper::map(Requests::find()
                ->where(['name_customers' => $model->name_customers])
                ->orderBy(['date_start' => SORT_ASC])
                ->all(), 'id', 'short_info');
            $requestsRun = Requests::find()
                ->where(['name_status' => ['ожидание', 'выполняется', 'отложена']])
                ->orderBy(['date_start' => SORT_ASC])
                ->all();
            $requestsRunDate = ArrayHelper::map($requestsRun, 'id',
                function ($requestsAllRun) {
                    return Yii::$app
                            ->formatter
                            ->asDatetime(
                                $requestsAllRun
                                    ->date_start, "php:d.m.Y"
                            )
                        . ' - (' . $requestsAllRun
                            ->name_customers . ')' .
                        $requestsAllRun
                            ->short_info;
                }
            );
        }

        if ($model instanceof Parts){
            $measure = trim(ArrayHelper::getValue($model,'name_measure'));
            $measures = ArrayHelper::map(Measure::find()
                ->orderBy(['name_measure'=>SORT_ASC])
                ->all(),'name_measure','name_measure');
        }

        $customer = trim(ArrayHelper::getValue($model,'name_customers'));
        $customers = ArrayHelper::map(Customers::find()
            ->orderBy(['name'=>SORT_ASC])
            ->all(),'name','name');

        $status = trim(ArrayHelper::getValue($model,'name_status'));
        $statuses = ArrayHelper::map(Status::find()
            ->all(),'name','name');

        return [
           'contracts'=>$contracts,
           'contract'=>$contract,
           'customer'=>$customer,
           'customers'=>$customers,
           'job' => $job,
           'jobs' => $jobs,
           'measure'=> $measure,
           'measures'=> $measures,
           'performers'=>$performers,
           'performer1'=>$performer1,
           'performer2'=>$performer2,
           'request'=>$request,
           'requests'=>$requests,
           'requestsRunDate'=>$requestsRunDate,
           'requestsAll'=>$requestsAll,
           'status'=>$status,
           'statuses'=>$statuses
       ];
    }
}