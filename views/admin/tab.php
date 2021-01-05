<?php

use yii\bootstrap\Tabs;
?>
<h3>Admin panel</h3>

<div style="margin-bottom: 10px;">
<?= Tabs::widget([
    'items' => [
        [
            'label' => 'Info',
            'url' => ['/admin/index'],
            'active' => Yii::$app->controller->action->id === 'index',
        ],
        [
            'label' => 'Users',
            'url' => ['/admin/manage-users'],
            'active' => Yii::$app->controller->action->id === 'manage-users',
        ],
        [
            'label' => 'Deleted messages',
            'url' => ['/admin/deleted-messages'],
            'active' => Yii::$app->controller->action->id === 'deleted-messages',
        ],

    ]
]); ?>
</div>
