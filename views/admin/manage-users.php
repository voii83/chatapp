<?php

use yii\widgets\LinkPager;
use app\models\user\User;
use yii\helpers\BaseHtml;

$itemRole = [];
foreach ($roles as $role) {
    if ($role != User::USER_ROLE_ADMIN) {
        $itemRole[$role] = $role;
    }
}

?>

<?= $this->render('tab'); ?>

<div class="panel panel-primary">
    <div class="panel-heading">Manage users</div>
    <?php if ($users) : ?>
        <ul class="list-group messages-wrapper">
            <?php foreach ($users as $user) : ?>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-sm-4">
                            <?= $user->username; ?>
                        </div>
                        <div class="col-sm-4 select-role">
                            <?= BaseHtml::dropDownList(
                                'roles',
                                $user->auth->item_name,
                                $itemRole,
                                ['class' => 'user-role', 'data-user-id' => $user->id]);
                            ?>
                        </div>
                        <div class="col-sm-4 result">
                            <div class="result-change-role"></div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?= LinkPager::widget([
    'pagination' => $pages,
]); ?>

<?php
$js = <<<JS
    /* change user role */
    (function () {
        $('select.user-role').on('change', function() {
            var select = $(this);
            var user_id = $(this).attr('data-user-id');
            var option_selected = $(this).val();            
            
            $.ajax({
                url: '/admin/manage-users',
                type: 'POST',
                data: {user_id: user_id, option_selected: option_selected},
                success: function(res) {
                    var result = select.parent('.select-role').siblings('.result').find('.result-change-role');
                    result.text('done');
                    result.css({'box-shadow':'0 0 10px #452250','background':'#5cb85c','color':'#ffffff','transition':'0.5s','text-align':'center'});
                    setTimeout(function() {
                        result.text('');
                        result.css({'box-shadow': '0 0 0 transparent', 'transition': '0.5s'}).attr('style','');                   
                    }, 1000);
                },
                error: function(res) {
                    console.error(res);
                } 
            });
        })
    })();
JS;
$this->registerJs($js);
?>