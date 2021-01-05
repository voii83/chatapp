<?php

namespace app\models\user;

use yii\db\ActiveRecord;

/**
 * AuthAssignment model
 * @package app\models\user
 * @property string $item_name
 * @property integer $user_id
 * @property integer $created_at
 */
class AuthAssignment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_assignment';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function  getUser()
    {
        return $this->hasOne(User::class, ['id', 'user_id']);
    }
}