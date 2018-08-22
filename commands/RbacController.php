<?php
/**
 * User: Kazakov_N
 * Date: 05.11.2017
 * Time: 21:28
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\components\rbac\GroupRule;
use yii\rbac\DbManager;

/**
 * RBAC console controller.
 */
class RbacController extends Controller
{

    public function actionInit($id = null)
    {
        $auth = new DbManager;
        $auth->init();

        $auth->removeAll(); //удаляем старые данные
        // Rules
        $groupRule = new GroupRule();

        $auth->add($groupRule);

        // Roles
        $user = $auth->createRole('user');
        $user->description = 'User';
        $user->ruleName = $groupRule->name;
        $auth->add($user);

        $operator = $auth->createRole('operator');
        $operator ->description = 'Operator';
        $operator ->ruleName = $groupRule->name;
        $auth->add($operator);
        $auth->addChild($operator, $user);

        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $admin->ruleName = $groupRule->name;
        $auth->add($admin);
        $auth->addChild($admin, $operator);
    }
}