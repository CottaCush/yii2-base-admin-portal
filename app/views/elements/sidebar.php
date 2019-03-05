<?php

use app\widgets\SidebarMenuWidget;
use yii\helpers\Html;

?>
<?= Html::beginTag('aside', ['class' => 'main-sidebar']) ?>
<?= Html::beginTag('section', ['class' => 'sidebar']) ?>
<?= SidebarMenuWidget::widget([
    'items' => [
        [
            'name' => 'Menu 1',
            'icon' => 'user', //use font awesome icons
            'link' => ['/default']
        ],
        [
            'name' => 'Invites',
            'icon' => 'paper-plane',
            'link' => ['/invite']
        ],
        [
            'name' => 'Menu 2',
            'icon' => 'user',
            'link' => ['/default'],
            'items' => [
                ['name' => 'Submenu 1', 'link' => ['/default']],
                ['name' => 'Submenu 2', 'link' => ['/default']]
            ],
        ]
    ],
    'submenuTemplate' => "\n<ul class='treeview-menu'>\n{items}\n</ul>\n",
]); ?>

<?= Html::endTag('section') ?>
<?= Html::endTag('aside');

