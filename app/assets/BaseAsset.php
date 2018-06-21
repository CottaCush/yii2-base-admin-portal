<?php

namespace app\assets;

use CottaCush\Yii2\Assets\AssetBundle;

/**
 * Class BaseAsset
 * Extend the CottaCush AssetBundle and set $basePath / $baseUrl properties
 * @author Olajide Oye <jide@cottacush.com>
 * @package app\assets
 */
class BaseAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
}
