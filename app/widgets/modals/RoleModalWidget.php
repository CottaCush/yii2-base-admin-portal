<?php

namespace app\widgets\modals;

use app\models\Role;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\assets\RoleAsset;
use CottaCush\Yii2\Widgets\Modals\ActiveFormModalWidget;
use app\constants\Messages;

/**
 * Class RoleModalWidget
 * @package app\widgets\modals
 * @author Taiwo Ladipo <ladipotaiwo01@gmail.com>
 * @author Bolade Oye <bolade@cottacush.com>
 */
class RoleModalWidget extends ActiveFormModalWidget
{
    /**@var Role $roleModel */
    public $roleModel;
    public $permissions = [];
    public $statuses;
    public $rolePermissions = [];

    public function init()
    {
        parent::init();
        RoleAsset::register($this->view);
        $this->title = ($this->populateFields ? 'Edit ' : 'Add ') . Messages::ENTITY_ROLE;
    }

    public function renderContents()
    {
        $strippedPermissions = ArrayHelper::map($this->permissions, 'id', 'label');
        reset($this->permissions);

        echo $this->form->field($this->model, 'label')->textInput(['class' => 'form-control']);

        echo $this->form->field($this->model, 'status')->dropDownList($this->statuses);

        echo Html::beginTag('fieldset');
        echo Html::tag('legend', 'Permissions');

        echo Html::beginTag('div', ['class' => 'checkbox']);
        echo Html::tag('label', Html::checkbox('', '', ['class' => 'select-all-check']) . ' Select All');
        echo Html::endTag('div');

        echo Html::tag('hr', '');

        echo Html::beginTag('div', ['class' => ['row', 'check-list']]);
        echo Html::checkboxList($this->model->formName() . "[permissions][]", $this->rolePermissions, $strippedPermissions, [
            'item' => function ($index, $label, $name, $isChecked, $value) {
                return '<div class="col-md-4">' . Html::checkbox($name, $isChecked, ['value' => $value, 'label' => $label,
                        'labelOptions' => ['class' => 'checkbox checkbox-inline no-margin']]) .
                    '</div>';
            }
        ]);

        echo Html::endTag('div');
        echo Html::endTag('fieldset');

        echo $this->form->field($this->model, 'id')->hiddenInput()->label(false);
    }
}
