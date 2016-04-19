<?php
/*
 * @author Ivan Nikiforov
 * Apr 13, 2016
 */
namespace app\models;

use dektrium\user\models\User as Yii2User;
use app\components\notification\PlaceholdersInterface;

/**
 * Description of User
 *
 * @author ivan
 */
class User extends Yii2User implements PlaceholdersInterface
{

    public function getPlaceholders()
    {
        return [
            '{username}' => $this->username,
            '{id}' => $this->id
        ];
    }
}
