<?php
/*
 * @author Ivan Nikiforov
 * Apr 13, 2016
 */
namespace app\components;

use dektrium\user\models\User;
use yii\helpers\ArrayHelper;

/**
 * Description of FormHelper
 *
 * @author ivan
 */
class FormHelper
{
    
    private static $_userList = null;

    public static function getUserList()
    {
        if (!is_null(self::$_userList)) {
            return self::$_userList;
        }

        $users = User::find()
            ->where('blocked_at IS NULL')
            ->orderBy('username')
            ->all();

        self::$_userList = ArrayHelper::map($users, 'id', 'username');
        return self::$_userList;
    }
}
