<?php

namespace app\assets;

use CottaCush\Yii2\Assets\AssetBundle;

/**
 * Class IoniconsAsset
 * @author Damilola Olaleye <damilola@cottacush.com>
 * @package app\assets
 */
class IoniconsAsset extends AssetBundle
{
    public $sourcePath = '@npm/ionicons';

    public $css = [
        'css/ionicons.css'
    ];
    public $productionCss = [
        'http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css'
    ];
}
