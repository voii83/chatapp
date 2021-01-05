<?php

namespace app\controllers;

use app\models\user\User;
use yii\data\Pagination;
use app\models\chat\Chat;
use app\models\chat\ChatForm;
use yii\helpers\Url;

/**
 * Class ChatController
 * @package app\controllers
 */
class ChatController extends AppController
{
    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new ChatForm();

        $query = Chat::query($this->user)->orderBy(['created_at' => SORT_DESC]);
        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => Chat::PAGE_SIZE,
        ]);
        $messages = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index', [
            'model' => $model,
            'messages' => $messages,
            'pages' => $pages,
            'user' => $this->user,
        ]);
    }

    /**
     * Add message to chat
     * Ajax only
     * @return boolean|array
     */
    public function actionAddMessage()
    {
        $result = false;
        $user = $this->user;

        if (!$user->checkAccess(User::USER_ROLE_USER)) {
            return $result;
        }

        if ($this->request->isAjax) {

            $message = new ChatForm();

            if ($message->load($this->request->post()) && $result = $message->saveMessage($user)) {
                $this->layout = false;
                return $this->render('part/message', [
                    'message' => $result,
                    'user' => $user,
                ]);
            }
        }

        return $result;
    }

    /**
     * Delete message
     * @param $id
     * @return mixed
     */
    public function actionDeleteMessage($id)
    {
        $user = $this->user;

        if (!$user->checkAccess(User::USER_ROLE_ADMIN)) {
            return $this->redirect(Url::to(['chat/index']));
        }

        $message = Chat::query($user)
            ->andWhere(['id' => $id])
            ->one();

        if ($message) {
            $message->delete = true;
            $message->save();
        }

        return $this->redirect(Url::to(['chat/index']));
    }
}