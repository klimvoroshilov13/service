<?php
/**
 * User: Kazakov_N
 * Date: 08.01.2019
 * Time: 14:39
 */

namespace app\commands;

use yii\console\Controller;


class MainController extends Controller
{

    public function actionHello (){
        echo 'Hello world';
        die;
    }
}