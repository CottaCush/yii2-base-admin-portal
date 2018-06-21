<?php

namespace app\widgets;

use app\assets\DropdownFilterAsset;
use CottaCush\Yii2\Helpers\Html;
use CottaCush\Yii2\Widgets\BaseWidget;

/**
 * Class DropdownFilterWidget
 * @package app\widgets
 * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
 */
class DropdownFilterWidget extends BaseWidget
{
    public $label;
    public $prompt;
    public $model;
    public $selection;
    public $name;
    public $url;

    public function init()
    {
        parent::init();
        echo Html::beginForm($this->url, 'get', ['id' => 'dropdownFilterForm']);
        echo Html::beginTag('div', ['class' => 'form-group form-group-inline text-right']);
        echo Html::label(($this->label) ? $this->label : 'Filter', 'filterDropdown');
        echo Html::dropDownList(
            $this->name,
            $this->selection,
            $this->model,
            ['prompt' => $this->prompt, 'class' => 'form-control', 'id' => 'filterDropdown']
        );
        echo Html::endTag('div');
        echo Html::endForm();
        DropdownFilterAsset::register($this->view);
    }
}
