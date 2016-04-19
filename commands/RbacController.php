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
use app\models\User;
//use dektrium\user\models\User;
use Yii;

class RbacController extends Controller
{

    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        
        $userRole = $auth->createRole('user');
        $auth->add($userRole);        
        
        $adminRole = $auth->createRole('admin');
        $auth->add($adminRole);

        $viewNotification = $auth->createPermission('viewNotification');
        $viewNotification->description = 'Просмотр браузерных уведомлений';
        $auth->add($viewNotification);
        
        $manageNotification = $auth->createPermission('manageNotification');
        $manageNotification->description = 'Создание, редактирование и удаление уведомлений';
        $auth->add($manageNotification);

        $manageArticle = $auth->createPermission('manageArticle');
        $manageArticle->description = 'Создание, редактирование и удаление статей';
        $auth->add($manageArticle);

        $auth->addChild($userRole, $viewNotification);
        $auth->addChild($adminRole, $manageNotification);        
        $auth->addChild($adminRole, $manageArticle);
        $auth->addChild($adminRole, $userRole);

        // Ищем админа, если нет - создаем
        $admin = User::findOne(['username' => 'admin']);
        if(!$admin) {
            $admin = Yii::createObject(User::className());
            $admin->setScenario('register');
            $admin->username = 'admin';
            $admin->password = '123456';
            $admin->email = 'admin@example.com';
            $admin->confirmed_at = time();
            $admin->save();
        }
        
        $auth->assign($adminRole, $admin->id);
        
        // Назначаем всем пользователям (кроме админа) роль "пользователь"
        foreach (User::find()->where(['<>', 'id', $admin->id])->all() as $user) {
            $auth->assign($userRole, $user->id);
        }

    }
}
