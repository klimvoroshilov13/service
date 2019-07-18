<?php
/**
 * User: Kazakov_N
 * Date: 11.11.2018
 * Time: 12:45
 */

namespace app\services;

use Yii;
use app\models\Jobs;
use app\models\Customers;
use app\models\Contracts;
use app\models\Performers;
use app\models\Status;
use app\models\Requests;
use yii\helpers\ArrayHelper;
use app\models\Planner;


class DataFromPlanner
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
     * @param Planner $model
     * @return array
     */

    public function getDataArray(Planner $model){

        $job = trim(ArrayHelper::getValue($model,'name_jobs'));
        $jobs = ArrayHelper::map(Jobs::find()
            ->all(),'name','name');

        $customer = trim(ArrayHelper::getValue($model,'name_customers'));
        $customers = ArrayHelper::map(Customers::find()
            ->orderBy(['name'=>SORT_ASC])
            ->all(),'name','name');

        $contract = trim(ArrayHelper::getValue($model,'info_contract'));
        $contracts = ArrayHelper::map(Contracts::find()
            ->where(['name_customers'=>$model->name_customers])
            ->all(),'name','full_name');

        $performer1 = trim(ArrayHelper::getValue($model,'name_performers1'));
        $performer2 = trim(ArrayHelper::getValue($model,'name_performers2'));
        $performers = ArrayHelper::map(Performers::find()
            ->where(['flag'=>1])
            ->all(),'name','name');

        $status = trim(ArrayHelper::getValue($model,'name_status'));
        $statuses = ArrayHelper::map(Status::find()
            ->all(),'name','name');

        $request = trim(ArrayHelper::getValue($model,'info_request'));
        $requests = ArrayHelper::map(Requests::find()
            ->where(['name_status'=>['ожидание','выполняется','отложена']])
            ->all(),'id','short_info');
        $requestsAll = ArrayHelper::map(Requests::find()
            ->where(['name_customers'=>$model->name_customers])
            ->orderBy(['date_start'=>SORT_ASC])
            ->all(),'id','short_info');
        $requestsRun = Requests::find()
            ->where(['name_status'=>['ожидание','выполняется','отложена']])
            ->orderBy(['date_start'=>SORT_ASC])
            ->all();
        $requestsRunDate = ArrayHelper::map($requestsRun,'id',
            function ($requestsAllRun) {
                return Yii::$app
                        ->formatter
                        ->asDatetime(
                            $requestsAllRun
                            ->date_start, "php:d.m.Y"
                        )
                            .' - ('.$requestsAllRun
                        ->name_customers.')'.
                            $requestsAllRun
                            ->short_info;
            }
        );

        return [
            'job' => $job,
            'jobs' => $jobs,
            'customer'=>$customer,
            'customers'=>$customers,
            'contracts'=>$contracts,
            'contract'=>$contract,
            'performers'=>$performers,
            'performer1'=>$performer1,
            'performer2'=>$performer2,
            'status'=>$status,
            'statuses'=>$statuses,
            'request'=>$request,
            'requests'=>$requests,
            'requestsRunDate'=>$requestsRunDate,
            'requestsAll'=>$requestsAll
        ];
    }
}