<?php

use app\widgets\PageHeaderWidget;
use yii\helpers\Html;

PageHeaderWidget::widget([
    'title' => 'Dashboard',
    'icon' => 'dashboard',
]);

echo Html::tag('p', 'This is the dashboard');
