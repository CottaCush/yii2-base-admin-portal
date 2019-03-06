<?php

namespace app\assets;

/**
 * Class InviteWidgetAsset
 * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
 * @package app\assets
 */
class InviteWidgetAsset extends BaseAsset
{
    public $js = [
        'js/pages/new-invite.js'
    ];

    public $depends = [
        'app\assets\AppAsset',
        'CottaCush\Yii2\Assets\FormAsset',
        'CottaCush\Yii2\Assets\ModalAsset'
    ];
}
