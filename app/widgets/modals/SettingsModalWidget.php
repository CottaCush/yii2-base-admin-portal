<?php

namespace app\widgets\modals;

use app\models\Setting;
use yii\helpers\Html;

/**
 * Class SettingsModalWidget
 * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
 * @package app\widgets\modals
 */
class SettingsModalWidget extends BaseModalWidget
{
    public $hint;
    public $dropdownModel;

    /** @var  Setting */
    public $setting;

    public function init()
    {
        parent::init();
        $this->title = 'Edit Setting';
    }

    public function renderContents()
    {
        parent::renderContents();
        echo $this->form->field($this->model, 'value')
            ->dropDownList($this->dropdownModel, [
                'prompt' => 'Select an item'
            ])
            ->label(
                $this->setting->name . ' ' . Html::tag('span', '', [
                    'class' => 'fa fa-question-circle',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'right',
                    'title' => $this->setting->description
                ])
            )
            ->hint($this->hint);
    }
}
