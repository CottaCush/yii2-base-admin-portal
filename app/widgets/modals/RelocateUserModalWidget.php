<?php

namespace app\widgets\modals;


/**
 * Class RelocateUserModalWidget
 * @package app\widgets\modals
 * @author Bolade Oye <bolade@cottacush.com>
 */
class RelocateUserModalWidget extends BaseModalWidget
{
    public $stores = [];

    public function init()
    {
        parent::init();
        $this->footerSubmit = 'Save';
        $this->title = 'Relocate User';
    }

    public function renderContents()
    {
        parent::renderContents();

        echo $this->form->field($this->model, 'old_store')
            ->textInput(['readonly' => true])
            ->label('Relocate From');
        echo $this->form->field($this->model, 'store')
            ->dropDownList($this->stores, ['prompt' => 'Please choose one'])
            ->label('To');
    }
}
