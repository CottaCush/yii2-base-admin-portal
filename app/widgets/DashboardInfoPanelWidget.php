<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class DashboardInfoPanelWidget
 * @author Bolade Oye <boade@cottacush.com>
 * @package app\widgets
 */
class DashboardInfoPanelWidget extends Widget
{
    public $infoIcon;
    public $infoCount;
    public $infoTitle;
    public $infoUrl;
    public $headingColor;

    public function run()
    {
        echo Html::beginTag('a', ['href' => $this->infoUrl]);
        echo Html::beginTag('div', ['class' => 'panel info-panel__item']);

        echo Html::beginTag('div', ['class' => 'info-panel__theme-' . $this->headingColor]);
        echo Html::beginTag('div', ['class' => 'panel-body info-box']);

        echo Html::beginTag('div', ['class' => 'info-box__heading']);
        echo Html::tag('p', $this->infoTitle, ['class' => 'info-box__heading-title pull-left']);
        echo Html::tag('p', '', ['class' => 'info-box__heading-icon pull-right fa fa-' . $this->infoIcon]);
        echo Html::endTag('div');

        echo Html::tag('p', $this->infoCount, ['class' => 'info-box__value']);

        echo Html::endTag('div'); //info-box
        echo Html::endTag('div');

        echo Html::endTag('div');
        echo Html::endTag('a');
    }
}
