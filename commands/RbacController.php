<?php
/*
 * @author Ivan Nikiforov
 * Apr 12, 2016
 */
namespace app\commands;

/**
 * Description of RbacController
 *
 * @author ivan
 */

use yii\console\Controller;
use Yii;

class RbacController extends Controller
{

    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $createPost = $auth->createPermission('createNotification');
        $createPost->description = 'Create a notification';
        $auth->add($createPost);

        $updatePost = $auth->createPermission('updateNotification');
        $updatePost->description = 'Update notification';
        $auth->add($updatePost);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $createPost);
        $auth->addChild($admin, $updatePost);        

        $auth->assign($admin, 1);
    }
}
