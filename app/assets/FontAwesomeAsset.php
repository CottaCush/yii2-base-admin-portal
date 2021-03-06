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
    public array $productionCss = [
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'
    ];
}
