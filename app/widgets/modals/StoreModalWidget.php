<?php

namespace app\widgets\modals;

use app\constants\Messages;
use app\models\State;

/**
 * Class StoreModalWidget
 * @package app\widgets\modals
 * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
 */
class StoreModalWidget extends BaseModalWidget
{

    public function init()
    {
        parent::init();
        $this->title = ($this->populateFields ? 'Edit ' : 'Add ') . Messages::ENTITY_STORE;
    }

    public function renderContents()
    {
        parent::renderContents();
        echo $this->form->field($this->model, 'location_name');
        echo $this->form->field($this->model, 'location_code');
        echo $this->form->field($this->model, 'state')
            ->dropDownList(State::getDropdownMap('key', 'name'), [
                'prompt' => 'Select State'
            ]);
        echo $this->form->field($this->model, 'market_id')->input('number', ['data-integer-input' => true]);
    }
}
