<?php

namespace app\models\chat;

use app\models\user\User;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Chat model
 * @package app\models\chat
 * @property integer $id
 * @property string $text
 * @property integer $user_id
 * @property boolean $delete
 * @property integer $created_at
 * @property integer $updated_at
 */
class Chat extends ActiveRecord
{
    const PAGE_SIZE = 20;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chat}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * Chat message query
     * @param \app\models\user\User $user
     * @return \yii\db\ActiveQuery
     */
    public static function query($user)
    {
        $result = self::find()->with(['user.auth']);
        if (!$user->checkAccess(User::USER_ROLE_ADMIN)) {
            $result->where(['delete' => false]);
        }

        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}