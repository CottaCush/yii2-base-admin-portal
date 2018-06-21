<?php

namespace app\widgets;

use CottaCush\Yii2\Widgets\BaseUserAvatar;

/**
 * Class UserAvatar
 * @author Olajide Oye <jide@cottacush.com>
 * @package app\widgets
 */
class UserAvatar extends BaseUserAvatar
{
    protected $fallbackImage = '/images/avatars/default.png';
    protected $baseClass = ['base' => 'img-circle'];
}
