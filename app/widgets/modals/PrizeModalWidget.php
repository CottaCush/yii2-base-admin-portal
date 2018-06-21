<?php

namespace app\widgets\modals;

use app\constants\Messages;
use CottaCush\Yii2\Widgets\Modals\ActiveFormModalWidget;
Use app\models\Prize;

/**
 * Class PrizeModalWidget
 * @package app\widgets\modals
 * @author Bolade Oye <bolade@cottacush.com>
 */
class PrizeModalWidget extends BaseModalWidget
{

    public function init()
    {
        parent::init();
        $this->title = ($this->populateFields ? 'Edit ' : 'Add ') . Messages::ENTITY_PRIZE;
    }

    public function renderContents()
    {
        parent::renderContents();
        echo $this->form->field($this->model, 'name');
        echo $this->form->field($this->model, 'sku');
        echo $this->form->field($this->model, 'quantity')
            ->textInput(['type' => 'number', 'data-integer-input' => true, 'min' => '0']);
        echo $this->form->field($this->model, 'value')->dropDownList(Prize::VALUE_OPTIONS, ['prompt' => 'Please choose one']);
    }
}
