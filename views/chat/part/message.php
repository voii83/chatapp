<?php

use app\models\user\User;
use yii\helpers\Url;

$isAdmin = $user->checkAccess(User::USER_ROLE_ADMIN);

?>

<li class="list-group-item">

    <p class="text-right"><small><?= date('d.m.Y', $message->created_at); ?></small></p>
    <p <?php if ($isAdmin && $message->delete) : ?> style="text-decoration: line-through" <?php endif; ?>>
        <?= $message->text; ?>
        <?php if ($message->user->auth->item_name == User::USER_ROLE_ADMIN) : ?>
            <span class="label label-info">Admin message</span>
        <?php endif; ?>
    </p>
    <p class="text-right">
        <?php if ($isAdmin && !$message->delete) : ?>
            <a href="<?= Url::to(['chat/delete-message', 'id' => $message->id]) ?>" class="btn btn-danger">Delete</a>
        <?php endif; ?>
        <?php if ($isAdmin && $message->delete) : ?>
            <span class="label label-warning">Deleted</span>
        <?php endif; ?>
    </p>
</li>