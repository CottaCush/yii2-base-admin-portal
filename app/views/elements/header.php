<?php

use app\widgets\UserAvatar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?=
Html::beginTag('header', ['class' => 'main-header']) .
Html::a(
     Html::img(Url::toRoute('/images/logos/mini-logo.png'), ['class' => 'logo-mini']) .
    Html::img(Url::toRoute('/images/logos/main-logo.png'), ['class' => 'logo-lg']),
    Url::toRoute('/admin/'),
    ['class' => 'logo']
) .
Html::beginTag('nav', ['class' => 'navbar navbar-static-top', 'role' => 'navigation']) .
Html::tag(
    'a',
    Html::tag('span', 'Toggle navigation', ['class' => 'sr-only']),
    ['class' => 'sidebar-toggle', 'data-toggle' => 'offcanvas', 'role' => 'button']
) .
Html::beginTag('div', ['class' => 'navbar-custom-menu']) .
Html::beginTag('ul', ['class' => ['nav', 'navbar-nav']]) .
Html::beginTag('li', ['class' => ['dropdown', 'user', 'user-menu']]) .
Html::beginTag('a', ['class' => 'dropdown-toggle', 'data-toggle' => 'dropdown']) .
UserAvatar::widget(['user' => $user, 'size' => 45, 'options' => ['class' => 'user-image']]) .
Html::tag('span', ArrayHelper::getValue($user, 'fullName', 'Default user'), ['class' => ['user-name', 'hidden-xs']]) .
Html::tag('span', '', ['class' => 'caret']) .
Html::endTag('a') .
Html::ul([
    Html::a('Sign Out', Url::toRoute('/logout'))
], [
    'class' => 'dropdown-menu',
    'encode' => false
]) .
Html::endTag('li') .
Html::endTag('ul') .
Html::endTag('div') .
Html::endTag('nav') .
Html::endTag('header');
