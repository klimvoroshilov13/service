<?php
/**
 * User: Kazakov_N
 * Date: 11.11.2018
 * Time: 12:45
 */

namespace app\services;

use app\models\PartsRequest;
use Yii;
use app\models\PartsItem;
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

        switch ($model){
            case ($model instanceof Planner):
                $contract = 'info_contract';
                $customer = 'name_customers';
                $job = 'name_jobs';
                $performer1 = 'name_performers1';
                $performer2 = 'name_performers2';
                $request = 'info_request';
                $status = 'name_status';
            break;
            case ($model instanceof Requests):
                $contract = 'name_contracts';
                $customer = 'name_customers';
                $performer1 = 'name_performers';
                $status = 'name_status';
            break;
            case ($model instanceof PartsRequest):
                $customer = 'customer';
                $performer1 = 'name_performer';
                $status = 'status';
                break;
            case ($model instanceof PartsItem):
                $measure = 'measure';
                $statusItem = 'status';
                $supplier = 'supplier';
                break;
        }

        //Contracts
        if ($model instanceof Planner or $model instanceof Requests) {
            $contract = trim(ArrayHelper::getValue($model, $contract));
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
        $dataArray['contract'] = array_search($contract, $contracts);
        $dataArray['contracts'] = $contracts;
        }

        //Customers
        if ($model instanceof Planner or $model instanceof Requests or $model instanceof PartsRequest){
            $customer = trim(ArrayHelper::getValue($model, $customer));
            $customers = ArrayHelper::map(Customers::find()
                ->orderBy(['name' => SORT_ASC])
                ->all(), 'id', 'name');
        $dataArray['customer'] = array_search($customer, $customers);
        $dataArray['customers'] = $customers;
        }

        //Supplier
        if ($model instanceof PartsItem){
            $supplier = trim(ArrayHelper::getValue($model, $supplier));
            $suppliers = ArrayHelper::map(Customers::find()
                ->where(['name_status'=>'поставщик'])
                ->orderBy(['name' => SORT_ASC])
                ->all(), 'name', 'name');
            $dataArray['supplier'] = $supplier;
            $dataArray['suppliers'] = $suppliers;
        }

        //Jobs
        if ($model instanceof Planner){
            $job = trim(ArrayHelper::getValue($model,$job));
            $jobs = ArrayHelper::map(Jobs::find()
                ->all(),'name','name');
        $dataArray['job'] = $job;
        $dataArray['jobs'] = $jobs;
        }

        //Measure
        if ($model instanceof PartsItem){
            $measure = trim(ArrayHelper::getValue($model,'measure'));
            $measures = ArrayHelper::map(Measure::find()
                ->orderBy(['measure_name'=>SORT_ASC])
                ->all(),'measure_name','measure_name');
        $dataArray['measure'] = $measure;
        $dataArray['measures'] = $measures;
        }

        //Performers
        if ($model instanceof Planner or $model instanceof Requests or $model instanceof PartsRequest){
            $performer1 = trim(ArrayHelper::getValue($model, $performer1));
            !($performer2 == null) ? $performer2 = trim(ArrayHelper::getValue($model, $performer2)):null;
            $performers = ArrayHelper::map(Performers::find()
                ->where(['flag' => 1])
                ->all(), 'name', 'name');
        $dataArray['performer1'] = $performer1;
        $dataArray['performer2'] = $performer2;
        $dataArray['performers'] = $performers;
        }

        //Request
        if ($model instanceof Planner) {
            $request = trim(ArrayHelper::getValue($model, $request));
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
        $dataArray['request'] = $request;
        $dataArray['requests'] = $requests;
        $dataArray['requestsAll'] = $requestsAll;
        $dataArray['requestsRun'] = $requestsRun;
        $dataArray['requestsRunDate'] = $requestsRunDate;
        }

        //Status
        if ($model instanceof Planner or $model instanceof Requests
            or $model instanceof PartsRequest or $model instanceof PartsItem) {
            switch ($model) {
                case ($model instanceof Requests or $model instanceof Planner):
                    $status = trim(ArrayHelper::getValue($model, $status));
                    $statuses = ArrayHelper::map(Status::find()
                        ->where(['first_owner' => 'requests'])
                        ->all(), 'status_name', 'status_name');
                    $dataArray['status'] = $status;
                    $dataArray['statuses'] = $statuses;
                    break;
                case ($model instanceof PartsItem):
                    $dataArray['statusItem'] = trim(ArrayHelper::getValue($model, $statusItem));
                    $dataArray['statusesItem'] = ArrayHelper::map(Status::find()
                        ->where(['or',['=','first_owner','parts'],['=','second_owner','parts']])
                        ->all(), 'status_name', 'status_name');
                    break;
            }
        }
        return $dataArray;
    }
}
