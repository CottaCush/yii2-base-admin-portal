<?php

namespace app\widgets\modals;

use CottaCush\Yii2\Widgets\Modals\ActiveFormModalWidget;

/**
 * Class BaseModalWidget
 * @package app\widgets\modals
 * @author Bolade Oye <bolade@cottacush.com>
 */
class BaseModalWidget extends ActiveFormModalWidget
{

    public function init()
    {
        parent::init();
        $this->footerSubmit = $this->populateFields ? 'Save ' : 'Add ';
    }

    public function renderContents()
    {
        echo $this->form->field($this->model, 'id')->hiddenInput()->label(false);
    }
}
