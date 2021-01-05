<?php

namespace app\controllers;

use Yii;
use app\models\chat\Chat;
use app\models\user\User;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;

/**
 * Class AdminController
 * @package app\controllers
 */
class AdminController extends AppController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [User::USER_ROLE_ADMIN]
                    ],
                ],
            ],
        ];
    }

    /**
     * Admin default action
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Manage users
     * @return mixed
     * @throws \Exception
     */
    public function actionManageUsers()
    {
        $roles = User::getUserRoles();

        if ($this->request->isAjax) {

            $user_id = $this->request->post('user_id');
            $role_selected = $this->request->post('option_selected');

            if (in_array($role_selected, $roles) && $user_id) {

                $auth = Yii::$app->authManager;
                $role = $auth->getRole($role_selected);
                $auth->revokeAll($user_id);
                $auth->assign($role, $user_id);

                return true;
            }

            return false;
        }

        $query = User::find()
            ->joinWith(['auth'])
            ->where('auth_assignment.item_name != :role', ['role' => User::USER_ROLE_ADMIN])
            ->orderBy(['created_at' => SORT_DESC]);

        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => User::PAGE_SIZE,
        ]);

        $users = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('manage-users', [
            'roles' => $roles,
            'users' => $users,
            'pages' => $pages,
        ]);
    }

    /**
     * Deleted messages
     * @return mixed
     */
    public function actionDeletedMessages()
    {
        $query = Chat::query($this->user)
            ->andWhere(['delete' => true])
            ->orderBy(['created_at' => SORT_DESC]);

        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => Chat::PAGE_SIZE,
        ]);
        $deletedMessages = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('deleted-messages', [
            'deletedMessages' => $deletedMessages,
            'pages' => $pages,
        ]);
    }

    /**
     * Restore message
     * @param $id
     * @return \yii\web\Response
     */
    public function actionRestoreMessage($id)
    {
        $user = $this->user;
        $message = Chat::query($user)
            ->andWhere(['id' => $id])
            ->one();

        if ($message) {
            $message->delete = false;
            $message->save();
        }

        return $this->redirect(Url::to(['admin/deleted-messages']));
    }
}