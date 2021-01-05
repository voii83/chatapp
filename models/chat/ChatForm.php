<?php

namespace app\models\chat;

use yii\base\Model;

/**
 * ChatForm model
 * @package app\models\chat
 */
class ChatForm extends Model
{
    public $text;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'text' => 'Message text',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['text', 'string'],
            [['text'], 'required', 'message' => 'Error, enter text'],
        ];
    }

    /**
     * Save message
     * @return \app\models\chat\Chat | boolean
     */
    public function saveMessage($user)
    {
        if (!$this->validate()) {
            return false;
        }

        $chat = new Chat();
        $chat->text = $this->text;
        $chat->user_id = $user->id;
        return $chat->save() ? $chat : false;
    }
}