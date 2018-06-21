<?php

namespace app\assets;

/**
 * Class AppAsset
 * @author Olajide Oye <jide@cottacush.com>
 * @package app\assets
 */
class AppAsset extends BaseAsset
{
    public $css = [
        'css/styles.css',
    ];
    public $js = [
        'js/theme-options.js',
        'js/theme.min.js',
        'js/number-input.js'
    ];
    public $depends = [
        'app\assets\FastClickAsset',
        'app\assets\JquerySlimScrollAsset',
        'app\assets\FontAwesomeAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
