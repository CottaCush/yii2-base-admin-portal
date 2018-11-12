<?php

namespace app\assets;

use CottaCush\Yii2\Assets\AssetBundle;

/**
 * Class FontAwesomeAsset
 * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
 * @package app\assets
 */
class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@npm/font-awesome';

    public $css = [
        'css/font-awesome.min.css'
    ];
}
