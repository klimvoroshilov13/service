<?php

/**
 * User: Kazakov_N
 * Date: 05.11.2017
 * Time: 21:31
 */

namespace app\components\rbac;

use Yii;
use yii\rbac\Rule;

/**
 * User group rule class.
 */
class GroupRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'group';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role;

            if ($item->name === 'admin') {
                return $role === $item->name;
            } elseif ($item->name === 'operator') {
                return $role === $item->name || $role === 'admin';
            } elseif ($item->name === 'user') {
                return $role === $item->name || $role === 'admin' || $role === 'operator';
            }
        return false;
        }
    }

}