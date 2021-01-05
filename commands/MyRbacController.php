<?php

namespace app\commands;

use app\models\user\User;
use Yii;
use yii\console\Controller;

/**
 * Class MyRbacController
 * @package app\commands
 */
class MyRbacController extends Controller
{

    /**
     * Action init RBAC
     */
    public function actionInit() {
        $auth = Yii::$app->authManager;

        $auth->removeAll();

        $admin = $auth->createRole(User::USER_ROLE_ADMIN);
        $user = $auth->createRole(User::USER_ROLE_USER);
        $guest = $auth->createRole(User::USER_ROLE_GUEST);

        $auth->add($admin);
        $auth->add($user);
        $auth->add($guest);

        $administer = $auth->createPermission('administer');
        $administer->description = 'Администрировать';

        $readMessages = $auth->createPermission('readMessages');
        $readMessages->description = 'Читать сообщения';

        $writeMessages = $auth->createPermission('writeMessages');
        $writeMessages->description = 'Писать сообщения';

        $auth->add($administer);
        $auth->add($readMessages);
        $auth->add($writeMessages);

        $auth->addChild($guest,$readMessages);

        $auth->addChild($user,$readMessages);
        $auth->addChild($user,$writeMessages);

        $auth->addChild($admin, $guest);
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $administer);

        $auth->assign($admin, 1);
        $auth->assign($user, 2);
        $auth->assign($guest, 3);
    }
}