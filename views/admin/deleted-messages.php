<?php

use app\models\user\User;
use yii\widgets\LinkPager;
use yii\helpers\Url;

?>
<?= $this->render('tab'); ?>
<div class="panel panel-primary">
    <div class="panel-heading">Deleted chat messages</div>
    <?php if ($deletedMessages) : ?>
        <ul class="list-group messages-wrapper">
            <?php foreach ($deletedMessages as $message) : ?>
                <li class="list-group-item">

                    <p class="text-right"><small><?= date('d.m.Y', $message->created_at); ?></small></p>
                    <p>
                        <?= $message->text; ?>
                        <?php if ($message->user->auth->item_name == User::USER_ROLE_ADMIN) : ?>
                            <span class="label label-info">Admin message</span>
                        <?php endif; ?>
                    </p>
                    <p class="text-right">
                        <a href="<?= Url::to(['admin/restore-message', 'id' => $message->id]) ?>" class="btn btn-success">Restore</a>
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?= LinkPager::widget([
    'pagination' => $pages,
]); ?>