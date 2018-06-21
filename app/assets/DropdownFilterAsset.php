<?php

namespace app\assets;

/**
 * Class DropdownFilterAsset
 * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
 * @package app\assets
 */
class DropdownFilterAsset extends BaseAsset
{
    public $js = [
        'js/pages/dropdown-filter.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
