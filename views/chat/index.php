<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use app\models\user\User;

?>

<?php if ($user->checkAccess(User::USER_ROLE_USER)) : ?>
    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form_chat-message',
        ],
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'action' => Url::to(['chat/add-message']),
        'method' => 'post',
    ]); ?>

        <div class="col-sm-12">
            <?= $form->field($model, 'text')->textarea(); ?>
        </div>

        <div class="col-sm-12 text-right" style="margin-bottom: 20px;">
            <?= Html::submitButton( 'Add message', ['class' => 'btn btn-success']); ?>
        </div>

    <?php ActiveForm::end(); ?>
<?php endif; ?>

<div class="col-sm-12">
    <div class="panel panel-primary">
        <div class="panel-heading">Chat messages</div>
        <ul class="list-group messages-wrapper">
        <?php if ($messages) : ?>
            <?php foreach ($messages as $message) : ?>
            <?= $this->render('part/message', [
                'message' => $message,
                'user' => $user,
            ]); ?>
            <?php endforeach; ?>
        <?php endif; ?>
        </ul>
    </div>

    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
</div>

<?php
$js = <<<JS
    /* add message */
    (function () {
        $('.form_chat-message').on('beforeSubmit', function() {
            var form = $(this); 
            var data = $(this).serialize();
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: data,
                success: function(res) {                
                    form[0].reset();
                    $('.messages-wrapper').prepend(res);                    
                },
                error: function(res) {
                    console.error(res);
                } 
            });
            return false;  
        });
    })();

JS;
$this->registerJs($js);
?>